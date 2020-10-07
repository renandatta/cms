<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriRepository
{
    protected $kategori, $skip = array();
    public function __construct(Kategori $kategori)
    {
        $this->skip = array();
        $this->kategori = $kategori;
    }

    public function search()
    {
        return $this->kategori->orderBy('id', 'desc')->get();
    }

    public function nested_data($parent_kode = '#')
    {
        $result = array();

        $data = $this->kategori
            ->select('id', 'nama', 'kode', 'parent_kode', 'slug')
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
        return $this->kategori->where($column, '=', $value)->first();
    }

    public function save(Request $request)
    {
        if (!$request->has('id'))
            $result = $this->kategori->create($request->all());
        else {
            $result = $this->kategori->find($request->input('id'));
            $result->update($request->all());
        }
        return $result;
    }

    public function delete($id)
    {
        $result = $this->kategori->find($id);
        $result->delete();
        return $result;
    }

    public function kode_otomatis($parent_kode)
    {
        $last_row = $this->kategori->where('parent_kode', '=', $parent_kode)->orderBy('kode', 'desc')->first();
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
        $fitur = $this->kategori->find($request->input('id'));
        $kode_asal = $fitur->kode;
        $kode_array = explode(".", $fitur->kode);
        $kode = $kode_array[count($kode_array)-1];
        $kode_tujuan = $request->input('arah') == 'up' ? intval($kode) - 1 : intval($kode) + 1;
        if (strlen($kode_tujuan) == 1) $kode_tujuan = '0' . $kode_tujuan;
        if ($fitur->parent_kode != '#') $kode_tujuan = $fitur->parent_kode. '.' .$kode_tujuan;
        $fitur_tujuan = $this->kategori->where('kode', '=', $kode_tujuan)->first();

        if (!empty($fitur_tujuan)) {
            $temp_kode = mt_rand(111,999);

            //=====tujuan pindah ke temp
            $this->kategori->where('kode', $kode_tujuan)->update(['kode' => $temp_kode]);
            $sub = $this->kategori->where('parent_kode', $kode_tujuan)->count();
            if ($sub > 0)
                $this->kategori->where('parent_kode', $kode_tujuan)
                    ->update([
                        'kode' => DB::raw("replace(kode, parent_kode, '". $temp_kode ."')"),
                        'parent_kode' => $temp_kode
                    ]);

            //=====asal pindah ke tujuan
            $this->kategori->where('kode', $kode_asal)->update(['kode' => $kode_tujuan]);
            $sub = $this->kategori->where('parent_kode', $kode_asal)->count();
            if ($sub > 0)
                $this->kategori->where('parent_kode', $kode_asal)
                    ->update([
                        'kode' => DB::raw("replace(kode, parent_kode, '". $kode_tujuan ."')"),
                        'parent_kode' => $kode_tujuan
                    ]);

            //=====temp pindah ke asal
            $this->kategori->where('kode', (string) $temp_kode)->update(['kode' => (string) $kode_asal]);
            $sub = $this->kategori->where('parent_kode', (string) $temp_kode)->count();
            if ($sub > 0)
                $this->kategori->where('parent_kode', (string) $temp_kode)
                    ->update([
                        'kode' => DB::raw("replace(kode, parent_kode, '". (string) $kode_asal ."')"),
                        'parent_kode' => (string) $kode_asal
                    ]);
        }
        return $fitur;
    }
}
