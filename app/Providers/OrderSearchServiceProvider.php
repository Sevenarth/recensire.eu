<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class OrderSearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('orderable', function($arguments) {
          $arguments = explode(",", $arguments);
          $field = substr(trim($arguments[0]), 1, -1);
          $title = substr(trim($arguments[1]), 1, -1);

          return '<?php
          $orderBy = Request::get("orderBy");
          $sort = Request::get("sort");
          if($orderBy == "'.$field.'") {
            if(empty($sort))
              echo "<a href=\"".Request::fullUrlWithQuery(["orderBy" => "'.$field.'","sort"=>"desc"])."\">'.$title.' <i class=\"fa fa-fw fa-sort-amount-down\"></i></a>";
            else
              echo "<a href=\"".Request::fullUrlWithQuery(["orderBy" => null,"sort"=>null])."\">'.$title.' <i class=\"fa fa-fw fa-sort-amount-up\"></i></a>";
          } else
            echo "<a href=\"".Request::fullUrlWithQuery(["orderBy" => "'.$field.'","sort"=>null])."\">'.$title.'</a>";
          ?>';
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
