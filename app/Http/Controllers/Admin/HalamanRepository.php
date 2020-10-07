<?php

namespace App\Http\Controllers\Admin;

use App\Models\Halaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HalamanRepository
{
    protected $halaman, $skip = array();
    public function __construct(Halaman $halaman)
    {
        $this->skip = array();
        $this->halaman = $halaman;
    }

    public function search()
    {
        return $this->halaman->orderBy('kode', 'asc')->get();
    }

    public function nested_data($parent_kode = '#')
    {
        $result = array();

        $data = $this->halaman
            ->select('id', 'nama', 'kode', 'parent_kode')
            ->where('parent_kode', 'like', ($parent_kode == '#' ? '' : $parent_kode). '%')
            ->orderBy('kode')
            ->get();
        if (count($data) > 0) $result = $this->get_children_from_array($data, $parent_kode);
        return $result;
    }

    public function get_children_from_array($data, $parent_kode)
    {
        $result = array();
        foreach ($data as $item) {
            if (!in_array($item->id, $this->skip) && $item->parent_kode == $parent_kode) {
                array_push($this->skip, $item->id);
                $item->children = $this->get_children_from_array($data, $item->kode);
                array_push($result, $item);
            }
        }
        return $result;
    }

    public function find($value, $column = 'id')
    {
        return $this->halaman->where($column, '=', $value)->first();
    }

    public function save(Request $request)
    {
        if (!$request->has('id'))
            $result = $this->halaman->create($request->all());
        else {
            $result = $this->halaman->find($request->input('id'));
            $result->update($request->all());
        }
        return $result;
    }

    public function delete($id)
    {
        $result = $this->halaman->find($id);
        $result->delete();
        return $result;
    }

    public function kode_otomatis($parent_kode)
    {
        $last_row = $this->halaman->where('parent_kode', '=', $parent_kode)->orderBy('kode', 'desc')->first();
        $kode = '01';
        if (!empty($last_row)) {
            $temp = explode(".", $last_row->kode);
            $kode = intval($temp[count($temp)-1])+1;
            if (strlen($kode) == 1) $kode = '0' . $kode;
        }
        return $parent_kode == '#' ? $kode : $parent_kode . '.' . $kode;
    }

    public function reposisi(Request $request)
    {
        $fitur = $this->halaman->find($request->input('id'));
        $kode_asal = $fitur->kode;
        $kode_array = explode(".", $fitur->kode);
        $kode = $kode_array[count($kode_array)-1];
        $kode_tujuan = $request->input('arah') == 'up' ? intval($kode) - 1 : intval($kode) + 1;
        if (strlen($kode_tujuan) == 1) $kode_tujuan = '0' . $kode_tujuan;
        if ($fitur->parent_kode != '#') $kode_tujuan = $fitur->parent_kode. '.' .$kode_tujuan;
        $fitur_tujuan = $this->halaman->where('kode', '=', $kode_tujuan)->first();

        if (!empty($fitur_tujuan)) {
            $temp_kode = mt_rand(111,999);

            //=====tujuan pindah ke temp
            $this->halaman->where('kode', $kode_tujuan)->update(['kode' => $temp_kode]);
            $sub = $this->halaman->where('parent_kode', $kode_tujuan)->count();
            if ($sub > 0)
                $this->halaman->where('parent_kode', $kode_tujuan)
                    ->update([
                        'kode' => DB::raw("replace(kode, parent_kode, '". $temp_kode ."')"),
                        'parent_kode' => $temp_kode
                    ]);

            //=====asal pindah ke tujuan
            $this->halaman->where('kode', $kode_asal)->update(['kode' => $kode_tujuan]);
            $sub = $this->halaman->where('parent_kode', $kode_asal)->count();
            if ($sub > 0)
                $this->halaman->where('parent_kode', $kode_asal)
                    ->update([
                        'kode' => DB::raw("replace(kode, parent_kode, '". $kode_tujuan ."')"),
                        'parent_kode' => $kode_tujuan
                    ]);

            //=====temp pindah ke asal
            $this->halaman->where('kode', (string) $temp_kode)->update(['kode' => (string) $kode_asal]);
            $sub = $this->halaman->where('parent_kode', (string) $temp_kode)->count();
            if ($sub > 0)
                $this->halaman->where('parent_kode', (string) $temp_kode)
                    ->update([
                        'kode' => DB::raw("replace(kode, parent_kode, '". (string) $kode_asal ."')"),
                        'parent_kode' => (string) $kode_asal
                    ]);
        }
        return $fitur;
    }
}
