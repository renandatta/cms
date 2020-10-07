<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KunjunganRepository
{
    protected $kunjungan, $skip = array();
    public function __construct(Kunjungan $kunjungan)
    {
        $this->skip = array();
        $this->kunjungan = $kunjungan;
    }

    public function search()
    {
        return $this->kunjungan->orderBy('id', 'desc')->get();
    }

    public function find($value, $column = 'id')
    {
        return $this->kunjungan->where($column, '=', $value)->first();
    }

    public function save(Request $request)
    {
        if (!$request->has('id'))
            $result = $this->kunjungan->create($request->all());
        else {
            $result = $this->kunjungan->find($request->input('id'));
            $result->update($request->all());
        }
        return $result;
    }

    public function delete($id)
    {
        $result = $this->kunjungan->find($id);
        $result->delete();
        return $result;
    }
}
