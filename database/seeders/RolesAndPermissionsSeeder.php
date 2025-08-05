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

        // Blog management permissions
        $blogPermissions = [
            'view_blogs' => 'Can view blogs',
            'create_blog' => 'Can create blogs',
            'edit_blog' => 'Can edit blogs',
            'delete_blog' => 'Can delete blogs',
            'toggle_blog_status' => 'Can activate/deactivate blogs',
            'manage_blog_faqs' => 'Can manage blog FAQs',
        ];

        // Newsletter subscriber management permissions
        $newsletterPermissions = [
            'view_newsletter_subscribers' => 'Can view newsletter subscribers',
            'remove_newsletter_subscriber' => 'Can remove newsletter subscribers',
        ];

        // Request demo management permissions
        $requestDemoPermissions = [
            'view_request_demos' => 'Can view all demo requests',
        ];

        // Help Center permissions
        $helpCenterPermissions = [
            'view_help_categories' => 'Can view help categories',
            'create_help_category' => 'Can create help categories',
            'edit_help_category' => 'Can edit help categories',
            'delete_help_category' => 'Can delete help categories',
            'view_help_subcategories' => 'Can view help subcategories',
            'create_help_subcategory' => 'Can create help subcategories',
            'edit_help_subcategory' => 'Can edit help subcategories',
            'delete_help_subcategory' => 'Can delete help subcategories',
            'view_help_topics' => 'Can view help topics',
            'create_help_topic' => 'Can create help topics',
            'edit_help_topic' => 'Can edit help topics',
            'delete_help_topic' => 'Can delete help topics',
        ];

        // Webinar management permissions
        $webinarPermissions = [
            'manage_webinars' => 'Can manage webinars (events and videos)',
        ];

        $allPermissions = array_merge(
            $adminManagementPermissions,
            $integrationPermissions,
            $featurePermissions,
            $planPermissions,
            $legalDocumentPermissions,
            $blogPermissions,
            $newsletterPermissions,
            $requestDemoPermissions,
            $helpCenterPermissions,
            $webinarPermissions
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
        // Assign manage_webinars to admin role as well
        $adminRole->givePermissionTo('manage_webinars');

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
