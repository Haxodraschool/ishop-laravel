<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Tạo Permissions ──────────────────────────────
        $permissions = [
            'manage products',
            'manage categories',
            'manage orders',
            'manage users',
            'view reports',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ── Tạo Roles ────────────────────────────────────
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);

        // Admin có tất cả quyền
        $adminRole->syncPermissions($permissions);

        // Customer không có quyền admin
        $customerRole->syncPermissions([]);

        // ── Gán Role cho Users hiện có ───────────────────
        // Gán role admin cho user có role == 1
        User::where('role', 1)->each(function ($user) use ($adminRole) {
            if (! $user->hasRole('admin')) {
                $user->assignRole($adminRole);
            }
        });

        // Gán role customer cho user có role != 1
        User::where('role', '!=', 1)->each(function ($user) use ($customerRole) {
            if (! $user->hasRole('customer')) {
                $user->assignRole($customerRole);
            }
        });

        $this->command->info('✅ Roles & Permissions đã được tạo thành công!');
        $this->command->info('   - admin: ' . implode(', ', $permissions));
        $this->command->info('   - customer: (không có quyền admin)');
    }
}
