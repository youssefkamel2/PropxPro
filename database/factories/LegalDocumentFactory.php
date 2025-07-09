<?php

namespace Database\Factories;

use App\Models\LegalDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class LegalDocumentFactory extends Factory
{
    protected $model = LegalDocument::class;

    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['privacy_policy', 'terms_of_service']),
            'content' => '<h2>' . $this->faker->sentence . '</h2><p>' . $this->faker->paragraph . '</p>',
            'version' => 1,
            'status' => 'published',
        ];
    }
} 