<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactUsRequest;
use Notification;
use App\Notifications\ContactUsNotification;
use App\{Category, Product, Option};
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function front(Request $request, $category_slug = null) {
      $categories = Category::whereNull('parent_id')->orderBy('title')->select('id', 'title', 'slug')->get();

      if($category_slug) {
        $category = null;
        foreach($categories as $cat)
          if($cat->slug == $category_slug)
            $category = $cat;

        if(!$category) abort(404);

        $tree = $category->getFamily();
        //dd($tree);
        $products = Product::rightJoin('category_product', 'product.id', '=', 'category_product.product_id')
          ->whereIn('category_product.category_id', $tree);
      } else {
        $category = false;
        $products = Product::query();
      }

      $test_orders = DB::table('test_order')
        ->orderBy('test_order.created_at', 'desc')
        ->select(
          'test_order.product_id',
          'test_order.created_at',
          'test_order.is_product_public',
          'test_order.is_product_link_public',
          DB::raw("(test_order.quantity - (select count(*) from `test_unit` where `test_order_id` = test_order.id and ((`status` > 0 and `status` <> 5) or (`status` = 0 and `expires_on` > '".\Carbon\Carbon::now(config('app.timezone'))."' and `tester_id` is not null)))) as `remaining`")
        )->get();

      $active_products = [];
      $watched_products = [];
      $products_links = [];
      foreach($test_orders as $test_order)
        if(!in_array($test_order->product_id, $watched_products) && $test_order->remaining > 0) {
            $watched_products[] = $test_order->product_id;
            if($test_order->is_product_public) {
              $active_products[] = $test_order->product_id;
              $products_links[$test_order->product_id] = $test_order->is_product_link_public;
            }
        }
      $products = $products
        ->whereIn('product.id', $active_products)
        ->orderByRaw('FIELD(product.id,'.implode(',',$active_products).')')
        ->select('product.images', 'product.URL', 'product.title', 'product.id')
        ->paginate(20);

      return view('front', [
        'header' => Option::get('header-data'),
        'footer' => Option::get('footer-data'),
        'category_slug' => $category_slug,
        'categories' => $categories,
        'products' => $products,
        'products_links' => $products_links
      ]);
    }

    public function contactus(Request $request) {
      return view('contactus');
    }

    public function send(ContactUsRequest $request) {
        Notification::route('mail', config('app.notifiable'))
          ->notify(new ContactUsNotification($request));

        return redirect()
          ->back()
          ->with('status', 'Thank you for contacting us!');
    }
}
