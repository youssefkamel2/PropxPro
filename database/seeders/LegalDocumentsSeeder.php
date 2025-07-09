<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LegalDocument;

class LegalDocumentsSeeder extends Seeder
{
    public function run()
    {
        LegalDocument::create([
            'type' => 'privacy_policy',
            'content' => '<h2>Privacy Policy</h2><p>Initial privacy policy content.</p>',
            'version' => 1,
            'status' => 'published',
        ]);

        LegalDocument::create([
            'type' => 'terms_of_service',
            'content' => '<h2>Terms of Service</h2><p>Initial terms of service content.</p>',
            'version' => 1,
            'status' => 'published',
        ]);
    }
} 