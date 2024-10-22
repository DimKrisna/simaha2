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

        return redirect()->route('tambahUser')->with('success', 'User Baru Berhasil di Tambahkan!!');
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

            switch ($user->id_role) {
                case 1:
                    return redirect()->route('ormawa');
                case 2:
                    return redirect()->route('read');
                case 3:
                    return redirect()->route('baca');
                case 4:
                    return redirect()->route('ormawabishum');
                case 5:
                    return redirect()->route('DataProdi');
                case 6:
                    return redirect()->route('ormawafst');
                case 7:
                    return redirect()->route('datafakultasdiploma');
                default:
                    return redirect('/home');
            }
        } else {


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
