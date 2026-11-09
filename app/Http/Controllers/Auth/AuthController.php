<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = $this->authService->attemptLogin($request->email, $request->password);

        if ($user) {
            $request->session()->regenerate();
            AuditTrailService::log('USER_LOGIN', 'User', $user->id, null, ['email' => $user->email], $user->id);
            return redirect()->intended(route('dashboard'))->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return back()->withErrors(['email' => 'Kredensial yang dimasukkan tidak cocok dengan catatan kami.'])->withInput();
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone_number' => 'nullable|string|max:30',
            'address' => 'nullable|string',
        ]);

        $user = $this->authService->registerTourist($request->all());
        Auth::login($user);

        AuditTrailService::log('USER_REGISTER', 'User', $user->id, null, ['email' => $user->email], $user->id);

        return redirect()->route('dashboard')->with('success', 'Pendaftaran akun berhasil! Selamat datang di ekosistem Jumiang Smart Tourism.');
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        AuditTrailService::log('USER_LOGOUT', 'User', $userId, null, null, $userId);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar sistem.');
    }
}
