<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'label' => 'Home',
                'order' => 1,
                'children' => [
                    [
                        'label' => 'Dashboard',
                        'icon' => 'pi pi-fw pi-home',
                        'to' => '/dashboard',
                        'order' => 1,
                    ]
                ]
            ],
            [
                'label' => 'Users',
                'order' => 2,
                'children' => [
                    [
                        'label' => 'Users',
                        'icon' => 'pi pi-fw pi-users',
                        'to' => '/admin/user',
                        'order' => 1
                    ],
                    [
                        'label' => 'Roles',
                        'icon' => 'pi pi-fw pi-user-edit',
                        'to' => '/admin/user/role',
                        'order' => 2
                    ],
                    [
                        'label' => 'Sessions',
                        'icon' => 'pi pi-fw pi-history',
                        'to' => '/admin/user/session',
                        'order' => 3
                    ]
                ]
            ],
        ];
        $this->seedMenu($menus);
    }

    private function seedMenu(array $menus, $parentId = null)
    {
        foreach ($menus as $menuData) {
            $children = $menuData['children'] ?? [];
            unset($menuData['children']);

            $menu = Menu::query()->create(array_merge($menuData, ['parent_id' => $parentId]));

            if (!empty($children)) {
                $this->seedMenu($children, $menu->id);
            }
        }
    }
}
