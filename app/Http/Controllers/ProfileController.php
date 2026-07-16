<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Address;
use App\Models\Qualification;
use App\Models\Experience;

class ProfileController extends Controller
{
    /**
     * Show User Dashboard
     */
    public function userDashboard()
    {
        $user = Auth::user();
        $user->load(['addresses', 'qualifications', 'experiences']);
        return view('userdashboard', compact('user'));
    }

    /**
     * Show Profile Page
     */
    public function showProfile()
    {
        $user = Auth::user();
        $user->load(['addresses', 'qualifications', 'experiences']);
        return view('profile', compact('user'));
    }

    /**
     * Update Profile (AJAX)
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0',
            'dob' => 'nullable|date',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update base info
        $user->update([
            'full_name' => $request->full_name,
            'age' => $request->age,
            'dob' => $request->dob,
        ]);

        // Update profile image
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::exists('public/' . $user->profile_picture)) {
                Storage::delete('public/' . $user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->update(['profile_picture' => $path]);
        }

        // Update addresses
        $this->updateAddress($user, 'permanent', [
            'address_line1' => $request->permanent_address_line1,
            'city' => $request->permanent_city,
            'state' => $request->permanent_state,
        ]);

        $this->updateAddress($user, 'current', [
            'address_line1' => $request->current_address_line1,
            'city' => $request->current_city,
            'state' => $request->current_state,
        ]);

        // Refresh qualifications
        $user->qualifications()->delete();
        if (is_array($request->qualifications)) {
            foreach ($request->qualifications as $q) {
                if (!empty($q['degree'])) {
                    $user->qualifications()->create([
                        'degree' => $q['degree'],
                        'institute' => $q['institute'] ?? '',
                        'year' => $q['year'] ?? '',
                    ]);
                }
            }
        }

        // Refresh experiences
        $user->experiences()->delete();
        if (is_array($request->experiences)) {
            foreach ($request->experiences as $exp) {
                if (!empty($exp['company_name'])) {
                    $user->experiences()->create([
                        'company_name' => $exp['company_name'],
                        'role' => $exp['role'] ?? '',
                        'years' => $exp['years'] ?? '',
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Profile updated successfully!']);
    }

    /**
     * Helper - Update or Create Address
     */
    private function updateAddress($user, $type, $data)
    {
        if (empty($data['address_line1']) && empty($data['city'])) {
            return;
        }

        Address::updateOrCreate(
            ['user_id' => $user->id, 'type' => $type],
            [
                'address_line1' => $data['address_line1'] ?? '',
                'city' => $data['city'] ?? '',
                'state' => $data['state'] ?? '',
                'type' => $type,
            ]
        );
    }
}
