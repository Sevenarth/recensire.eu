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
    $tester = Tester::create($request->only([
      'name', 'email', 'wechat', 'profile_image', 'amazon_profiles', 'facebook_profiles'
    ]));

    return redirect()
      ->route('panel.testers.home')
      ->with('status', 'Tester aggiunto con successo!');
  }

  public function view(Request $request, Tester $tester) {
    return view('panel/testers/view', compact('tester'));
  }

  public function edit(Request $request, Tester $tester) {
    return view('panel/testers/form', compact('tester'));
  }

  public function update(TesterFormRequest $request, Tester $tester) {
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
}
