<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_admin = User::query()->where("email", "admin@gmail.com")->first();
        $user_editor = User::query()->where("email", "editor@gmail.com")->first();

        $role_admin = Role::query()->where("slug", "admin")->first();
        $role_editor = Role::query()->where("slug", "editor")->first();

        UserRole::query()->create([
            'user_id' => $user_admin->id,
            'role_id' => $role_admin->id
        ]);

        UserRole::query()->create([
            'user_id' => $user_editor->id,
            'role_id' => $role_editor->id
        ]);
    }
}
