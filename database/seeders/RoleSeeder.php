<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (array_keys(User::ROLES) as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        User::query()->each(function (User $user): void {
            $user->syncRoles([$user->role ?: 'murid']);
        });
    }
}
