<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i= 1; $i <= 100 ; $i++){
            Article::create([
                'name'      => 'Admin Toko '. $i,
                
            ]);

        }
    }
}
