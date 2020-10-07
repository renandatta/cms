<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KontenController extends Controller
{
    protected $breadcrumbs, $konten;
    public function __construct(KontenRepository $konten)
    {
        $this->middleware('auth');

        $this->konten = $konten;
        view()->share(['title' => 'Konten']);
        $this->breadcrumbs = array(
            ['url' => null, 'caption' => 'Konten Manajemen', 'parameters' => null],
            ['url' => 'admin.konten', 'caption' => 'Konten', 'parameters' => null],
        );
    }

    public function index()
    {
        Session::put('menu_aktif', 'admin.konten');

        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null,
            'caption' => 'Data Konten',
            'parameters' => null,
            'desc' => 'Manajemen data konten'
        ]);
        return view('admin.konten.index', compact('breadcrumbs'));
    }

    public function info(Request $request)
    {
        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null,
            'caption' => $request->has('id') ? 'Ubah Konten' : 'Tambah Konten',
            'parameters' => $request->input('id') ?? null,
            'desc' => $request->has('id') ?
                'Ubah informasi data konten' :
                'Tambah data konten baru'
        ]);
        $id = $request->input('id') ?? null;
        $konten = ($id != null) ? $this->konten->find($id) : [];
        $halaman_id = $request->input('halaman_id') ?? null;
        if (!empty($konten) && $konten->halaman_id != null) $halaman_id = $konten->halaman_id;
        return view('admin.konten.info', compact('breadcrumbs', 'konten', 'halaman_id'));
    }

    public function search(Request $request)
    {
        $konten = $this->konten->search($request);
        if ($request->has('ajax')) return $konten;
        $action = $request->input('action') ?? array();
        return view('admin.konten._table', compact('konten', 'action'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:4|max:255',
            'konten' => 'required|max:255',
        ]);

        $konten = $this->konten->save($request);
        if ($request->has('ajax')) return $konten;
        if ($request->has('halaman_id'))
            return redirect()->route('admin.halaman.konten', 'id=' . $request->input('halaman_id'))
                ->with('success', 'Konten berhasil disimpan');
        return redirect()->route('admin.konten')
            ->with('success', 'Konten berhasil disimpan');
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) return abort(404);
        $konten = $this->konten->delete($request->input('id'));
        if ($request->has('ajax')) return $konten;
        return redirect()->route('admin.konten')
            ->with('success', 'Konten berhasil dihapus');
    }

}
