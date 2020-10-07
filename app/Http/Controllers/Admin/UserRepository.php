<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function search(Request $request)
    {
        $user = $this->user->orderBy('nama', 'asc')
            ->with(['last_login']);

        $nama = $request->input('nama') ?? '';
        $user = $user->where('nama', 'like', '%'. $nama .'%');

        $email = $request->input('email') ?? '';
        $user = $user->where('email', 'like', '%'. $email .'%');

        if ($request->has('paginate'))
            return $user->paginate($request->input('paginate'));
        return $user->get();
    }

    public function find($value, $column = 'id')
    {
        return $this->user->where($column, $value)->first();
    }

    public function save(Request $request)
    {
        if (!$request->has('id'))
            $result = $this->user->create($request->all());
        else {
            $result = $this->user->find($request->input('id'));
            $result->update($request->all());
        }
        $password = $request->input('password') ?? '';
        if ($password != '') {
            $result->password = Hash::make($password);
            $result->save();
        }
        return $result;
    }

    public function delete($id)
    {
        $result = $this->user->find($id);
        $result->delete();
        return $result;
    }
}
