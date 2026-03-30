<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Auth\LoginRequest as AuthLoginRequest;
use App\Models\FcmToken;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(AuthLoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        Log::info('Admin login attempt detected', ['email' => $credentials['email']]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $admin = Auth::guard('admin')->user();

            Log::info('Admin login successful', ['admin_id' => $admin->id]);

            activity()
                ->causedBy($admin)
                ->log('Admin logged in');

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back!');
        }

        Log::warning('Admin login failed', ['email' => $credentials['email']]);

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        if ($admin) {
            Log::info('Admin logging out', ['admin_id' => $admin->id]);

            activity()
                ->causedBy($admin)
                ->log('Admin logged out');
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }

    public function updateFcm(Request $request)
    {
        Log::info('FCM token save request received', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'authenticated' => Auth::check(),
        ]);

        $request->validate([
            'fcm' => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        Log::info('Updating FCM token', [
            'user_id' => $user?->id,
            'authenticated' => (bool) $user,
            'device_name' => $request->device_name,
            'token_prefix' => substr($request->fcm, 0, 18),
        ]);

        try {
            DB::transaction(function () use ($user, $request) {
                FcmToken::updateOrCreate(
                    ['token' => $request->fcm],
                    [
                        'user_id' => $user?->id,
                        'device_name' => $request->device_name,
                        'last_used_at' => now(),
                    ]
                );
            });
        } catch (\Throwable $e) {
            Log::error('FCM token save failed', [
                'user_id' => $user?->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Could not save FCM token.',
            ], 500);
        }

        Log::info('FCM token updated successfully', ['user_id' => $user?->id]);

        return response()->json([
            'success' => true,
            'message' => 'FCM token updated successfully.',
            'data' => [
                'user_id' => $user?->id,
                'fcm' => $request->fcm,
            ]
        ]);
    }
}
