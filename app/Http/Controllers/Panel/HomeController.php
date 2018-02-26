<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use Storage;
use App\TestOrder;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request) {
      $testOrders = [];
      foreach(TestOrder::all() as $testOrder) {
        $count = $testOrder
          ->testUnits()
          ->where(DB::raw('DATE(expires_on)'), '<', DB::raw('DATE(\''.Carbon::now(config('app.timezone')).'\')'))
          ->count();
        $testOrder->present = $count;
        if($testOrder->quantity > $count)
          $testOrders[] = $testOrder;
      }

      return view("panel/home", compact('testOrders'));
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
