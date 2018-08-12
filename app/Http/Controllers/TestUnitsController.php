<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{TestUnit, BannedPaypal};
use DB;
use \Carbon\Carbon;
use App\Http\Requests\TestAcceptRequest;
use App\Notifications\{BoughtProduct, BannedPaypalEmail};
use Notification;

class TestUnitsController extends Controller
{
    public function view(Request $request, $testUnit) {
      $testUnit = TestUnit::where('hash_code', $testUnit)->where('tester_id', '<>', null)->where('status', 0)->firstOrFail();
      if((new Carbon($testUnit->expires_on, config('app.timezone'))) < Carbon::now())
        abort(404);
      return view('test', compact('testUnit'));
    }
    public function go(Request $request, $testUnit) {
      $testUnit = TestUnit::where('hash_code', $testUnit)->where('status', 0)->firstOrFail();
      if((new Carbon($testUnit->expires_on, config('app.timezone'))) < Carbon::now())
        abort(404);

      if(empty($testUnit->viewed)) {
        $testUnit->viewed = true;
        $testUnit->save();
      }

      return redirect($testUnit->reference_url);
    }

    public function accept(TestAcceptRequest $request, $testUnit) {
      $testUnit = TestUnit::where('hash_code', $testUnit)->where('status', 0)->firstOrFail();
      if((new Carbon($testUnit->expires_on, config('app.timezone'))) < Carbon::now())
        abort(404);

      $testUnit->fill($request->only('paypal_account', 'amazon_order_id', 'tester_notes'));
      $testUnit->status = 1;
      $testUnit->statuses()->create(['status' => 1]);
      $testUnit->save();

      try {
        Notification::route('mail', config('app.notifiable'))
          ->notify(new BoughtProduct($testUnit));
      } catch(\Swift_TransportException $e) {}

      if(BannedPaypal::where('email', $request->input('paypal_account'))->first()) {
        try {
          Notification::route('mail', config('app.notifiable'))
            ->notify(new BannedPaypalEmail($testUnit));
        } catch(\Swift_TransportException $e) {}
      }

      return redirect()
        ->route('tests.thankyou', $testUnit->hash_code)
        ->with('accepted', true);
    }

    public function thankYou(Request $request, $testUnit) {
      if(empty(session('accepted')))
        abort(404);

      return view('thankyou');
    }
}
