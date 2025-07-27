<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HelpCategory;
use App\Models\HelpSubcategory;
use App\Models\HelpTopic;
use App\Models\User;

class HelpCenterSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $category = HelpCategory::create([
            'name' => 'Get started with PropXPro',
            'description' => 'Everything you need to set up and get your own successful, business-friendly PropXPro!',
            'order' => 1,
            'is_active' => true,
        ]);

        // Create subcategories
        $subcategory = HelpSubcategory::create([
            'category_id' => $category->id,
            'name' => 'Plans and pricing',
            'description' => 'Learn about PropXPro plans and pricing.',
            'order' => 1,
            'is_active' => true,
        ]);

        // Create a topic
        $user = User::first();
        HelpTopic::create([
            'subcategory_id' => $subcategory->id,
            'title' => 'How much does PropXPro cost?',
            'slug' => 'how-much-does-propxpro-cost',
            'content' => '<p>PropXPro offers several plans to fit your needs. Contact us for more details.</p>',
            'order' => 1,
            'is_active' => true,
            'created_by' => $user ? $user->id : 1,
        ]);
    }
}