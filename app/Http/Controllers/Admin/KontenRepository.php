<?php

namespace App\Http\Controllers\Admin;

use App\Models\Konten;
use Illuminate\Http\Request;

class KontenRepository
{
    protected $konten, $skip = array();
    public function __construct(Konten $konten)
    {
        $this->skip = array();
        $this->konten = $konten;
    }

    public function search(Request $request)
    {
        $konten = $this->konten->orderBy('id', 'desc');

        $halaman_id = $request->input('halaman_id') ?? '';
        if ($halaman_id != '')
            $konten = $konten->where('halaman_id', $halaman_id);
        else
            $konten = $konten->whereNull('halaman_id');

        return $konten->get();
    }

    public function find($value, $column = 'id')
    {
        return $this->konten->where($column, '=', $value)->first();
    }

    public function save(Request $request)
    {
        if (!$request->has('id'))
            $result = $this->konten->create($request->all());
        else {
            $result = $this->konten->find($request->input('id'));
            $result->update($request->all());
        }
        return $result;
    }

    public function delete($id)
    {
        $result = $this->konten->find($id);
        $result->delete();
        return $result;
    }
}
