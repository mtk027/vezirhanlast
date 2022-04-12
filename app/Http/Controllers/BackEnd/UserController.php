<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $data['item'] = User::find(Auth::id());

        return view('admin.users.index', $data);
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->password) {
            $password = bcrypt($request->password);
        }else{
            $password = $user->password;
        }
        $user->update([
            'name'     => $request->name,
            'password' => $password,
        ]);
        return redirect()->route('dashboard.users.index');
    }
}
