<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SellerFormRequest;
use App\Seller;

class SellersController extends Controller
{
  public function index(Request $request) {
    $orderBy = $request->query('orderBy', null);
    if(!empty($orderBy) && !in_array($orderBy, ['id', 'nickname', 'name', 'email']))
      $orderBy = null;
    $sort = $request->query('sort', 'asc');
    if($sort != "asc" && $sort != "desc")
      $sort = "asc";
    $search = trim($request->query('s', null));

    if(!empty($search)) {
        $sellers = Seller::where("id", $search)
          ->orWhere("nickname", "like", "%".$search."%")
          ->orWhere("name", "like", "%".$search."%")
          ->orWhere("email", "like", "%".$search."%")
          ->orWhere("facebook", $search)
          ->orWhere("wechat", $search);

        if(!empty($orderBy))
          $sellers = $sellers->orderBy($orderBy, $sort)->orderBy('id', $sort)->paginate(15);
        else
          $sellers = $sellers->paginate(15);
    } else {
        if(!empty($orderBy))
          $sellers = Seller::orderBy($orderBy, $sort)->orderBy('id', $sort)->paginate(15);
        else
          $sellers = Seller::paginate(15);
    }

    return view("panel/sellers/home")->with('sellers', $sellers);
  }

  public function create(Request $request) {
    return view("panel/sellers/form");
  }

  public function put(SellerFormRequest $request) {
    $seller = Seller::create($request->only([
      'nickname', 'name', 'email', 'facebook', 'wechat', 'profile_image', 'notes'
    ]));
    $seller->save();

    return redirect()
      ->route('panel.sellers.home')
      ->with('status', 'Nuovo venditore inserito con successo!');
  }

  public function edit(Request $request, Seller $seller) {
    return view("panel/sellers/form", ['seller' => $seller]);
  }

  public function update(SellerFormRequest $request, Seller $seller) {
    $seller->fill($request->only([
      'nickname', 'name', 'email', 'facebook', 'wechat', 'profile_image', 'notes'
    ]));
    $seller->save();

    return redirect()
      ->route('panel.sellers.view', $seller->id)
      ->with('status', 'Venditore aggiornato con successo!');
  }

  public function delete(Request $request, Seller $seller) {
    $seller->delete();

    return redirect()
      ->route('panel.sellers.home')
      ->with('status', 'Venditore eliminato con successo!');
  }

  public function view(Request $request, Seller $seller) {
    return view("panel/sellers/view", ['seller' => $seller, 'stores' => $seller->stores()->paginate(5)]);
  }

  public function fetch(Request $request) {
    $search = trim($request->input('s', null));

    if(!empty($search)) {
        $sellers = Seller::where("id", $search)
          ->orWhere("nickname", "like", "%".$search."%")
          ->orWhere("name", "like", "%".$search."%")
          ->orWhere("email", "like", "%".$search."%")
          ->orWhere("facebook", $search)
          ->orWhere("wechat", $search)
          ->limit(15)
          ->get(['id', 'nickname', 'name', 'email']);
    } else
      $sellers = Seller::orderBy('name', 'asc')
        ->limit(15)
        ->get(['id', 'nickname', 'name', 'email']);

    return $sellers;
  }
}
