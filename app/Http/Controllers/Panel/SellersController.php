<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SellerFormRequest;
use App\{Seller, Product};

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
    $search = $request->input('s', $request->query('s', null));

    if(!empty($search)) {
        $sellers = Seller::where(function($q) use($search) {
          $q->where("id", $search)
          ->orWhere("nickname", "like", "%".$search."%")
          ->orWhere("name", "like", "%".$search."%")
          ->orWhere("email", "like", "%".$search."%")
          ->orWhere("facebook", $search)
          ->orWhere("wechat", $search);
        });
    } else
      $sellers = Seller::orderBy('name', 'asc');

    if($request->input('except') && is_array($request->input('except')))
      $sellers = $sellers->whereNotIn('id', $request->input('except'));

    return $sellers
      ->limit(20)
      ->get(['id', 'nickname', 'name', 'email']);
  }

  public function products(Request $request, Seller $seller) {
    $orderBy = $request->query('orderBy', null);
    if(!empty($orderBy) && !in_array($orderBy, ['ASIN', 'brand', 'title']))
      $orderBy = null;
    $sort = $request->query('sort', 'asc');
    if($sort != "asc" && $sort != "desc")
      $sort = "asc";
    $search = trim($request->query('s', null));

    foreach($seller->stores as $store) {
      $store_ids[] = $store->id;
      $store_names[$store->id] = $store->name;
    }

    $products = Product::whereHas('stores', function($q) use($store_ids) {
      $q->whereIn('store.id', $store_ids);
    });

    if(!empty($search))
      $products = $products->where(function($query) use ($search) {
        $query->where('ASIN', $search)
          ->orWhere('title', 'like', '%'.$search.'%')
          ->orWhere('brand', 'like', $search.'%');
      });

    if(!empty($orderBy))
      $products = $products->orderBy($orderBy, $sort);

    $products = $products->paginate(15);

    return view("panel/sellers/products", ['seller' => $seller, 'products' => $products, 'stores' => $store_names]);
  }
}
