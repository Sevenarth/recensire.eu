<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      Blade::directive('openForm', function ($arguments) {
          $arguments = explode(",", $arguments);
          $route = substr(trim($arguments[0]), 1, -1);
          $method = substr(trim($arguments[1]), 1, -1);
          $arg = null;
          foreach(array_slice($arguments, 2) as $karg) {
            $karg = explode("=", $karg, 2);
            $key = trim($karg[0]);
            $$key = substr(trim($karg[1]), 1, -1);
          }

          if(!empty($arg))
            return '<form action="<?php echo !empty($'.$arg.') ? route(\''.$route.'\', $'.$arg.') : route(\''.$route.'\') ?>" method="post">
          <?php echo method_field(\''.$method.'\'). PHP_EOL . csrf_field(); ?>';
          else
            return '<form action="<?php echo route(\''.$route.'\') ?>" method="post">
          <?php echo method_field(\''.$method.'\'). PHP_EOL . csrf_field(); ?>';
      });

      Blade::directive('formTextfield', function ($arguments) {
          $arguments = explode(",", $arguments);
          $name = substr(trim($arguments[0]), 1, -1);
          $title = substr(trim($arguments[1]), 1, -1);
          $placeholder = "";
          $required = true;
          $value = "";
          $description = "";
          $class = "";
          $type = "text";
          $append = "";
          $prepend = "";
          $plaintext = "false";
          $editMode = false;
          foreach(array_slice($arguments, 2) as $karg) {
            $karg = explode("=", $karg, 2);
            $key = trim($karg[0]);
            $$key = substr(trim($karg[1]), 1, -1);
          }

          return '<fieldset class="form-group">
          <label for="'.$name.'"><?php echo '.$plaintext.' ? "<b>":""?>'.$title.'<?php echo '.$plaintext.' ? "<b>":""?><?php echo ('.$required.') ? "" : " <small class=\"text-muted\">(opzionale)</small>"; ?></label>
          <div class="input-group">
          <?php if(!empty("'.$prepend.'")) { ?>
        <div class="input-group-prepend">
          <div class="input-group-text">'.$prepend.'</div>
        </div><?php } ?>
          <input type="'.$type.'" <?php echo ('.$plaintext.' ? "readonly" : "") ?> value="<?php if($old = old("'.$name.'")) echo $old; '. ($editMode ? 'elseif(isset($'.$editMode.')){ echo $'.$editMode.'->'.$name.'; }' : '').'else{ ?>'.$value.'<?php } ?>" class="<?php echo (!empty("'.$class.'")) ? "'.$class.' " : "form-control" . ('.$plaintext.' ? "-plaintext" : ""); ?><?php echo ($errors->has("'.$name.'")) ? " is-invalid" : ""; ?>" name="'.$name.'" id="'.$name.'" placeholder="'.$placeholder.'"<?php echo ('.$required.') ? \' required\' : \'\'; ?>>
          <?php if(!empty("'.$append.'")) { ?>
                  <div class="input-group-append">
                    <div class="input-group-text">'.$append.'</div>
                  </div><?php } ?>
                  <?php if($errors->has("'.$name.'")) { ?>
                  <div class="invalid-feedback">
                    <?php foreach($errors->get("'.$name.'") as $error) echo $error . "<br>"; ?>
                  </div>
                  <?php } ?>
          </div>
          <?php if(!empty("'.$description.'")) { ?>
            <small class="text-block text-muted"><?php echo "'.$description.'"; ?></small><?php } ?>
          </fieldset>';
      });

      Blade::directive('closeForm', function () {
          return '</form>';
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
