<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    protected $breadcrumbs, $user;
    public function __construct(UserRepository $user)
    {
        $this->middleware('auth');

        $this->user = $user;
        view()->share(['title' => 'User']);
        $this->breadcrumbs = array(
            ['url' => null, 'caption' => 'Konten Manajemen', 'parameters' => null],
            ['url' => 'admin.user', 'caption' => 'User', 'parameters' => null],
        );
    }

    public function index()
    {
        Session::put('menu_aktif', 'admin.user');

        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null,
            'caption' => 'Data User',
            'parameters' => null,
            'desc' => 'Manajemen data user program'
        ]);
        return view('admin.user.index', compact('breadcrumbs'));
    }

    public function info(Request $request)
    {
        $breadcrumbs = $this->breadcrumbs;
        array_push($breadcrumbs, [
            'url' => null,
            'caption' => $request->has('id') ? 'Ubah User' : 'Tambah User',
            'parameters' => $request->input('id') ?? null,
            'desc' => $request->has('id') ?
                'Ubah informasi data user program' :
                'Tambah data user program baru'
        ]);
        $id = $request->input('id') ?? null;
        $user = ($id != null) ? $this->user->find($id) : [];
        return view('admin.user.info', compact('breadcrumbs', 'user'));
    }

    public function search(Request $request)
    {
        $user = $this->user->search($request);
        if ($request->has('ajax')) return $user;
        $action = $request->input('action') ?? array();
        return view('admin.user._table', compact('user', 'action'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:4|max:255',
            'email' => 'required|min:4|max:255',
        ]);

        $user = $this->user->save($request);
        if ($request->has('ajax')) return $user;
        return redirect()->route('admin.user')
            ->with('success', 'User berhasil disimpan');
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) return abort(404);
        $user = $this->user->delete($request->input('id'));
        if ($request->has('ajax')) return $user;
        return redirect()->route('admin.user')
            ->with('success', 'User berhasil dihapus');
    }

}
