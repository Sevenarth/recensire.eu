<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\ProductFormRequest;
use App\Http\Controllers\Controller;
use App\Product;
use App\Store;
use App\Category;
use Validator;

class ProductsController extends Controller
{
  public function index(Request $request) {
    $orderBy = $request->query('orderBy', null);
    if(!empty($orderBy) && !in_array($orderBy, ['id', 'brand', 'title', 'ASIN']))
      $orderBy = null;
    $sort = $request->query('sort', 'asc');
    if($sort != "asc" && $sort != "desc")
      $sort = "asc";
    $search = trim($request->query('s', null));

    if(!empty($search)) {
        $products = Product::where("id", $search)
          ->orWhere("brand", "like", $search."%")
          ->orWhere("title", "like", "%".$search."%")
          ->orWhere("ASIN", $search);

        if(!empty($orderBy))
          $products = $products->orderBy($orderBy, $sort)->orderBy('id', $sort)->paginate(15);
        else
          $products = $products->orderBy('created_at', 'desc')->orderBy('id', $sort)->paginate(15);
    } else {
        if(!empty($orderBy))
          $products = Product::orderBy($orderBy, $sort)->orderBy('id', $sort)->paginate(15);
        else
          $products = Product::orderBy('created_at', 'desc')->orderBy('id', $sort)->paginate(15);
    }

    return view("panel/products/home")->with('products', $products);
  }

  public function create(Request $request) {

    return view("panel/products/form", ['product' => new Product, 'catsTree' => Category::tree()]);
  }

  public function tags(Request $request) {
    $q = Product::allTags();

    if($request->query('s'))
      $q = $q->where('name', 'like', $request->query('s').'%');

    return $q->select(['name'])->limit(10)->get();
  }

  public function put(ProductFormRequest $request) {
    $product = Product::create($request->only([
      'title', 'brand', 'ASIN', 'URL', 'description', 'images'
    ]));
    $product->save();
    $tags = array_filter(array_map('trim', explode(",", $request->input('tags'))));
    if(count($tags) > 0)
      $product->tag($tags);

    $cats = array_map('intval', $request->input('categories'));

    foreach($cats as $i => $cat)
      if($cat === 0)
        unset($cats[$i]);

    if(!empty($cats))
      $product->categories()->attach($cats);


    return redirect()
      ->route('panel.products.home')
      ->with('status', 'Nuovo prodotto inserito con successo!');
  }

  public function view(Request $request, Product $product) {

    $orderBy = $request->query('orderBy', null);
    if(!empty($orderBy) && !in_array($orderBy, ['name', 'store.id']))
      $orderBy = null;
    $sort = $request->query('sort', 'asc');
    if($sort != "asc" && $sort != "desc")
      $sort = "asc";
    $search = trim($request->query('s', null));

    $stores = $product->stores();

    if(!empty($search))
      $stores = $stores->where(function($query) use($search) {
        $query->where("store.id", $search)
          ->orWhere("name", "like", "%".$search."%")
          ->orWhere("company_name", "like", "%".$search."%")
          ->orWhere("company_registration_no", $search)
          ->orWhere("VAT", $search);
      });

    if(!empty($orderBy))
      $stores = $stores->orderBy($orderBy, $sort);

    $stores = $stores->paginate(15);

    return view("panel/products/view", ['product' => $product, 'stores' => $stores]);
  }

  public function edit(Request $request, Product $product) {
    return view("panel/products/form", ['product' => $product, 'catsTree' => Category::tree()]);
  }

  public function update(ProductFormRequest $request, Product $product) {
    $product->fill($request->only([
      'title', 'brand', 'ASIN', 'URL', 'description', 'images'
    ]));
    $product->save();

    $tags_org = array_map(function($elt) { return $elt['name']; }, $product->tags->toArray());
    $tags = array_filter(array_map('trim', explode(",", $request->input('tags'))));

    if($toDelete = array_diff($tags_org, $tags))
      $product->untag($toDelete);
    if($toAdd = array_diff($tags, $tags_org))
      $product->tag($toAdd);

    $cats_org = array_map(function($elt) { return $elt['id']; }, $product->categories->toArray());
    $cats = array_map('intval', $request->input('categories'));

    foreach($cats as $i => $cat)
      if($cat === 0)
        unset($cats[$i]);

    if($toDelete = array_diff($cats_org, $cats))
      $product->categories()->detach($toDelete);
    if($toAdd = array_diff($cats, $cats_org))
      $product->categories()->attach($toAdd);

    return redirect()
      ->route('panel.products.view', $product->id)
      ->with('status', 'Prodotto aggiornato con successo!');
  }

  public function delete(Request $request, Product $product) {
    $product->delete();

    return redirect()
      ->route('panel.products.home')
      ->with('status', 'Prodotto eliminato con successo!');
  }

  public function attachStore(Request $request, Product $product) {
    Validator::extend('unassociated', function ($attribute, $value, $parameters, $validator) use($product) {
      return !(DB::table('store_product')->where('product_id', $product->id)->where('store_id', $value)->count() > 0);
    });
    $validator = Validator::make($request->all(), [
      'store_id' => 'required|exists:store,id|unassociated'
    ], [
      'required' => 'Seleziona un negozio valido.',
      'exists' => "Il negozio richiesto non è nel sistema.",
      'unassociated' => "Il prodotto &egrave; gi&agrave; associato a questo negozio."
    ]);

    if ($validator->fails())
      return redirect()
        ->route('panel.products.view', $product->id)
        ->withErrors($validator)
        ->withInput();

    $product->stores()->attach($request->input('store_id'));

    return redirect()
      ->route('panel.products.view', $product->id)
      ->with('status', 'Prodotto associato con successo!');
  }

  public function detachStore(Request $request, Product $product, Store $store) {
    if(DB::table('store_product')->where('product_id', $product->id)->where('store_id', $store->id)->count() > 0) {
      $store->products()->detach($product);

      return redirect()
        ->route('panel.products.view', $product->id)
        ->with('status', 'Prodotto disassociato con successo!');
    } else
      return response('Bad request', 400);
  }
}
