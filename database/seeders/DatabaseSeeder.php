<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(\App\Modules\System\database\seeders\SystemSeeder::class);
        $this->call(\App\Modules\CMS\database\seeders\CMSSeeder::class);
        $this->call(\App\Modules\Gallery\database\seeders\GallerySeeder::class);
        $this->call(\App\Modules\Contact\database\seeders\ContactSeeder::class);
        $this->call(\App\Modules\PPDB\database\seeders\PPDBSeeder::class);
        $this->call(\App\Modules\Theme\database\seeders\ThemeSeeder::class);
    }
}
