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
          ->orWhere("facebook_profiles", "like", '%'.$search.'%')
          ->orWhere("amazon_profiles", "like", '%'.$search.'%')
          ->orWhere("wechat", '%'.$search.'%');

        if(!empty($orderBy))
          $testers = $testers->orderBy($orderBy, $sort)->orderBy('id', $sort)->paginate(15);
        else
          $testers = $testers->paginate(15);
    } else {
        if(!empty($orderBy))
          $testers = Tester::orderBy($orderBy, $sort)->orderBy('id', $sort)->paginate(15);
        else
          $testers = Tester::paginate(15);
    }

    return view("panel/testers/home")->with('testers', $testers);
  }

  public function create(Request $request) {
    $tester = new Tester;
    $tester->amazon_profiles = [];
    $tester->amazon_profiles_statuses = [];
    $tester->facebook_profiles = [];
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
      if(!empty($amz) && Tester::where('amazon_profiles', 'like', '%'.str_replace('/', '%', trim($amz)).'%')->count() > 0)
        $confirm_fields[] = 'profilo Amazon no. ' . ($key+1);
    foreach($request->input('facebook_profiles') as $key => $fb)
      if(!empty($fb) && Tester::where('facebook_profiles', 'like', '%'.trim($fb).'%')->count() > 0)
        $confirm_fields[] = 'Facebook ID no. ' . ($key+1);

    if(count($confirm_fields) > 0 && $request->input('confirmation') != sha1(json_encode($request->only(['name','email','wechat','amazon_profiles','facebook_profiles']))))
      return redirect()
        ->back()
        ->withInput(array_merge($request->all(), [
          'confirmation' => sha1(json_encode($request->only(['name','email','wechat','amazon_profiles','facebook_profiles']))),
          'fields' => implode(", ", $confirm_fields)
        ]));

    $amz = $request->input("amazon_profiles");
    $amz_statuses = $request->input("amazon_profiles_statuses");
    array_multisort($amz_statuses, $amz);

    $tester = Tester::create(array_merge($request->only([
      'name', 'email', 'wechat', 'profile_image', 'facebook_profiles', 'status', 'notes'
    ]), ['amazon_profiles' => $amz, 'amazon_profiles_statuses' => $amz_statuses]));

    return redirect()
      ->route('panel.testers.home')
      ->with('status', 'Tester aggiunto con successo!');
  }

  public function view(Request $request, Tester $tester) {
    $testUnits = $tester->testUnits()->orderBy('created_at', 'desc')->paginate(15);
    return view('panel/testers/view', compact('tester', 'testUnits'));
  }

  public function edit(Request $request, Tester $tester) {
    return view('panel/testers/form', compact('tester'));
  }

  public function update(TesterFormRequest $request, Tester $tester) {
    $confirm_fields = [];
    if($tester->name != $request->input('name') && Tester::where('id', '<>', $tester->id)->where('name', trim($request->input('name')))->count() > 0)
      $confirm_fields[] = 'nome';
    if($tester->email != $request->input('email') && Tester::where('id', '<>', $tester->id)->where('email', trim($request->input('email')))->count() > 0)
      $confirm_fields[] = 'indirizzo email';
    if($tester->wechat != $request->input('wechat') && Tester::where('id', '<>', $tester->id)->where('wechat', trim($request->input('wechat')))->count() > 0)
      $confirm_fields[] = 'WeChat';
    foreach($request->input('amazon_profiles') as $key => $amz)
      if(!empty($amz) && ((is_array($tester->amazon_profiles) && in_array($amz, $tester->amazon_profiles)) || !$tester->amazon_profiles) && Tester::where('id', '<>', $tester->id)->where('amazon_profiles', 'like', '%'.trim($amz).'%')->count() > 0)
        $confirm_fields[] = 'profilo Amazon no. ' . ($key+1);
    foreach($request->input('facebook_profiles') as $key => $fb)
      if(!empty($fb) && ((is_array($tester->facebook_profiles) && in_array($fb, $tester->amazon_profiles)) || !$tester->facebook_profiles) && Tester::where('id', '<>', $tester->id)->where('facebook_profiles', 'like', '%'.trim($fb).'%')->count() > 0)
        $confirm_fields[] = 'Facebook ID no. ' . ($key+1);

    if(count($confirm_fields) > 0 && $request->input('confirmation') != sha1(json_encode($request->only(['name','email','wechat','amazon_profiles','facebook_profiles']))))
      return redirect()
        ->back()
        ->withInput(array_merge($request->all(), [
          'confirmation' => sha1(json_encode($request->only(['name','email','wechat','amazon_profiles','facebook_profiles']))),
          'fields' => implode(", ", $confirm_fields)
        ]));

    $amz = $request->input("amazon_profiles");
    $amz_statuses = $request->input("amazon_profiles_statuses");
    array_multisort($amz_statuses, $amz);

    $tester->fill($request->only([
      'name', 'email', 'wechat', 'profile_image', 'facebook_profiles', 'status', 'notes'
    ]));
    $tester->amazon_profiles = $amz;
    $tester->amazon_profiles_statuses = $amz_statuses;
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
          ->get(['id', 'name', 'email', 'status']);
    } else
      $sellers = Tester::orderBy('name', 'asc')
        ->limit(15)
        ->get(['id', 'name', 'email', 'status']);

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

  public function export()
  {
    $output = 'Nome contatto,Indirizzo Email,Stato,Accounts PayPal' . PHP_EOL;

    foreach(Tester::orderBy('name', 'asc')->get() as $tester) {
      $output .= $tester->name . ',' . $tester->email . ',' . config('testers.statuses')[$tester->status] . ',';
      $output .= implode(',', array_map(function ($t) {
          return $t['paypal_account'];
        }, $tester->testUnits()->whereNotNull('paypal_account')->distinct()->get(['paypal_account'])->toArray()
      )) . PHP_EOL;
    }

    return response()->streamDownload(function () use($output) {
      echo $output;
    }, 'Testers Recensire.eu ' . date('d M Y') . '.csv');
  }
}
