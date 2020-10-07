<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KategoriController extends Controller
{
    protected $breadcrumbs, $kategori;
    public function __construct(KategoriRepository $kategori)
    {
        $this->middleware('auth');

        $this->kategori = $kategori;
        view()->share(['title' => 'Kategori']);
        $this->breadcrumbs = array(
            ['url' => null, 'caption' => 'Konten Manajemen', 'parameters' => null],
            ['url' => 'admin.kategori', 'caption' => 'Kategori', 'parameters' => null],
        );
    }

    public function index()
    {
        Session::put('menu_aktif', 'admin.kategori');

        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null, 'caption' => 'Data Kategori', 'parameters' => null, 'desc' => 'Manajemen data kategori'
        ]);
        return view('admin.kategori.index', compact('breadcrumbs'));
    }

    public function info(Request $request)
    {
        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null,
            'caption' => $request->has('id') ? 'Ubah Kategori' : 'Tambah Kategori',
            'parameters' => $request->input('id') ?? null,
            'desc' => $request->has('id') ?
                'Ubah informasi data kategori' :
                'Tambah data kategori baru'
        ]);
        $id = $request->input('id') ?? null;
        $kategori = ($id != null) ? $this->kategori->find($id) : [];
        $parent_kode = $request->input('parent_kode') ?? '#';
        $parent = $this->kategori->find($parent_kode, 'kode');
        $kode = !empty($kategori) ? $kategori->kode : $this->kategori->kode_otomatis($parent_kode);
        return view('admin.kategori.info', compact('breadcrumbs', 'kategori', 'parent_kode', 'kode', 'parent'));
    }

    public function search(Request $request)
    {
        $parentKode = $request->input('parent_kode') ?? '#';
        $kategori = $this->kategori->nested_data($parentKode);
        $action = $request->input('action') ?? array();
        if ($request->has('ajax')) return $kategori;
        return view('admin.kategori._table', compact('kategori', 'action'));
    }

    public function save(Request $request)
    {
        $kategori = $this->kategori->save($request);
        if ($request->has('ajax')) return $kategori;
        return redirect()->route('admin.kategori')
            ->with('success', 'Kategori berhasil disimpan');
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) return abort(404);
        $kategori = $this->kategori->delete($request->input('id'));
        if ($request->has('ajax')) return $kategori;
        return redirect()->route('admin.kategori')
            ->with('success', 'Kategori berhasil dihapus');
    }

    public function kode_otomatis(Request $request)
    {
        if (!$request->input('parent_kode')) return abort(404);
        return $this->kategori->kode_otomatis($request->input('parent_kode'));
    }

    public function reposisi(Request $request)
    {
        if (!$request->input('id')) return abort(404);
        return $this->kategori->reposisi($request);
    }
}
