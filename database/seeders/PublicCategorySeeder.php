<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PublicCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Berita', 'Artikel', 'Kajian'];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat)],
                ['name' => $cat]
            );
        }
    }
}
