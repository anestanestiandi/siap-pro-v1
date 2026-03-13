<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\PelayananKeprotokolan;
use App\Models\Persidangan;
use App\Models\KunjunganKerja;
use App\Models\AdministrasiPerjalananDinas;
use App\Models\ModuleRead;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SidebarComposer
{
    public function compose(View $view)
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            $view->with('moduleCounts', [
                'pelayanan-keprotokolan' => 0,
                'persidangan' => 0,
                'kunjungan-kerja' => 0,
                'administrasi-perjalanan-dinas' => 0,
            ]);
            return;
        }

        $userId = Auth::id();
        $modules = [
            'pelayanan-keprotokolan' => PelayananKeprotokolan::class,
            'persidangan' => Persidangan::class,
            'kunjungan-kerja' => KunjunganKerja::class,
            'administrasi-perjalanan-dinas' => AdministrasiPerjalananDinas::class,
        ];

        $counts = [];
        $adminIds = User::where('role', 'admin')->pluck('id_user')->toArray();

        foreach ($modules as $name => $modelClass) {
            $lastRead = ModuleRead::where('id_user', $userId)
                ->where('module_name', $name)
                ->value('last_read_at');

            $query = $modelClass::whereIn('created_by', $adminIds);
            
            if ($lastRead) {
                $query->where('created_at', '>', $lastRead);
            }

            $counts[$name] = $query->count();
        }

        $view->with('moduleCounts', $counts);
    }
}
