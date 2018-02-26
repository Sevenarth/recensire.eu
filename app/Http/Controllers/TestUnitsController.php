<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestUnit;
use DB;
use \Carbon\Carbon;

class TestUnitsController extends Controller
{
    public function view(Request $request, $testUnit) {
      $testUnit = TestUnit::where('hash_code', $testUnit)->where('status', 0)->firstOrFail();
      if((new Carbon($testUnit->expires_on, config('app.timezone'))) < Carbon::now())
        abort(404);
      return view('test', compact('testUnit'));
    }
    public function go(Request $request, $testUnit) {
      $testUnit = TestUnit::where('hash_code', $testUnit)->where('status', 0)->firstOrFail();
      if((new Carbon($testUnit->expires_on, config('app.timezone'))) < Carbon::now())
        abort(404);

      if(!empty($testUnit->viewed)) {
        $testUnit->viewed = true;
        $testUnit->save();
      }

      return redirect($testUnit->reference_url);
    }
}
