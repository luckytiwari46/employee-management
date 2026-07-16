<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Show Admin Dashboard — lists all users except admin.
     */
    public function dashboard()
    {
        // ✅ Fetch all users with related details except admins
        $users = User::with(['qualifications', 'addresses', 'experiences'])
                    ->where('is_admin', 0)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admindashboard', compact('users'));
    }

    /**
     * View details of a specific user (with relations)
     */
    public function viewUserDetails($id)
    {
        // ✅ Load all related models
        $user = User::with(['addresses', 'qualifications', 'experiences'])->findOrFail($id);
        return view('admin.user_details', compact('user'));
    }

    /**
     * Delete a user (Admin only)
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // ✅ Prevent deleting admin account
        if ($user->is_admin == 1) {
            return redirect()->back()->with('error', 'Cannot delete admin account.');
        }

        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }
}
