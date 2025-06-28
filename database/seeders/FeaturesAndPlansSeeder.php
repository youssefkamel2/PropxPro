<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class FeaturesAndPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Features
        $features = [
            // Regular Features (category = null) - Boolean type
            [
                'key' => 'user_management',
                'name' => 'User Management',
                'type' => 'boolean',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'role_based_access',
                'name' => 'Role-Based Access Control',
                'type' => 'boolean',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'api_access',
                'name' => 'API Access',
                'type' => 'boolean',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'advanced_analytics',
                'name' => 'Advanced Analytics',
                'type' => 'boolean',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'custom_branding',
                'name' => 'Custom Branding',
                'type' => 'boolean',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'priority_support',
                'name' => 'Priority Support',
                'type' => 'boolean',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'data_export',
                'name' => 'Data Export',
                'type' => 'boolean',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'backup_restore',
                'name' => 'Backup & Restore',
                'type' => 'boolean',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'white_label',
                'name' => 'White Label Solution',
                'type' => 'boolean',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'sso_integration',
                'name' => 'SSO Integration',
                'type' => 'boolean',
                'category' => null,
                'is_active' => true,
            ],

            // Regular Features (category = null) - Text type
            [
                'key' => 'storage_limit',
                'name' => 'Storage Limit',
                'type' => 'text',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'user_limit',
                'name' => 'User Limit',
                'type' => 'text',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'api_rate_limit',
                'name' => 'API Rate Limit',
                'type' => 'text',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'retention_period',
                'name' => 'Data Retention Period',
                'type' => 'text',
                'category' => null,
                'is_active' => true,
            ],
            [
                'key' => 'support_response_time',
                'name' => 'Support Response Time',
                'type' => 'text',
                'category' => null,
                'is_active' => true,
            ],

            // Additional Usage Charge Features (category = additional_usage_charge) - Text type only
            [
                'key' => 'additional_storage_price',
                'name' => 'Additional Storage Price per GB',
                'type' => 'text',
                'category' => 'additional_usage_charge',
                'is_active' => true,
            ],
            [
                'key' => 'additional_user_price',
                'name' => 'Additional User Price',
                'type' => 'text',
                'category' => 'additional_usage_charge',
                'is_active' => true,
            ],
            [
                'key' => 'additional_api_call_price',
                'name' => 'Additional API Call Price per 1000',
                'type' => 'text',
                'category' => 'additional_usage_charge',
                'is_active' => true,
            ],
            [
                'key' => 'additional_retention_price',
                'name' => 'Additional Retention Period Price per Month',
                'type' => 'text',
                'category' => 'additional_usage_charge',
                'is_active' => true,
            ],
        ];

        foreach ($features as $featureData) {
            Feature::create($featureData);
        }

        // Create Plans
        $plans = [
            [
                'name' => 'basic',
                'title' => 'Basic Plan',
                'monthly_price' => 29.99,
                'annual_price' => 299.99,
                'annual_savings' => 'Save 17%',
                'is_popular' => false,
                'description' => 'Perfect for small teams getting started',
                'is_active' => true,
            ],
            [
                'name' => 'professional',
                'title' => 'Professional Plan',
                'monthly_price' => 79.99,
                'annual_price' => 799.99,
                'annual_savings' => 'Save 17%',
                'is_popular' => true,
                'description' => 'Ideal for growing businesses',
                'is_active' => true,
            ],
            [
                'name' => 'enterprise',
                'title' => 'Enterprise Plan',
                'monthly_price' => 199.99,
                'annual_price' => 1999.99,
                'annual_savings' => 'Save 17%',
                'is_popular' => false,
                'description' => 'For large organizations with advanced needs',
                'is_active' => true,
            ],
        ];

        foreach ($plans as $planData) {
            Plan::create($planData);
        }

        // Associate features with plans
        $this->associateFeaturesWithPlans();
    }

    private function associateFeaturesWithPlans(): void
    {
        $basicPlan = Plan::where('name', 'basic')->first();
        $professionalPlan = Plan::where('name', 'professional')->first();
        $enterprisePlan = Plan::where('name', 'enterprise')->first();

        // Basic Plan Features
        $basicFeatures = [
            'user_management' => 'true',
            'storage_limit' => '10 GB',
            'user_limit' => '5 users',
            'api_rate_limit' => '1000 requests/hour',
            'retention_period' => '30 days',
            'support_response_time' => '24 hours',
            'additional_storage_price' => '$0.10',
            'additional_user_price' => '$5.00',
            'additional_api_call_price' => '$0.01',
            'additional_retention_price' => '$2.00',
        ];

        $this->attachFeaturesToPlan($basicPlan, $basicFeatures);

        // Professional Plan Features
        $professionalFeatures = [
            'user_management' => 'true',
            'role_based_access' => 'true',
            'api_access' => 'true',
            'data_export' => 'true',
            'storage_limit' => '100 GB',
            'user_limit' => '25 users',
            'api_rate_limit' => '10000 requests/hour',
            'retention_period' => '90 days',
            'support_response_time' => '8 hours',
            'additional_storage_price' => '$0.08',
            'additional_user_price' => '$4.00',
            'additional_api_call_price' => '$0.008',
            'additional_retention_price' => '$1.50',
        ];

        $this->attachFeaturesToPlan($professionalPlan, $professionalFeatures);

        // Enterprise Plan Features
        $enterpriseFeatures = [
            'user_management' => 'true',
            'role_based_access' => 'true',
            'api_access' => 'true',
            'advanced_analytics' => 'true',
            'custom_branding' => 'true',
            'priority_support' => 'true',
            'data_export' => 'true',
            'backup_restore' => 'true',
            'white_label' => 'true',
            'sso_integration' => 'true',
            'storage_limit' => 'Unlimited',
            'user_limit' => 'Unlimited',
            'api_rate_limit' => 'Unlimited',
            'retention_period' => '365 days',
            'support_response_time' => '2 hours',
            'additional_storage_price' => '$0.05',
            'additional_user_price' => '$3.00',
            'additional_api_call_price' => '$0.005',
            'additional_retention_price' => '$1.00',
        ];

        $this->attachFeaturesToPlan($enterprisePlan, $enterpriseFeatures);
    }

    private function attachFeaturesToPlan(Plan $plan, array $features): void
    {
        foreach ($features as $featureKey => $value) {
            $feature = Feature::where('key', $featureKey)->first();
            if ($feature) {
                $plan->features()->attach($feature->id, ['value' => $value]);
            }
        }
    }
} 