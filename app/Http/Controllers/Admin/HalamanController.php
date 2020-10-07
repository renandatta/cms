<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HalamanController extends Controller
{
    protected $breadcrumbs, $halaman;
    public function __construct(HalamanRepository $halaman)
    {
        $this->middleware('auth');

        $this->halaman = $halaman;
        view()->share(['title' => 'Halaman']);
        $this->breadcrumbs = array(
            ['url' => null, 'caption' => 'Konten Manajemen', 'parameters' => null],
            ['url' => 'admin.halaman', 'caption' => 'Halaman', 'parameters' => null],
        );
    }

    public function index()
    {
        Session::put('menu_aktif', 'admin.halaman');

        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null, 'caption' => 'Data Halaman', 'parameters' => null, 'desc' => 'Manajemen data halaman'
        ]);
        return view('admin.halaman.index', compact('breadcrumbs'));
    }

    public function info(Request $request)
    {
        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null,
            'caption' => $request->has('id') ? 'Ubah Halaman' : 'Tambah Halaman',
            'parameters' => $request->input('id') ?? null,
            'desc' => $request->has('id') ?
                'Ubah informasi data halaman' :
                'Tambah data halaman baru'
        ]);
        $id = $request->input('id') ?? null;
        $halaman = ($id != null) ? $this->halaman->find($id) : [];
        $parent_kode = $request->input('parent_kode') ?? '#';
        $parent = $this->halaman->find($parent_kode, 'kode');
        $kode = !empty($halaman) ? $halaman->kode : $this->halaman->kode_otomatis($parent_kode);
        return view('admin.halaman.info', compact('breadcrumbs', 'halaman', 'parent_kode', 'kode', 'parent'));
    }

    public function search(Request $request)
    {
        $parentKode = $request->input('parent_kode') ?? '#';
        $halaman = $this->halaman->nested_data($parentKode);
        $action = $request->input('action') ?? array();
        return view('admin.halaman._table', compact('halaman', 'action'));
    }

    public function save(Request $request)
    {
        $halaman = $this->halaman->save($request);
        if ($request->has('ajax')) return $halaman;
        return redirect()->route('admin.halaman')
            ->with('success', 'Halaman berhasil disimpan');
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) return abort(404);
        $halaman = $this->halaman->delete($request->input('id'));
        if ($request->has('ajax')) return $halaman;
        return redirect()->route('admin.halaman')
            ->with('success', 'Halaman berhasil dihapus');
    }

    public function kode_otomatis(Request $request)
    {
        if (!$request->input('parent_kode')) return abort(404);
        return $this->halaman->kode_otomatis($request->input('parent_kode'));
    }

    public function reposisi(Request $request)
    {
        if (!$request->input('id')) return abort(404);
        return $this->halaman->reposisi($request);
    }

    public function konten(Request $request)
    {
        if (!$request->has('id')) return abort(404);
        $id = $request->input('id');
        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null,
            'caption' => 'Konten Halaman',
            'parameters' => $id,
            'desc' => 'Manajemen konten yang dalam halaman'
        ]);
        $halaman = $this->halaman->find($id);
        return view('admin.halaman.konten', compact('breadcrumbs', 'halaman'));
    }
}
