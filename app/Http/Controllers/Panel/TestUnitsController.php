<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TestOrder;
use App\TestUnit;
use App\Http\Requests\TestUnitFormRequest;
use Carbon\Carbon;
use App\Tester;

class TestUnitsController extends Controller
{
    public function create(Request $request, TestOrder $testOrder) {
      $testUnit = new TestUnit;
      $testUnit->testOrder()->associate($testOrder);

      return view('panel/testUnits/form', compact('testUnit'));
    }

    public function put(TestUnitFormRequest $request, TestOrder $testOrder) {
      $testUnit = new TestUnit;
      $testUnit->hash_code = "placeholder";
      $testUnit->fill($request->only([
        'amazon_order_id', 'review_url', 'reference_url',
        'instructions', 'status', 'paypal_account', 'refunded_amount'
      ]));
      $testUnit->expires_on = new Carbon(
        $request->input('expires_on_date') . " " . $request->input('expires_on_time'),
        config('app.timezone')
      );
      $testUnit->tester()->associate(Tester::find($request->input('tester_id')));
      $testUnit->testOrder()->associate($testOrder);
      $testUnit->save();
      $testUnit->statuses()->create([
        'status' => $request->input('status')
      ]);

      return redirect()
        ->route('panel.testOrders.view', $testOrder->id)
        ->with('status', 'Unità di test aggiunta con successo!');
    }

    public function view(Request $request, TestUnit $testUnit) {
      return view('panel/testUnits/view', compact('testUnit'));
    }

    public function edit(Request $request, TestUnit $testUnit) {
      return view('panel/testUnits/form', compact('testUnit'));
    }

    public function update(TestUnitFormRequest $request, TestUnit $testUnit) {
      $testUnit->fill($request->only([
        'amazon_order_id', 'review_url', 'reference_url',
        'instructions', 'status', 'paypal_account', 'refunded_amount'
      ]));
      $testUnit->expires_on = new Carbon(
        $request->input('expires_on_date') . " " . $request->input('expires_on_time'),
        config('app.timezone')
      );
      $testUnit->save();
      if($testUnit->status != $request->input('status'))
        $testUnit->statuses()->create([
          'status' => $request->input('status')
        ]);

      return redirect()
        ->route('panel.testOrders.testUnits.view', $testUnit->id)
        ->with('status', 'Unità di test modificata con successo!');
    }

    public function delete(Request $request, TestUnit $testUnit) {
      $id = $testUnit->testOrder->id;
      $testUnit->delete();

      return redirect()
        ->route('panel.testOrders.view', $id)
        ->with('status', 'Unità di test eliminata con successo!');
    }
}
