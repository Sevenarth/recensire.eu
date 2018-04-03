<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;

class PanelMenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      Blade::directive('panelNav', function ($noRoundedCorners = false) {
          return '<?php foreach(config("panel.nav") as $routeName => $entry) {
            if(is_array($entry[1])) {
              $current = false;
              foreach($entry[1] as $path)
                if(Request::is($path)) {
                  $current = true;
                  break;
                }
            } else
              $current = Request::is($entry[1]);

            if($current) {
              $active = " '.($noRoundedCorners ? '' : ' rounded-left ').'active";
              $aStart = "";
              $aEnd = "";
            } else {
              $active = "";
              $aStart = "<a href=\"".route("panel.{$routeName}")."\">";
              $aEnd = "</a>";
            }

            echo "{$aStart}<li class=\"list-group-item{$active}\"><i class=\"fas fa-fw {$entry[2]}\"></i> {$entry[0]}</li>{$aEnd}" . PHP_EOL;
          } ?>';
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
