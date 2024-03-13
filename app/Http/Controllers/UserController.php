<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //fungsi untuk register
    public function register()
    {
        $data['title'] = 'Register';
        return view('user/register', $data);
    }

    public function register_action(Request $request)
    {
        $request->validate([
            'nama_singkatan' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
            'id_role'=> 'required',
        ]);

        $user = new User([
            'nama_singkatan' => $request->nama_singkatan,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'id_role'=> $request->id_role,
        ]);

        if ($request->has('id_ormawa')) {
            $user->id_ormawa = $request->id_ormawa;
        }
        $user->save();

        return redirect()->route('login')->with('success', 'Registration success. Please login!');
    }


    public function login()
    {
        $data['title'] = 'Login';
        return view('user/login', $data);
    }
    //fungsi untuk login 
    public function login_action(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->id_role === 1) {
                return dd("login sebagai mahasiswa");
            } elseif ($user->id_role === 2) {
                return redirect()->route('dataHima'); 
            } elseif ($user->id_role === 3) {
                return dd("sebagai apa");
            } elseif ($user->id_role === 4) {
                return dd("sebagai apa");
            } elseif ($user->id_role === 5) {
                return dd("ini sona ada di sini");
            } 
        }

        return back()->withErrors([
            'password' => 'Wrong username or password',
        ]);
    }

    public function password()
    {
        $data['title'] = 'Change Password';
        return view('user/password', $data);
    }
   //fungsi untuk mengganti password
    public function password_action(Request $request)
    {
        $request->validate([
            'old_password' => 'required|current_password',
            'new_password' => 'required|confirmed',
        ]);
        $user = User::find(Auth::id());
        $user->password = Hash::make($request->new_password);
        $user->save();
        $request->session()->regenerate();
        return back()->with('success', 'Password changed!');
    }
    //fungsi logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
