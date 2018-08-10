<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TestOrderFormRequest;
use App\Store;
use App\Product;
use App\TestOrder;

class TestOrdersController extends Controller
{
  public function index(Request $request) {
    $orderBy = $request->query('orderBy', null);
    if(!empty($orderBy) && !in_array($orderBy, ['test_order.id', 'store.name', 'product.title', 'test_order.created_at', 'incomplete_units']))
      $orderBy = null;
    $sort = $request->query('sort', 'asc');
    if($sort != "asc" && $sort != "desc")
      $sort = "asc";
    $search = trim($request->query('s', null));

    $testOrders = DB::table('test_order')
        ->leftJoin('store', 'test_order.store_id', '=', 'store.id')
        ->leftJoin('product', 'test_order.product_id', '=', 'product.id');

    if(!empty($search))
      if($search[0] == ":") {
        $params = explode(",", substr($search, 1));
        foreach($params as $param) {
          $query = explode("=", $param, 2);

          $combinations = [
            'product' => 'product.id',
            'store' => 'store.id',
            'ASIN' => 'product.ASIN'
          ];

          if(in_array($query[0], array_keys($combinations)))
            $testOrders->where($combinations[$query[0]], $query[1]);
        }
      } else
        $testOrders = $testOrders->where(function($query) use($search) {
          $query->where("store.id", $search)
            ->orWhere("store.name", "like", "%".$search."%")
            ->orWhere("product.id", $search)
            ->orWhere("product.ASIN", $search)
            ->orWhere("product.title", "like", "%".$search."%");
        });

    $completed_filter = request()->query('completed', 'n');
    if($completed_filter == 'y' || $completed_filter == 'n') {
      $op = $completed_filter == 'y' ? '=' : '>';
      $testOrders = $testOrders->whereRaw("(test_order.quantity - (select count(*) from `test_unit` where `test_order_id` = test_order.id and ((`status` > 0 and `status` <> 5 and `status` <> 6 and `status` <> 8) or (`status` = 0 and `expires_on` > '".\Carbon\Carbon::now(config('app.timezone'))."' and `tester_id` is not null)))) ".$op." 0");
    }

    $visible_filter = request()->query('visible');
    if($visible_filter == 'y' || $visible_filter == 'n')
      $testOrders = $testOrders->where('is_product_public', $visible_filter == 'y');

    if(!empty($orderBy))
      $testOrders = $testOrders->orderBy($orderBy, $sort)->orderBy('test_order.id', $sort);
    else
      $testOrders = $testOrders->orderBy('test_order.created_at', 'desc')->orderBy('test_order.id', $sort);

    $testOrders = $testOrders
        ->select(
          DB::raw("(test_order.quantity - (select count(*) from `test_unit` where `test_order_id` = test_order.id and ((`status` > 0 and `status` <> 5 and `status` <> 6 and `status` <> 8) or (`status` = 0 and `expires_on` > '".\Carbon\Carbon::now(config('app.timezone'))."' and `tester_id` is not null)))) as incomplete_units"),
          "test_order.id as testOrder_id",
          "test_order.created_at as testOrder_created_at",
          "test_order.quantity",
          "product.id as product_id",
          "product.title as product_name",
          "store.id as store_id",
          "store.name as store_name",
          "product.images as product_images",
          "product.URL as product_URL"
        )->paginate(15);

    return view("panel/testOrders/home", ['testOrders' => $testOrders]);
  }

  public function create(Request $request, Product $product, Store $store) {
    $testOrder = new TestOrder;
    $testOrder->product()->associate($product);
    $testOrder->store()->associate($store);
    return view('panel/testOrders/form', compact('testOrder'));
  }

  public function put(TestOrderFormRequest $request, Product $product, Store $store) {
    $testOrder = new TestOrder;
    $testOrder->fee = $request->input('fee');
    $testOrder->description = $request->input('description');
    $testOrder->quantity = $request->input('quantity');
    $testOrder->is_product_public = $request->input('is_product_public') == "on";
    $testOrder->is_product_link_public = $request->input('is_product_link_public') == "on";
    $testOrder->product()->associate($product);
    $testOrder->store()->associate($store);
    $testOrder->save();

    return redirect()
      ->route('panel.testOrders.view', $testOrder->id);
  }

  public function view(Request $request, TestOrder $testOrder) {
    return view('panel/testOrders/view', compact('testOrder'));
  }

  public function edit(Request $request, TestOrder $testOrder) {
    return view('panel/testOrders/form', compact('testOrder'));
  }

  public function update(TestOrderFormRequest $request, TestOrder $testOrder) {
    $testOrder->fee = $request->input('fee');
    $testOrder->is_product_public = $request->input('is_product_public') == "on";
    $testOrder->is_product_link_public = $request->input('is_product_link_public') == "on";
    $testOrder->description = $request->input('description');
    $testOrder->quantity = $request->input('quantity');
    $testOrder->save();

    return redirect()
      ->route('panel.testOrders.view', $testOrder->id)
      ->with('status', 'Ordine di lavoro aggiornato con successo!');
  }

  public function delete(Request $request, TestOrder $testOrder) {
    if($testOrder->hasCompletes())
      abort(403);

    $testOrder->delete();

    return redirect()
      ->route('panel.testOrders.home')
      ->with('status', 'Ordine di lavoro cancellato con successo!');
  }
}
