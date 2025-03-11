<?php

namespace App\View\Components;

use App\Models\MnKategori;
use App\Models\Roles;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Layout extends Component
{
    /**
     * Create a new component instance.
     */
    public $title;
    public function __construct($title = null)
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
{
    $menus = array();
    $role = Roles::find(auth()->user()->role_id);

    if ($role) {
        switch ($role->id) {
            case 1:
                // Mahasiswa
                $menus[] = MnKategori::with(['list' => function($query) {
                    $query->where('status', 'Aktif');
                }, 'list.sub'])->find(1);
                break;
            case 2:
                // Kemahasiswaan
                $menus[] = MnKategori::with(['list' => function($query) {
                    $query->where('status', 'Aktif');
                }, 'list.sub'])->find(2);
                break;
            case 3:
                // Wakil Rektor 3
                $menus[] = MnKategori::with(['list' => function($query) {
                    $query->where('status', 'Aktif');
                }, 'list.sub'])->find(3);
                break;
            case 4:
                // Fakultas Bishum
                $menus[] = MnKategori::with(['list' => function($query) {
                    $query->where('status', 'Aktif');
                }, 'list.sub'])->find(4);
                break;
            case 5:
                // Prodi
                $menus[] = MnKategori::with(['list' => function($query) {
                    $query->where('status', 'Aktif');
                }, 'list.sub'])->find(5);
                break;
            case 6:
                // Fakultas Saintek
                $menus[] = MnKategori::with(['list' => function($query) {
                    $query->where('status', 'Aktif');
                }, 'list.sub'])->find(6);
                break;
            case 7:
                // Fakultas Diploma
                $menus[] = MnKategori::with(['list' => function($query) {
                    $query->where('status', 'Aktif');
                }, 'list.sub'])->find(7);
                break;
        }
    }

    return view('layout', compact('menus'));
}
}
