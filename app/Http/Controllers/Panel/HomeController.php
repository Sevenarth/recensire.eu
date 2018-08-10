<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use Storage;
use App\{TestOrder, TestUnit, TestUnitStatus, Tester, Product, Store, Seller, BannedPaypal};
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class HomeController extends Controller
{
    public function index(Request $request) {
      /*$incomplete = [];
      foreach(TestOrder::all() as $testOrder) {
        $count = $testOrder->testUnits()->where(function($q) {
          $q->where('status', '>', 0)->orWhere(function($q) {
            $q->where('status', 0)->where('expires_on', '>', \Carbon\Carbon::now(config('app.timezone')));
          });
        })->count();
        $testOrder->present = $count;
        if($testOrder->quantity > $count)
          $incomplete[] = $testOrder;
      }*/

      $acceptedToday = TestUnitStatus::where('status', 1)
        ->where('created_at','>',Carbon::now(config('app.timezone'))->startOfDay())
        ->groupBy('test_unit_id')
        ->select('test_unit_id', DB::raw('MAX(created_at) as created_at'))
        ->get();

      $reviewedToday = TestUnitStatus::where('status', 2)
        ->where('created_at','>',Carbon::now(config('app.timezone'))->startOfDay())
        ->groupBy('test_unit_id')
        ->select('test_unit_id', DB::raw('MAX(created_at) as created_at'))
        ->get();

      $completedToday = TestUnitStatus::where('status', 3)
        ->where('created_at','>',Carbon::now(config('app.timezone'))->startOfDay())
        ->groupBy('test_unit_id')
        ->select('test_unit_id', DB::raw('MAX(created_at) as created_at'))
        ->get();

      return view("panel/home", compact('incomplete', 'acceptedToday', 'reviewedToday', 'completedToday'));
    }

    public function upload(Request $request) {

      return view("panel/upload");
    }

    public function postUpload(ImageUploadRequest $request) {
      $url = Storage::disk('public')->url($request->image->store('images', 'public'));
      if($fn = $request->input('fn')) {
        $fn = $request->input('fn') . "('{$url}')";
      } else if($iid = $request->input('field')) {
        $id = $iid . "-field";
        $fn = "updateImageField('".$iid."')";
      } else {
        $id = "profile_image";
        $fn = "updateImage()";
      }

      return "<script>".
      (!empty($id) ? "window.opener.document.getElementById('{$id}').value = '{$url}';".PHP_EOL : "")
      ."window.opener.{$fn}
      window.close()
      </script>";
    }

    public function report(Request $request) {
      $sellers = [];
      if(old('sellers'))
        foreach(old('sellers') as $seller_id)
          if($seller = Seller::where('id', $seller_id)->select('id', 'nickname as name')->first())
            $sellers[] = $seller;

      $stores = [];
      if(old('stores'))
        foreach(old('stores') as $store_id)
          if($store = Store::where('id', $store_id)->select('id', 'name')->first())
            $stores[] = $store;

      return view('panel/report', compact('stores', 'sellers'));
    }

    public function postReport(Request $request) {
      $report = "";

      if(!empty($request->input('start_date'))&&!empty($request->input('end_date'))) {
        $onlyCurrent = $request->input('current_state') == "on";

        if($onlyCurrent)
          $statuses = DB::table('test_unit');
        else
          $statuses = DB::table('test_unit_status')
            ->leftJoin('test_unit', 'test_unit_status.test_unit_id', '=', 'test_unit.id');

        $statuses = $statuses->leftJoin('test_order', 'test_order.id', '=', 'test_unit.test_order_id')
          ->leftJoin('store', 'test_order.store_id', '=', 'store.id')
          ->where('test_unit'.($request->input('status') == "expiring" ? '.expires_on' : ($onlyCurrent ? '.updated_at' : '_status.created_at')), '>', (new Carbon($request->input('start_date')))->startOfDay())
          ->where('test_unit'.($request->input('status') == "expiring" ? '.expires_on' : ($onlyCurrent ? '.updated_at' : '_status.created_at')), '<', (new Carbon($request->input('end_date')))->endOfDay());

        if(!empty($request->input('sellers')) && is_array($request->input('sellers')))
          $statuses = $statuses->whereIn('store.seller_id', $request->input('sellers'));
        if(!empty($request->input('stores')) && is_array($request->input('stores')))
          $statuses = $statuses->whereIn('store.id', $request->input('stores'));

        if($request->input('status') == "others" && is_array($request->input('statuses')) && count($request->input('statuses')))
          $statuses = $statuses->whereIn('test_unit' . (!$onlyCurrent ? '_status' : '') . '.status', $request->input('statuses'));
        elseif($request->input('status') == "expiring")
          $statuses = $statuses->where('test_unit.status', '0');

        $statuses = $statuses->orderBy('store.name')->orderBy('test_unit.test_order_id');
        
        if(!$onlyCurrent)
          $statuses = $statuses->orderBy('test_unit_status.status', 'desc');
        
        $statuses = $statuses->select([
          'amazon_order_id',
          'paypal_account',
          'review_url',
          'refunded',
          'test_unit' . (!$onlyCurrent ? '_status' : '') . '.status as status',
          'refunded_amount',
          'expires_on',
          'tester_id',
          'hash_code',
          'test_unit.id as unit_id',
          'store.name as store_name',
          'product_id',
          'store.id as store_id'
        ])->get();

        $store = null;

        foreach($statuses as $status) {
          if($status->store_name != $store) {
            $report .= "---- Store name: <a href=\"".route('panel.stores.view', $status->store_id)."\">" . $status->store_name . "</a>" . PHP_EOL;
            $store = $status->store_name;
          }
          $row = [];
          $tester = !empty($status->tester_id) ? Tester::find($status->tester_id) : new Tester;
          if($request->input('hash_code') == "on")
            $row[] = "Hash code: <a href=\"".route('panel.testUnits.view', $status->unit_id)."\">" . (!empty($status->hash_code) ? $status->hash_code : 'N/A' ). "</a>";
          if($request->input('amazon_order_id') == "on")
            $row[] = "Order No: " . (!empty($status->amazon_order_id) ? $status->amazon_order_id : 'N/A');
          if($request->input('paypal_account') == "on")
            $row[] = "PayPal account: " . (!empty($status->paypal_account) ? $status->paypal_account : 'N/D');
          if($request->input('review_url') == "on")
            $row[] = 'Review URL: ' . (!empty($status->review_url) ? $status->review_url : 'N/D');
          if($request->input('amazon_profile') == "on")
            $row[] = 'Amazon Profile: ' . (isset($tester->amazon_profiles[0]) ? $tester->amazon_profiles[0] : 'N/A');
          if($request->input('tester_name') == "on")
            $row[] = 'Tester name: ' . (!empty($tester->name) ? $tester->name : 'N/A');
          if($request->input('facebook_id') == "on")
            $row[] = 'Facebook ID: ' . (!empty($tester->facebook_profiles[0]) ? $tester->facebook_profiles[0] : 'N/A');
          if($request->input('refunded') == "on")
            $row[] = 'Refunded: ' . (!empty($status->refunded) ? 'Yes' : 'No');
          if($request->input('asin') == "on") {
            $product = Product::find($status->product_id);
            $row[] = 'ASIN: ' . $product->ASIN;
          }
          if($request->input('refunded_amount') == "on")
            $row[] = 'Refunded amount: ' . (!empty($status->refunded_amount) ? config('app.currency') . " " . number_format($status->refunded_amount, 2, '.', '') : 'N/A');
          if($request->input('status_check') == "on") {
            $expiration = new \Carbon\Carbon($status->expires_on, config('app.timezone'));
            if($expiration->gt(\Carbon\Carbon::now(config('app.timezone'))) || $status->status > 0)
              $row[] = 'Status: ' . config('testUnit.statuses')[$status->status];
            else
              $row[] = 'Status: Scaduto';
          }

          if(count($row) > 0)
            $report .= implode("\t", $row) . PHP_EOL;
        }
        $total = count($statuses);
      }
      if(isset($total) && is_numeric($total))
        return redirect()
          ->route('panel.report')
          ->withInput(array_merge($request->all(), ["report" => $report]))
          ->with('status', 'La query ha generato ' . $total . ' risultat'. ($total == 1 ? 'o' : 'i'));
      else
        return redirect()
          ->route('panel.report')
          ->withInput(array_merge($request->all(), ["report" => $report]));
    }

    public function banlist() {
      $bpp = BannedPaypal::orderBy('created_at','desc')->orderBy('email')->get();
      return view('panel.banlist', compact('bpp'));
    }

    public function banlistUpdate()
    {
        $create = request()->input('create', false);
        $delete = request()->input('delete', false);
        $mass = request()->input('mass');
        $key = request()->input('email');
        $value = request()->input('notes');

        if($mass) {
          $i = 0;
          $bans = [];

          foreach($mass as $entry) {
            $key = $entry['email'];
            $value = $entry['notes'];

            if(!preg_match("/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i", $key))
              continue;
            
            $ban = BannedPaypal::where('email', $key)->first();
            if($ban)
              continue;
            
            $ban = new BannedPaypal;
            $ban->email = $key;
            $ban->notes = $value;
            $ban->save();
            $i++;
            $bans[] = $ban;
          }

          return response()->json([
            'bans' => $bans,
            'count' => $i
          ]);
        }

        $ban = BannedPaypal::where('email', $key)->first();

        if($delete) {
            if($ban) {
                $ban->delete();
                return response()->json(['status' => 'Ban eliminato con successo!']);
            } else
                return response()->json(['email' => 'Questa email bannata è inesistente'], 404);
        }

        if(!preg_match("/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i", $key))
            return response()->json(['email' => 'Inserire una email valida!'], 400);

        if($create) {
            if($ban)
                return response()->json(['email' => 'Questa email esiste già'], 409);

            $ban = new BannedPaypal;
        }
        
        if($ban) {
            $ban->email = $key;
            $ban->notes = $value;
            $ban->save();

            return response()->json([
                'ban' => $ban,
                'status' => 'Ban ' . ($create ? 'aggiunto' : 'modificato') . ' con successo!'
            ]);
        }

        return response()->json(['email' => 'Questa email bannata è inesistente'], 404);
    }

    public function banlistExport() {
      $output = "Email,Creato il,Note".PHP_EOL;

      foreach(BannedPaypal::orderBy('created_at','desc')->orderBy('email')->get() as $ban)
        $output .= $ban->email . "," . \Carbon::Carbon($ban->created_at)->format("d M Y H:i") . "," . json_encode($ban->notes) . PHP_EOL;

      return response()->streamDownload(function () use($output) {
        echo $output;
      }, 'Ban list Recensire.eu ' . date('d M Y') . '.csv');
    }
}
