<?php

namespace App\Http\Controllers;

use App\Models\HistoryLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Models\AppSetting;

class ManagementUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter Role
        if ($request->has('role') && $request->role != '' && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        // Order by latest
        $users = $query->latest()->paginate(10)->onEachSide(1)->withQueryString();

        return view('management-user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('management-user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'role' => ['required', 'in:super_admin,admin,eksternal'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $newUser = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'role' => $request->role,
            'jenis_kelamin' => $request->jenis_kelamin,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        HistoryLog::create([
            'model_type'  => User::class,
            'model_id'    => $newUser->id_user,
            'user_id'     => Auth::id(),
            'action'      => 'create',
            'description' => 'User baru ditambahkan: ' . $newUser->username,
            'status'      => 'success',
        ]);

        return redirect()->route('management-user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not implemented/needed for now
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('management-user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('tb_user')->ignore($user->id_user, 'id_user')],
            'role' => ['required', 'in:super_admin,admin,eksternal'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'is_active' => ['required', 'boolean'],
        ]);

        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'role' => $request->role,
            'jenis_kelamin' => $request->jenis_kelamin,
            'is_active' => $request->is_active,
        ]);

        HistoryLog::create([
            'model_type'  => User::class,
            'model_id'    => $user->id_user,
            'user_id'     => Auth::id(),
            'action'      => 'update',
            'description' => 'Data user diperbarui: ' . $user->username,
            'status'      => 'success',
        ]);

        return redirect()->route('management-user.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting self
            if ($user->id_user === auth()->id()) {
                return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
            }

            $deletedUsername = $user->username;
            $deletedUserId   = $user->id_user;
            $user->delete();

            HistoryLog::create([
                'model_type'  => User::class,
                'model_id'    => $deletedUserId,
                'user_id'     => Auth::id(),
                'action'      => 'delete',
                'description' => 'User dihapus: ' . $deletedUsername,
                'status'      => 'success',
            ]);

            return redirect()->route('management-user.index')
                        ->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the reset password form.
     */
    public function resetPasswordForm(string $id)
    {
        $user = User::findOrFail($id);
        return view('management-user.reset-password', compact('user'));
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return view('management-user.reset-success', compact('user'));
    }
}
