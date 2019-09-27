<?php

use App\Entities\Permission;
use App\Entities\Role;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     *
     * @throws
     */
    public function run()
    {
        $this->disableForeignKeys();

        // drop current datas
        foreach (Role::all() as $role) {
            $role->syncPermissions([]);
            $role->delete();
        }
        foreach (Permission::all() as $permission) {
            $permission->delete();
        }

        // Create Roles
        $superadmin = Role::create(['name' => config('access.users.admin_role'), 'is_core' => true]);
        $user = Role::create(['name' => config('access.users.default_role'), 'is_core' => true]);

        $manager = Role::create(['name' => 'manager']);
        $supervisor = Role::create(['name' => 'supervisor']);
        $executive = Role::create(['name' => 'executive']);

        // Create Permissions
        $core_permissions = [
            'access-backend' => 'Access Backend',
            'access-module'  => [
                'label'    => 'Modules',
                'children' => [
                    /*
                    'access-socso-generator' => [
                        'label' => 'Access SOCSO Generator module',
                        'children' => [
                            'read-socso-generator' => 'Read Socso Generator',
                            'create-socso-generator' => 'Create/Update Socso Generator',
                            'delete-socso-generator' => 'Delete Socso Generator',
                        ]
                    ],
                    'access-leave' => [
                        'label' => 'Access Leave module',
                        'children' => [
                            'read-leave' => 'Read Leave',
                            'create-leave' => 'Create/Update leave',
                            'delete-leave' => 'Delete leave',
                        ]
                    ]
                    */
                ],
            ],
            'access-system' => [
                'label'    => 'Access System',
                'children' => [
                    // access managements
                    'access-access' => [
                        'label'    => 'Access Management',
                        'children' => [
                            'read-user'   => 'Read User',
                            'create-user' => 'Create/Update User',
                            'delete-user' => 'Delete User',

                            'read-role'   => 'Read Role',
                            'create-role' => 'Create/Update Role',
                            'delete-role' => 'Delete Role',
                        ],
                    ],
                    'access-setting' => [
                        'label'    => 'Access Settings',
                        'children' => [
                        ],
                    ],
                ],
            ],
        ];

        // seed permission from array
        $traversePermissions = function ($permissions, $parent = null) use (&$traversePermissions) {
            foreach ($permissions as $permission => $value) {
                $children = null;
                $_this = null;

                if (is_array($value)) {
                    $children = $value['children'];
                    $label = $value['label'];
                } else {
                    $children = null;
                    $label = $value;
                }

                // system permissions
                $system_permissions = [
                    'access-backend',
                    'access-module',
                    'access-system', 'access-access',
                    'read-user', 'create-user', 'delete-user',
                    'read-role', 'create-role', 'delete-role',
                    'access-setting',
                ];

                $attributes = [
                    'name'    => $permission,
                    'label'   => $label,
                    'is_core' => true,
                    //'is_system' => in_array($permission, $system_permissions)
                ];

                if ($parent) {
                    $_this = $parent->children()->create($attributes);
                } else {
                    $_this = Permission::create($attributes);
                }

                if ($children) {
                    $traversePermissions($children, $_this);
                }
            }
        };
        $traversePermissions($core_permissions);

        // ALWAYS GIVE ADMIN ROLE ALL PERMISSIONS
        // no longer require as  superadmin role will be checked in PermissionMiddleware
        $superadmin->syncPermissions(Permission::all());

        // Assign Permissions to other Roles
        $manager->givePermissionTo('access-backend');
        $supervisor->givePermissionTo('access-backend');
        $executive->givePermissionTo('access-backend');

        //$user->givePermissionTo('access-backend');

        $this->enableForeignKeys();
    }
}
