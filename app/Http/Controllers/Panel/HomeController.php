<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use Storage;
use App\TestOrder;
use App\TestUnit;
use App\TestUnitStatus;
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

      $refundedToday = TestUnitStatus::where('status', 3)
        ->where('created_at','>',Carbon::now(config('app.timezone'))->startOfDay())
        ->groupBy('test_unit_id')
        ->select('test_unit_id', DB::raw('MAX(created_at) as created_at'))
        ->get();

      return view("panel/home", compact('incomplete', 'acceptedToday', 'reviewedToday', 'refundedToday'));
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
      $testUnits = new Collection();
      if(!empty($request->input('start_date'))&&!empty($request->input('end_date'))) {
        if(!empty($request->input('store_id')))
          foreach(TestOrder::where('store_id', $request->input('store_id'))->get() as $testOrder) {
            $units = $testOrder->testUnits()
              ->where('created_at', '>', (new Carbon($request->input('start_date')))->startOfDay())
              ->where('created_at', '<', (new Carbon($request->input('end_date')))->endOfDay());

            if(intval($request->input('status')) >= 0)
              $units = $units->where('status', $request->input('status'));

            $testUnits = $testUnits->merge($units->get());
          }
        else {
          $testUnits = TestUnit::where('created_at', '>', (new Carbon($request->input('start_date')))->startOfDay())
            ->where('created_at', '<', (new Carbon($request->input('end_date')))->endOfDay());

          if(intval($request->input('status')) >= 0)
            $testUnits = $testUnits->where('status', $request->input('status'));

          $testUnits = $testUnits->get();
        }

        foreach($testUnits as $unit) {
          $row = [];
          $tester = $unit->tester;
          if($request->input('amazon_order_id') == "on")
            $row[] = "Order No: " . (!empty($unit->amazon_order_id) ? $unit->amazon_order_id : 'N/A');
          if($request->input('paypal_account') == "on")
            $row[] = "PayPal account: " . (!empty($unit->paypal_account) ? $unit->paypal_account : 'N/D');
          if($request->input('review_url') == "on")
            $row[] = 'Review URL: ' . (!empty($unit->review_url) ? $unit->review_url : 'N/D');
          if($request->input('amazon_profile') == "on")
            $row[] = 'Amazon Profile: ' . (isset($tester->amazon_profiles[0]) ? $tester->amazon_profiles[0] : 'N/A');
          if($request->input('tester_name') == "on")
            $row[] = 'Tester name: ' . (!empty($tester->name) ? $tester->name : 'N/A');
          if($request->input('facebook_id') == "on")
            $row[] = 'Facebook ID: ' . (!empty($tester->facebook_profiles[0]) ? $tester->facebook_profiles[0] : 'N/A');
          if($request->input('refunded') == "on")
            $row[] = 'Refunded: ' . (!empty($unit->refunded) ? 'Yes' : 'No');

          if(count($row) > 0)
            $report .= implode("\t", $row) . PHP_EOL;
        }
      }
      return redirect()
        ->back()
        ->withInput(array_merge($request->all(), ["report" => $report]));
    }
}
