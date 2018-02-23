<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Requests\ProductFormRequest;
use App\Http\Controllers\Controller;
use App\Product;
use App\Category;

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
          $products = $products->orderBy($orderBy, $sort)->paginate(15);
        else
          $products = $products->paginate(15);
    } else {
        if(!empty($orderBy))
          $products = Product::orderBy($orderBy, $sort)->paginate(15);
        else
          $products = Product::paginate(15);
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
    return view("panel/products/view", ['product' => $product]);
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
}
