<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tester;
use App\Http\Requests\TesterFormRequest;

class TestersController extends Controller
{
  public function index(Request $request) {
    $orderBy = $request->query('orderBy', null);
    if(!empty($orderBy) && !in_array($orderBy, ['id', 'name', 'email']))
      $orderBy = null;
    $sort = $request->query('sort', 'asc');
    if($sort != "asc" && $sort != "desc")
      $sort = "asc";
    $search = trim($request->query('s', null));

    if(!empty($search)) {
        $testers = Tester::where("id", $search)
          ->orWhere("name", "like", "%".$search."%")
          ->orWhere("email", "like", "%".$search."%")
          ->orWhere("facebook_profiles", '%'.$search.'%')
          ->orWhere("wechat", '%'.$search.'%');

        if(!empty($orderBy))
          $testers = $testers->orderBy($orderBy, $sort)->paginate(15);
        else
          $testers = $testers->paginate(15);
    } else {
        if(!empty($orderBy))
          $testers = Tester::orderBy($orderBy, $sort)->paginate(15);
        else
          $testers = Tester::paginate(15);
    }

    return view("panel/testers/home")->with('testers', $testers);
  }

  public function create(Request $request) {
    $tester = new Tester;
    return view('panel/testers/form', compact('tester'));
  }

  public function put(TesterFormRequest $request) {
    $confirm_fields = [];
    if(Tester::where('name', trim($request->input('name')))->count() > 0)
      $confirm_fields[] = 'nome';
    if(Tester::where('email', trim($request->input('email')))->count() > 0)
      $confirm_fields[] = 'indirizzo email';
    if(Tester::where('wechat', trim($request->input('wechat')))->count() > 0)
      $confirm_fields[] = 'WeChat';
    foreach($request->input('amazon_profiles') as $key => $amz)
      if(Tester::where('amazon_profiles', 'like', '%'.trim($amz).'%')->count() > 0)
        $confirm_fields[] = 'profilo Amazon no. ' . ($key+1);
    foreach($request->input('facebook_profiles') as $key => $fb)
      if(Tester::where('facebook_profiles', 'like', '%'.trim($fb).'%')->count() > 0)
        $confirm_fields[] = 'Facebook ID no. ' . ($key+1);

    if(count($confirm_fields) > 0 && $request->input('confirmation') != "true")
      return redirect()
        ->back()
        ->withInput(array_merge($request->all(), ['confirmation' => 'true', 'fields' => implode(", ", $confirm_fields)]));

    $tester = Tester::create($request->only([
      'name', 'email', 'wechat', 'profile_image', 'amazon_profiles', 'facebook_profiles'
    ]));

    return redirect()
      ->route('panel.testers.home')
      ->with('status', 'Tester aggiunto con successo!');
  }

  public function view(Request $request, Tester $tester) {
    $testUnits = $tester->testUnits()->paginate(15);
    return view('panel/testers/view', compact('tester', 'testUnits'));
  }

  public function edit(Request $request, Tester $tester) {
    return view('panel/testers/form', compact('tester'));
  }

  public function update(TesterFormRequest $request, Tester $tester) {
    $confirm_fields = [];
    if(Tester::where('id', '<>', $tester->id)->where('name', trim($request->input('name')))->count() > 0)
      $confirm_fields[] = 'nome';
    if(Tester::where('id', '<>', $tester->id)->where('email', trim($request->input('email')))->count() > 0)
      $confirm_fields[] = 'indirizzo email';
    if(Tester::where('id', '<>', $tester->id)->where('wechat', trim($request->input('wechat')))->count() > 0)
      $confirm_fields[] = 'WeChat';
    foreach($request->input('amazon_profiles') as $key => $amz)
      if(Tester::where('id', '<>', $tester->id)->where('amazon_profiles', 'like', '%'.trim($amz).'%')->count() > 0)
        $confirm_fields[] = 'profilo Amazon no. ' . ($key+1);
    foreach($request->input('facebook_profiles') as $key => $fb)
      if(Tester::where('id', '<>', $tester->id)->where('facebook_profiles', 'like', '%'.trim($fb).'%')->count() > 0)
        $confirm_fields[] = 'Facebook ID no. ' . ($key+1);

    if(count($confirm_fields) > 0 && $request->input('confirmation') != "true")
      return redirect()
        ->back()
        ->withInput(array_merge($request->all(), ['confirmation' => 'true', 'fields' => implode(", ", $confirm_fields)]));

    $tester->fill($request->only([
      'name', 'email', 'wechat', 'profile_image', 'amazon_profiles', 'facebook_profiles'
    ]));
    $tester->save();

    return redirect()
      ->route('panel.testers.view', $tester->id)
      ->with('status', 'Tester aggiornato con successo!');
  }

  public function delete(Request $request, Tester $tester) {
    $tester->delete();

    return redirect()
      ->route('panel.testers.home')
      ->with('status', 'Tester eliminato con successo!');
  }

  public function fetch(Request $request) {
    $search = trim($request->input('s', null));

    if(!empty($search)) {
        $sellers = Tester::where("id", $search)
          ->orWhere("name", "like", "%".$search."%")
          ->orWhere("email", "like", "%".$search."%")
          ->limit(15)
          ->get(['id', 'name', 'email']);
    } else
      $sellers = Tester::orderBy('name', 'asc')
        ->limit(15)
        ->get(['id', 'name', 'email']);

    return $sellers;
  }

  public function import(Request $request) {
    if($request->hasFile('sheet') && $request->sheet->getClientOriginalExtension() == "csv") {
      $path = $request->sheet->path();

      $file = fopen($path, "r");
      $i = 0;
      while (($data = fgetcsv($file)) !== false) {
        if($i > 0) {
          $name = trim($data[1]);
          if(!empty($name)) {
            $fbid = trim($data[0]);
            if(Tester::where('facebook_profiles', 'like', '%"'.$fbid.'"%')->count() == 0) {
              $tester = new Tester;
              $tester->name = $name;
              $tester->facebook_profiles = [$fbid];
              $tester->profile_image = "https://graph.facebook.com/{$fbid}/picture?type=large";

              $email = trim($data[2]);
              if(!empty($email))
                $tester->email = $email;

              $amazon = trim($data[4]);
              if(!empty($amazon) && substr($amazon, 0, 4) == "http")
                $tester->amazon_profiles = [$amazon];
              else
                $tester->amazon_profiles = [];

              $tester->save();
            }
          }
        } else
          $i++;
      }
      fclose($file);

      return redirect()
        ->route('panel.testers.home')
        ->with('status', 'I testers sono stati importati con successo! Duplicati e vuoti sono stati saltati.');
    } else
      return redirect()
        ->back()
        ->withErrors(['sheet' => ['Seleziona un file valido prima di continuare.']]);
  }
}
