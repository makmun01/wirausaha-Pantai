<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function attemptLogin(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->where('is_active', true)->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            return $user;
        }

        return null;
    }

    public function registerTourist(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'] ?? null,
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        $touristRole = Role::firstOrCreate(['name' => 'wisatawan'], [
            'display_name' => 'Wisatawan',
            'description' => 'Pengguna umum pengunjung dan pembeli.',
        ]);

        $user->roles()->attach($touristRole->id);

        UserProfile::create([
            'user_id' => $user->id,
            'address' => $data['address'] ?? null,
            'bio' => 'Wisatawan penikmat wisata bahari Pantai Jumiang',
        ]);

        return $user;
    }
}
