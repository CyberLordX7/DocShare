<?php

namespace Database\Seeders;


use App\Enum\RolesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    private const DEFAULT_PASSWORD = 'password';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guard = 'web';


        foreach (RolesEnum::cases() as $roleEnum) {
            $role = Role::firstOrCreate([
                'name' => $roleEnum->value,
                'guard_name' => $guard,
            ]);

            $permissions = collect($roleEnum->defaultPermissions())
                ->map(fn ($permission) => Permission::firstOrCreate([
                    'name' => $permission->value,
                    'guard_name' => $guard
                ]));

            $role->syncPermissions($permissions);
        }


        $this->createUserWithRole('Guest User', 'guest@docshare.com', RolesEnum::Guest);
        $this->createUserWithRole('Regular User', 'user@docshare.com', RolesEnum::User);
        $this->createUserWithRole('Administrator', 'admin@docshare.com', RolesEnum::Admin);
        $this->createUserWithRole('Super Admin', 'superadmin@docshare.com', RolesEnum::SuperAdmin);
        $this->createUserWithRole('Michael', 'michael@docshare.com', RolesEnum::SuperAdmin);
    }


    protected function createUserWithRole(string $name, string $email, RolesEnum $role): void
    {
        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt(self::DEFAULT_PASSWORD),
            'receive_notifications' => in_array($role, [RolesEnum::Admin, RolesEnum::SuperAdmin])
        ]);

        $user->assignRole($role->value);
    }
}
