<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Qualification;
use App\Models\Experience;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Show Signup Form
     */
    public function showSignupForm()
    {
        $states = ['Uttar Pradesh', 'Maharashtra', 'Delhi', 'Bihar', 'Karnataka'];
        return view('signup', compact('states'));
    }

    /**
     * Handle Signup Form Submission
     */
    public function signupSubmit(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/', // must contain letters and numbers
                'confirmed',
            ],
            'age' => 'required|integer|min:18|max:100',
            'dob' => 'required|date',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Address validation
            'permanent_address_line1' => 'required|string|max:255',
            'permanent_city' => 'required|string|max:255',
            'permanent_state' => 'required|string|max:255',
            'current_address_line1' => 'required|string|max:255',
            'current_city' => 'required|string|max:255',
            'current_state' => 'required|string|max:255',
        ], [
            'password.regex' => 'Password must contain at least one letter and one number.',
            'password.confirmed' => 'Passwords do not match.',
            'email.unique' => 'This email is already registered.',
            'age.required' => 'Please enter your age.',
        ]);

        DB::beginTransaction(); // ✅ Start transaction

        try {
            // Handle profile picture upload safely
            $path = null;
            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('profiles', 'public');
            }

            // ✅ Create user
            $user = User::create([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'age' => $validated['age'],
                'dob' => $request->dob,
                'profile_picture' => $path,
                'role' => 'user',
                'is_admin' => 0,
            ]);

            // ✅ Save Qualifications
            if ($request->has('qualifications') && is_array($request->qualifications)) {
                foreach ($request->qualifications as $q) {
                    if (!empty($q['degree'])) {
                        Qualification::create([
                            'user_id' => $user->id,
                            'degree' => $q['degree'] ?? null,
                            'institute' => $q['institute'] ?? null,
                            'year' => $q['year'] ?? null,
                        ]);
                    }
                }
            }

            // ✅ Save Experiences
            if ($request->has('experiences') && is_array($request->experiences)) {
                foreach ($request->experiences as $exp) {
                    if (!empty($exp['company_name'])) {
                        Experience::create([
                            'user_id' => $user->id,
                            'company_name' => $exp['company_name'] ?? null,
                            'role' => $exp['role'] ?? null,
                            'years' => $exp['years'] ?? null,
                        ]);
                    }
                }
            }

            // ✅ Save Addresses
            foreach (['permanent', 'current'] as $type) {
                Address::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'address_line1' => $request[$type . '_address_line1'] ?? null,
                    'address_line2' => $request[$type . '_address_line2'] ?? null,
                    'city' => $request[$type . '_city'] ?? null,
                    'state' => $request[$type . '_state'] ?? null,
                ]);
            }

            DB::commit(); // ✅ All good, save everything

            return redirect()->route('login')->with('success', 'Signup successful! Please log in.');
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Something went wrong, undo changes
            return back()->with('error', 'Signup failed: ' . $e->getMessage());
        }
    }

    /**
     * Show Login Form
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle Login Submission
     */
    public function loginSubmit(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin' || Auth::user()->is_admin == 1) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome Admin!');
            } else {
                return redirect()->route('user.dashboard')->with('success', 'Welcome Back!');
            }
        }

        return back()->with('error', 'Invalid email or password');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have logged out successfully.');
    }
}
