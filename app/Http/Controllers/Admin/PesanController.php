<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PesanController extends Controller
{
    protected $breadcrumbs, $pesan;
    public function __construct(PesanRepository $pesan)
    {
        $this->middleware('auth');

        $this->pesan = $pesan;
        view()->share(['title' => 'Pesan']);
        $this->breadcrumbs = array(
            ['url' => null, 'caption' => 'Konten Manajemen', 'parameters' => null],
            ['url' => 'admin.pesan', 'caption' => 'Pesan', 'parameters' => null],
        );
    }

    public function index()
    {
        Session::put('menu_aktif', 'admin.pesan');

        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null,
            'caption' => 'Data Pesan',
            'parameters' => null,
            'desc' => 'Manajemen data pesan'
        ]);
        return view('admin.pesan.index', compact('breadcrumbs'));
    }

    public function info(Request $request)
    {
        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null,
            'caption' => $request->has('id') ? 'Ubah Pesan' : 'Tambah Pesan',
            'parameters' => $request->input('id') ?? null,
            'desc' => $request->has('id') ?
                'Ubah informasi data pesan' :
                'Tambah data pesan baru'
        ]);
        $id = $request->input('id') ?? null;
        $pesan = ($id != null) ? $this->pesan->find($id) : [];
        return view('admin.pesan.info', compact('breadcrumbs', 'pesan'));
    }

    public function search(Request $request)
    {
        $pesan = $this->pesan->search($request);
        if ($request->has('ajax')) return $pesan;
        $action = $request->input('action') ?? array();
        return view('admin.pesan._table', compact('pesan', 'action'));
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) return abort(404);
        $pesan = $this->pesan->delete($request->input('id'));
        if ($request->has('ajax')) return $pesan;
        return redirect()->route('admin.pesan')
            ->with('success', 'Pesan berhasil dihapus');
    }

}
