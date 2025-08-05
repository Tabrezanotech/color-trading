<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class RegisterController extends Controller
{
   public function showRegisterForm($code = null)
{
    if (!$code) {
        $code = request()->query('code');
    }
    $admin = User::where('invitation_code', $code)->first();
    return view('auth.register', [
        'invitationCode' => $admin ? $admin->invitation_code : ''
    ]);
}

public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed',
        'role' => 'required|in:admin,user',
        'admin_secret_key' => 'nullable|string',
        'invitation_code' => 'nullable|string',
    ]);

    $invitationCode = null;
    $invitedBy = null;

    if ($request->role === 'admin') {
        // Allow only one admin
        $existingAdmin = User::where('role', 'admin')->first();
        if ($existingAdmin) {
            return back()->withErrors(['role' => 'An admin already exists. Only one admin is allowed.']);
        }

        // Validate admin secret key
        $expectedKey = env('ADMIN_SECRET_KEY');
        if ($request->admin_secret_key !== $expectedKey) {
            return back()->withErrors(['admin_secret_key' => 'Invalid Admin Secret Key.']);
        }

        // Generate admin invitation code
        $invitationCode = uniqid('jyoti_');
    } elseif ($request->role === 'user') {
        // User must enter a valid invitation code
        if (empty($request->invitation_code)) {
            return back()->withErrors(['invitation_code' => 'Invitation code is required for users.']);
        }

        $inviter = User::where('invitation_code', $request->invitation_code)->first();
       if (!$inviter || !in_array($inviter->role, ['admin', 'user'])) {
    return back()->withErrors(['invitation_code' => 'Invalid or unauthorized invitation code.']);
}


        $invitedBy = $inviter->id;
        $invitationCode = uniqid('user_');
    }

    // Generate OTP
    $otp = rand(100000, 999999);
    $otpExpiresAt = Carbon::now()->addMinutes(10);

    // Store pending user in session
    session([
        'pending_user' => [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
            'invitation_code' => $invitationCode,
            'invited_by' => $invitedBy,
            'otp' => $otp,
            'otp_expires_at' => $otpExpiresAt,
        ]
    ]);

    // Send OTP email
    Mail::raw("Your OTP is: $otp", function ($message) use ($request) {
        $message->to($request->email)->subject('Your Registration OTP');
    });

    return redirect()->route('otp.verify.form')->with('success', 'OTP sent to your email.');
}


    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

  public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|numeric',
    ]);

    $pendingUser = session('pending_user');

    if (!$pendingUser) {
        return redirect()->route('register')->withErrors(['message' => 'Session expired. Please register again.']);
    }

    if ($pendingUser['otp'] != $request->otp || now()->gt($pendingUser['otp_expires_at'])) {
        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    // Create the user
    $user = User::create([
        'name' => $pendingUser['name'],
        'email' => $pendingUser['email'],
        'password' => $pendingUser['password'],
        'role' => $pendingUser['role'],
        'invitation_code' => $pendingUser['invitation_code'] ?? null,
        'invited_by' => $pendingUser['invited_by'] ?? null,
    ]);

    // Send registration confirmation email
    Mail::raw("Hi {$user->name}, your registration is successful. You can now login.", function ($message) use ($user) {
        $message->to($user->email)->subject('Registration Successful');
    });

    // Clear the session
    session()->forget('pending_user');

    // Redirect based on role
    if ($user->role === 'admin') {
        Auth::login($user);
        return redirect()->route('admin.dashboard')->with('success', 'Registration successful. Welcome!');
    }

    return redirect()->route('login')->with('success', 'Registration successful. Please login.');
}

}
