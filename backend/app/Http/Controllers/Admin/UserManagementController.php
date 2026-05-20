<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display the user management page with filtering and sorting.
     */
    public function index(Request $request)
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalOperators = User::where('role', 'operator')->count();

        $query = User::query();

        // Filter by role
        $roleFilter = $request->get('role', 'all');
        if ($roleFilter !== 'all' && in_array($roleFilter, ['admin', 'operator', 'user'])) {
            $query->where('role', $roleFilter);
        }

        // Search
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'terlama':
                $query->orderBy('created_at', 'asc');
                break;
            case 'nama_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('name', 'desc');
                break;
            default: // 'terbaru'
                $query->orderBy('created_at', 'desc');
                break;
        }

        $users = $query->paginate(10)->appends($request->query());

        return view('admin.users.index', compact(
            'totalUsers', 'totalAdmins', 'totalOperators', 'users',
            'roleFilter', 'sort', 'search'
        ));
    }

    /**
     * Show the add operator page — displays list of users with role 'user'
     * so admin can promote them to 'operator'.
     */
    public function add(Request $request)
    {
        $query = User::where('role', 'user');

        // Search by name or email
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('admin.users.add', compact('users', 'search'));
    }

    /**
     * Promote a user (role: user) to operator.
     */
    public function promote(User $user)
    {
        // Only allow promoting users with role 'user'
        if ($user->role !== 'user') {
            return redirect()->route('admin.users.add')
                ->with('error', 'Hanya akun dengan role "User" yang dapat dipromosikan menjadi Operator.');
        }

        $user->update(['role' => 'operator']);

        return redirect()->route('admin.users.add')
            ->with('success', "Akun \"{$user->name}\" berhasil dipromosikan menjadi Operator.");
    }

    /**
     * Show user detail page for admin to view info and change role.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Update user role from the detail page.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'string', 'in:user,operator'],
        ]);

        // Prevent changing admin role
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.show', $user)
                ->with('error', 'Role Super Admin tidak dapat diubah.');
        }

        $oldRole = $user->role;
        $newRole = $request->role;

        if ($oldRole === $newRole) {
            return redirect()->route('admin.users.show', $user)
                ->with('info', 'Role tidak berubah.');
        }

        $user->update(['role' => $newRole]);

        $roleLabels = ['user' => 'User', 'operator' => 'Operator'];

        return redirect()->route('admin.users.show', $user)
            ->with('success', "Role \"{$user->name}\" berhasil diubah dari {$roleLabels[$oldRole]} menjadi {$roleLabels[$newRole]}.");
    }
}
