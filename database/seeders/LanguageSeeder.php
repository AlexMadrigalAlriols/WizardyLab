<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['name' => 'English', 'iso_code' => 'en'],
            ['name' => 'Spanish', 'iso_code' => 'es'],
            ['name' => 'French', 'iso_code' => 'fr'],
            ['name' => 'German', 'iso_code' => 'de'],
            ['name' => 'Italian', 'iso_code' => 'it'],
            ['name' => 'Portuguese', 'iso_code' => 'pt'],
            ['name' => 'Russian', 'iso_code' => 'ru'],
            ['name' => 'Chinese', 'iso_code' => 'zh'],
            ['name' => 'Japanese', 'iso_code' => 'ja'],
            ['name' => 'Korean', 'iso_code' => 'ko'],
            ['name' => 'Arabic', 'iso_code' => 'ar'],
            ['name' => 'Hindi', 'iso_code' => 'hi'],
            ['name' => 'Bengali', 'iso_code' => 'bn'],
            ['name' => 'Urdu', 'iso_code' => 'ur'],
            ['name' => 'Turkish', 'iso_code' => 'tr'],
            ['name' => 'Vietnamese', 'iso_code' => 'vi'],
            ['name' => 'Thai', 'iso_code' => 'th'],
            ['name' => 'Dutch', 'iso_code' => 'nl'],
            ['name' => 'Greek', 'iso_code' => 'el'],
            ['name' => 'Swedish', 'iso_code' => 'sv'],
            ['name' => 'Polish', 'iso_code' => 'pl'],
            ['name' => 'Finnish', 'iso_code' => 'fi'],
            ['name' => 'Norwegian', 'iso_code' => 'no'],
            ['name' => 'Danish', 'iso_code' => 'da'],
            ['name' => 'Czech', 'iso_code' => 'cs'],
            ['name' => 'Hungarian', 'iso_code' => 'hu'],
            ['name' => 'Romanian', 'iso_code' => 'ro'],
            ['name' => 'Ukrainian', 'iso_code' => 'uk'],
            ['name' => 'Croatian', 'iso_code' => 'hr']
        ];

        Language::insert($languages);
    }
}
