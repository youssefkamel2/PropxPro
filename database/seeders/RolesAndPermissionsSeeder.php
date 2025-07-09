<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        Schema::disableForeignKeyConstraints();
        
        Permission::query()->delete();
        Role::query()->delete();
        
        // Create admin management permissions
        $adminManagementPermissions = [
            'view_admins' => 'Can view list of admins',
            'create_admin' => 'Can create new admin',
            'edit_admin' => 'Can edit admin details',
            'delete_admin' => 'Can delete admin',
            'activate_admin' => 'Can activate/deactivate admin',
            'manage_admin_permissions' => 'Can manage admin role permissions',
        ];

        // Add integration permissions
        $integrationPermissions = [
            'view_integrations' => 'Can view integrations',
            'create_integration' => 'Can create new integrations',
            'edit_integration' => 'Can edit integration details',
            'delete_integration' => 'Can delete integrations',
            'toggle_integration' => 'Can activate/deactivate integrations',
        ];

        // Add feature permissions
        $featurePermissions = [
            'view_features' => 'Can view features',
            'create_feature' => 'Can create features',
            'edit_feature' => 'Can edit features',
            'delete_feature' => 'Can delete features',
            'toggle_feature_status' => 'Can activate/deactivate features',
        ];

        // Add plan permissions
        $planPermissions = [
            'view_plans' => 'Can view plans',
            'create_plan' => 'Can create plans',
            'edit_plan' => 'Can edit plans',
            'delete_plan' => 'Can delete plans',
            'toggle_plan_status' => 'Can activate/deactivate plans',
        ];

        // Legal document permissions
        $legalDocumentPermissions = [
            'update_privacy_policy' => 'Can update privacy policy',
            'update_terms_of_service' => 'Can update terms of service',
        ];

        $allPermissions = array_merge(
            $adminManagementPermissions,
            $integrationPermissions,
            $featurePermissions,
            $planPermissions,
            $legalDocumentPermissions
        );

        foreach ($allPermissions as $permission => $description) {
            Permission::create([
                'name' => $permission,
                'description' => $description,
                'guard_name' => 'api'
            ]);
        }

        // Create roles
        $superadminRole = Role::create([
            'name' => 'superadmin',
            'guard_name' => 'api',
            'description' => 'Super Administrator with full system access'
        ]);

        $adminRole = Role::create([
            'name' => 'admin',
            'guard_name' => 'api',
            'description' => 'Administrator with limited access based on assigned permissions'
        ]);

        // Assign all permissions to superadmin
        $superadminRole->givePermissionTo(Permission::all());

        // Create default superadmin user if it doesn't exist
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@propxpro.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin123'),
                'status' => 'active'
            ]
        );

        $superadmin->assignRole('superadmin');
        
        Schema::enableForeignKeyConstraints();
    }
}
