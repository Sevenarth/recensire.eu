<?php

use Illuminate\Database\Seeder;
use App\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        for($i = 0; $i < 5; $i++) {
          $cat = new Category;
          $cat->title = "cat-".($i+1);
          $cat->save();

          for($j = 0; $j < 2; $j++) {
            $subcat = new Category;
            $subcat->title = "cat-".($i+1)."-".($j+1);
            $subcat->parent_id = $cat->id;
            $subcat->save();
          }
        }
    }
}
