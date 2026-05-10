<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo admin — role Spatie sẽ được gán ở RoleSeeder
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('Admin@2025'),
                'role'     => 1,
            ]
        );

        // Gán role ngay nếu Spatie đã sẵn sàng
        if (class_exists(\Spatie\Permission\Models\Role::class)
            && \Spatie\Permission\Models\Role::where('name', 'admin')->exists()
            && ! $admin->hasRole('admin')
        ) {
            $admin->assignRole('admin');
        }

        // Tạo user demo
        $user = User::firstOrCreate(
            ['username' => 'user'],
            [
                'name'     => 'Demo User',
                'password' => Hash::make('User@2025'),
                'role'     => 0,
            ]
        );

        if (class_exists(\Spatie\Permission\Models\Role::class)
            && \Spatie\Permission\Models\Role::where('name', 'customer')->exists()
            && ! $user->hasRole('customer')
        ) {
            $user->assignRole('customer');
        }
    }
}
