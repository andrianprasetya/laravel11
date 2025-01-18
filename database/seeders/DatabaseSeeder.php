<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(MenuSeeder::class);
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'Editor User',
            'email' => 'editor@gmail.com',
        ]);

        Role::factory()->create([
            'name' => 'Admin',
            'slug' => Str::slug("Admin")
        ]);

        Role::factory()->create([
            'name' => 'Editor',
            'slug' => Str::slug("Editor")
        ]);

        $this->call(UserRoleSeeder::class);
    }
}
