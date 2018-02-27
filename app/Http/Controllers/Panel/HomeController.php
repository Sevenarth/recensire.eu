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
        ->where('created_at','>',Carbon::now()->startOfDay())
        ->groupBy('test_unit_id')
        ->select('test_unit_id', DB::raw('MAX(created_at) as created_at'))
        ->get();

      $reviewedToday = TestUnitStatus::where('status', 2)
        ->where('created_at','>',Carbon::now()->startOfDay())
        ->groupBy('test_unit_id')
        ->select('test_unit_id', DB::raw('MAX(created_at) as created_at'))
        ->get();

      $refundedToday = TestUnitStatus::where('status', 3)
        ->where('created_at','>',Carbon::now()->startOfDay())
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
      (!empty($id) ? "window.opener.document.getElementById('{$id}').value = '{$url}'" : "")
      ."window.opener.{$fn}
      window.close()
      </script>";
    }
}
