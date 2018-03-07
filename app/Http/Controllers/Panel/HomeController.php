<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use Storage;
use App\TestOrder;
use App\TestUnit;
use App\TestUnitStatus;
use App\Tester;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class HomeController extends Controller
{
    public function index(Request $request) {
      $incomplete = [];
      foreach(TestOrder::all() as $testOrder) {
        $count = $testOrder
          ->testUnits()
          ->where('expires_on', '>', Carbon::now(config('app.timezone')))
          ->count();
        $testOrder->present = $count;
        if($testOrder->quantity > $count)
          $incomplete[] = $testOrder;
      }

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
      return view('panel/report');
    }

    public function postReport(Request $request) {
      $report = "";
      $statuses = null;
      if(!empty($request->input('start_date'))&&!empty($request->input('end_date'))) {
        if(!empty($request->input('store_id')))
          foreach(TestOrder::where('store_id', $request->input('store_id'))->orderBy('id')->get() as $testOrder) {
            $statuses_ = DB::table('test_unit_status')
              ->leftJoin('test_unit', 'test_unit_status.test_unit_id', '=', 'test_unit.id')
              ->leftJoin('test_order', 'test_order.id', '=', 'test_unit.test_order_id')
              ->leftJoin('store', 'test_order.store_id', '=', 'store.id')
              ->where('test_unit.test_order_id', $testOrder->id)
              ->where('test_unit'.($request->input('status') == -1 ? '.expires_on' : '_status.created_at'), '>', (new Carbon($request->input('start_date')))->startOfDay())
              ->where('test_unit'.($request->input('status') == -1 ? '.expires_on' : '_status.created_at'), '<', (new Carbon($request->input('end_date')))->endOfDay());

            if(intval($request->input('status')) >= 0)
              $statuses_ = $statuses_->where('test_unit_status.status', $request->input('status'));
            elseif(intval($request->input('status')) == -1)
              $statuses = $statuses->where('test_unit.status', '0');

            $statuses_->orderBy('test_unit_status.status', 'desc')->select([
              'amazon_order_id',
              'paypal_account',
              'review_url',
              'refunded',
              'test_unit_status.status as status',
              'expires_on',
              'tester_id',
              'hash_code',
              'test_unit.id as unit_id',
              'store.name as store_name',
              'store.id as store_id'
            ]);

            if(empty($statuses)) $statuses = $statuses_->get();
            else $statuses = $statuses->merge($statuses_->get());
          }
        else {
          $statuses = DB::table('test_unit_status')
            ->leftJoin('test_unit', 'test_unit_status.test_unit_id', '=', 'test_unit.id')
            ->leftJoin('test_order', 'test_order.id', '=', 'test_unit.test_order_id')
            ->leftJoin('store', 'test_order.store_id', '=', 'store.id')
            ->where('test_unit'.($request->input('status') == -1 ? '.expires_on' : '_status.created_at'), '>', (new Carbon($request->input('start_date')))->startOfDay())
            ->where('test_unit'.($request->input('status') == -1 ? '.expires_on' : '_status.created_at'), '<', (new Carbon($request->input('end_date')))->endOfDay());

          if(intval($request->input('status')) >= 0)
            $statuses = $statuses->where('test_unit_status.status', $request->input('status'));
          elseif(intval($request->input('status')) == -1)
            $statuses = $statuses->where('test_unit.status', '0');

          $statuses->orderBy('store.id')->orderBy('test_unit.test_order_id')->orderBy('test_unit_status.status', 'desc')->select([
            'amazon_order_id',
            'paypal_account',
            'review_url',
            'refunded',
            'test_unit_status.status as status',
            'expires_on',
            'tester_id',
            'hash_code',
            'test_unit.id as unit_id',
            'store.name as store_name',
            'store.id as store_id'
          ]);
          $statuses = $statuses->get();
        }

        $store = null;

        foreach($statuses as $status) {
          if($status->store_name != $store) {
            $report .= "---- Store name: <a href=\"".route('panel.stores.view', $status->store_id)."\">" . $status->store_name . "</a><br>";
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
          if($request->input('status_check') == "on") {
            $expiration = new \Carbon\Carbon($status->expires_on, config('app.timezone'));
            if($expiration->gt(\Carbon\Carbon::now(config('app.timezone'))) || $status->status > 0)
              $row[] = 'Status: ' . config('testUnit.statuses')[$status->status];
            else
              $row[] = 'Status: Scaduto';
          }

          if(count($row) > 0)
            $report .= implode("\t", $row) . "<br>";
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
}
