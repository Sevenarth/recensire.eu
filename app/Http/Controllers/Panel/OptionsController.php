<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{Option, Shortcode};

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
        Option::set('front_show_limit', request()->input('front_show_limit'));

        return redirect()->action('Panel\OptionsController@index')->with('status', 'Opzioni aggiornate con successo!');
    }

    public function shortcodes()
    {
        $shortcodes = Shortcode::orderBy('key')->get();
        return view('panel.shortcodes', compact('shortcodes'));
    }

    public function shortcodesUpdate()
    {
        $create = request()->input('create', false);
        $delete = request()->input('delete', false);
        $key = request()->input('key');
        $value = request()->input('value');
        $shortcode = Shortcode::where('key', $key)->first();

        if($delete) {
            if($shortcode) {
                $shortcode->delete();
                return response()->json(['status' => 'Shortcode eliminato con successo!']);
            } else
                return response()->json(['shortcode' => 'Questo shortcode è inesistente'], 404);
        }

        if(!preg_match("/^([0-9a-z\-]+)$/i", $key))
            return response()->json(['shortcode' => 'Inserire uno shortcode valido!'], 400);

        if($create) {
            if($shortcode)
                return response()->json(['shortcode' => 'Questo shortcode esiste già'], 409);

            $shortcode = new Shortcode;
        }
        
        if($shortcode) {
            $shortcode->key = $key;
            $shortcode->value = $value;
            $shortcode->save();

            return response()->json([
                'shortcode' => $shortcode,
                'status' => 'Shortcode ' . ($create ? 'aggiunto' : 'modificato') . ' con successo!'
            ]);
        }

        return response()->json(['shortcode' => 'Questo shortcode è inesistente'], 404);
    }

    public function reports()
    {
        $fields = config('testUnit.reportFields');
        $statuses = [];
        foreach(config('testUnit.englishStatuses') as $key => $value)
            $statuses[] = ['value' => $key, 'display' => $value];

        $reports = json_decode(Option::get('reports'));
        if(!is_array($reports)) $reports = [];
        return view('panel.emailreports', compact('fields', 'statuses', 'reports'));
    }

    public function reportsUpdate()
    {
        Option::set('reports', json_encode(request()->input('reports')));

        return response()->json(['status' => 'Tutti i cambiamenti sono stati salvati con successo!']);
    }
}
