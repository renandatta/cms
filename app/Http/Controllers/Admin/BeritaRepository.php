<?php

namespace App\Http\Controllers\Admin;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaRepository
{
    protected $berita, $skip = array();
    public function __construct(Berita $berita)
    {
        $this->skip = array();
        $this->berita = $berita;
    }

    public function search(Request $request)
    {
        $berita = $this->berita->orderBy('id', 'desc');

        $judul = $request->input('judul') ?? '';
        if ($judul != '')
            $berita = $berita->where('judul', 'like', '%'. $judul .'%');

        $konten = $request->input('konten') ?? '';
        if ($konten != '')
            $berita = $berita->where('konten', 'like', '%'. $konten .'%');

        $tanggal = $request->input('tanggal') ?? '';
        if ($tanggal != '')
            $berita = $berita->where('tanggal', '=', unformat_date($tanggal));

        if ($request->has('paginate'))
            return $berita->paginate($request->input('paginate'));
        return $berita->get();
    }

    public function find($value, $column = 'id')
    {
        return $this->berita->where($column, '=', $value)->first();
    }

    public function save(Request $request)
    {
        if (!$request->has('id'))
            $result = $this->berita->create($request->all());
        else {
            $result = $this->berita->find($request->input('id'));
            $result->update($request->all());
        }
        return $result;
    }

    public function delete($id)
    {
        $result = $this->berita->find($id);
        $result->delete();
        return $result;
    }
}
