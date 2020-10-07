<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pesan;
use Illuminate\Http\Request;

class PesanRepository
{
    protected $pesan, $skip = array();
    public function __construct(Pesan $pesan)
    {
        $this->skip = array();
        $this->pesan = $pesan;
    }

    public function search(Request $request)
    {
        $pesan = $this->pesan->orderBy('id', 'desc');
        if ($request->has('paginate'))
            return $pesan->paginate($request->input('paginate'));
        return $pesan->get();
    }

    public function find($value, $column = 'id')
    {
        return $this->pesan->where($column, '=', $value)->first();
    }

    public function save(Request $request)
    {
        if (!$request->has('id'))
            $result = $this->pesan->create($request->all());
        else {
            $result = $this->pesan->find($request->input('id'));
            $result->update($request->all());
        }
        return $result;
    }

    public function delete($id)
    {
        $result = $this->pesan->find($id);
        $result->delete();
        return $result;
    }
}
