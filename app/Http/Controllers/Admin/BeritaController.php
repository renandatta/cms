<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BeritaController extends Controller
{
    protected $breadcrumbs, $berita;
    public function __construct(BeritaRepository $berita, KategoriRepository $kategori)
    {
        $this->middleware('auth');

        $this->berita = $berita;
        view()->share(['title' => 'Berita']);
        $this->breadcrumbs = array(
            ['url' => null, 'caption' => 'Konten Manajemen', 'parameters' => null],
            ['url' => 'admin.berita', 'caption' => 'Berita', 'parameters' => null],
        );
        view()->share(['kategori' => $kategori->search()]);
    }

    public function index()
    {
        Session::put('menu_aktif', 'admin.berita');

        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null,
            'caption' => 'Data Berita',
            'parameters' => null,
            'desc' => 'Manajemen data berita'
        ]);
        return view('admin.berita.index', compact('breadcrumbs'));
    }

    public function info(Request $request)
    {
        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null,
            'caption' => $request->has('id') ? 'Ubah Berita' : 'Tambah Berita',
            'parameters' => $request->input('id') ?? null,
            'desc' => $request->has('id') ?
                'Ubah informasi data berita' :
                'Tambah data berita baru'
        ]);
        $id = $request->input('id') ?? null;
        $berita = ($id != null) ? $this->berita->find($id) : [];
        return view('admin.berita.info', compact('breadcrumbs', 'berita'));
    }

    public function search(Request $request)
    {
        $berita = $this->berita->search($request);
        if ($request->has('ajax')) return $berita;
        $action = $request->input('action') ?? array();
        return view('admin.berita._table', compact('berita', 'action'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|numeric',
            'judul' => 'required|min:4|max:255',
            'konten' => 'required|min:4|max:255',
        ]);

        $request->merge(['tanggal' => unformat_date($request->input('tanggal'))]);

        $berita = $this->berita->save($request);
        if ($request->has('ajax')) return $berita;
        return redirect()->route('admin.berita')
            ->with('success', 'Berita berhasil disimpan');
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) return abort(404);
        $berita = $this->berita->delete($request->input('id'));
        if ($request->has('ajax')) return $berita;
        return redirect()->route('admin.berita')
            ->with('success', 'Berita berhasil dihapus');
    }

}
