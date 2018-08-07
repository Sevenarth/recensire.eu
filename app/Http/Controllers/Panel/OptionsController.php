<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Option;

class OptionsController extends Controller
{
    public function index()
    {
        $options = [];
        $query = Option::all();
        foreach($query as $option)
            $options[$option->key] = $option->value;
        
        return view("panel/options", ['options' => $options]);
    }

    public function update()
    {
        Option::set('header-data', request()->input('header-data'));
        Option::set('footer-data', request()->input('footer-data'));

        return redirect()->action('Panel\OptionsController@index')->with('status', 'Opzioni aggiornate con successo!');
    }
}
