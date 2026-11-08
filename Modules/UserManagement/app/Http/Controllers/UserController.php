<?php

namespace Modules\UserManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\UserManagement\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $users = User::query()
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
            }))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('usermanagement::index', compact('users', 'q'));
    }

    public function create()
    {
        return view('usermanagement::form', ['user' => new User(['role' => 'user'])]);
    }

    public function store(UserRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('usermanagement::form', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        // Admin tidak boleh menurunkan role akunnya sendiri
        if ($user->id === auth()->id() && $data['role'] !== 'admin') {
            return back()
                ->withInput()
                ->withErrors(['role' => 'Anda tidak dapat menurunkan role akun Anda sendiri.']);
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diubah.');
    }

    public function destroy(User $user)
    {
        // Admin tidak boleh menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
