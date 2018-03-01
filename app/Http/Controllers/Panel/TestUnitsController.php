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

    public function massCreate(Request $request, TestOrder $testOrder) {
      $testUnit = new TestUnit;
      $testUnit->testOrder()->associate($testOrder);
      $testUnit->mass = true;

      return view('panel/testUnits/form', compact('testUnit'));
    }

    public function massPut(TestUnitFormRequest $request, TestOrder $testOrder) {
      $count = $testOrder->testUnits()
        ->where('status', '>', 0)
        ->orWhere(function($q) {
          $q->where('status', 0)
            ->where('expires_on', '>', Carbon::now(config('app.timezone')));
        })->count();
      for($i = 0; $i < $testOrder->quantity-$count; $i++) {
        $testUnit = new TestUnit;
        $testUnit->hash_code = "placeholder";
        $testUnit->fill($request->only([
          'amazon_order_id', 'review_url', 'reference_url',
          'instructions', 'status', 'paypal_account',
          'refunded_amount', 'expires_on_time', 'expires_on_space',
          'refunding_type', 'tester_notes'
        ]));

        $testUnit->refunded = $request->input('refunded') == 'on' ? 1 : 0;
        $spaceSpecs = ['T%dS', 'T%dM', 'T%dH', '%dD', '%dW', '%dM', '%dY'];
        $expires_on = Carbon::now(config('app.timezone'));
        $expires_on->add(new \DateInterval(sprintf('P'.$spaceSpecs[$request->input('expires_on_space')], $request->input('expires_on_time'))));
        $testUnit->expires_on = $expires_on;

        $testUnit->testOrder()->associate($testOrder);
        $testUnit->save();

        $testUnit->statuses()->create([
          'status' => $request->input('status')
        ]);
      }

      if($testOrder->quantity-$count > 0)
        return redirect()
          ->route('panel.testOrders.view', $testOrder->id)
          ->with('status', 'Unità di test aggiunte con successo!');
      else
        return redirect()
          ->route('panel.testOrders.view', $testOrder->id)
          ->with('status', "L'ordine di lavoro è già al completo.");
    }

    public function put(TestUnitFormRequest $request, TestOrder $testOrder) {
      $testUnit = new TestUnit;
      $testUnit->hash_code = "placeholder";
      $testUnit->fill($request->only([
        'amazon_order_id', 'review_url', 'reference_url',
        'instructions', 'status', 'paypal_account',
        'refunded_amount', 'expires_on_time', 'expires_on_space',
        'refunding_type', 'tester_notes'
      ]));

      $testUnit->refunded = $request->input('refunded') == 'on' ? 1 : 0;

      $spaceSpecs = ['T%dS', 'T%dM', 'T%dH', '%dD', '%dW', '%dM', '%dY'];
      $expires_on = Carbon::now(config('app.timezone'));
      $expires_on->add(new \DateInterval(sprintf('P'.$spaceSpecs[$request->input('expires_on_space')], $request->input('expires_on_time'))));
      $testUnit->expires_on = $expires_on;

      $testUnit->tester()->associate(Tester::find($request->input('tester_id')));
      $testUnit->testOrder()->associate($testOrder);
      $testUnit->save();

      $testUnit->statuses()->create([
        'status' => $request->input('status')
      ]);

      if($testUnit->refunded)
        $testUnit->statuses()->create([
          'status' => 3
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
      $fields = [
        'review_url', 'tester_notes',
        'instructions', 'paypal_account',
        'refunded_amount', 'refunding_type'
      ];

      if($testUnit->status < 1){
        $fields[] = "amazon_order_id";
        $fields[] = "reference_url";
      }

      $testUnit->fill($request->only($fields));

      if(empty($testUnit->refunded) && $request->input('refunded') == 'on')
        $testUnit->statuses()->create([
          'status' => 3
        ]);

      $testUnit->refunded = $request->input('refunded') == 'on' ? 1 : 0;

      if($testUnit->status < 1 && ($testUnit->expires_on_time != trim($request->input('expires_on_time'))
          || $testUnit->expires_on_space != trim($request->input('expires_on_space'))))
      {
        $testUnit->expires_on_time = $request->input('expires_on_time');
        $testUnit->expires_on_space = $request->input('expires_on_space');
        $spaceSpecs = ['T%dS', 'T%dM', 'T%dH', '%dD', '%dW', '%dM', '%dY'];
        $expires_on = Carbon::now(config('app.timezone'));
        $expires_on->add(new \DateInterval(sprintf('P'.$spaceSpecs[$request->input('expires_on_space')], $request->input('expires_on_time'))));
        $testUnit->expires_on = $expires_on;
      } else
        $testUnit->expires_on = $testUnit->expires_on;

      if($testUnit->status < 1 && (empty($testUnit->tester) || $testUnit->tester->id != $request->input('tester_id')))
        $testUnit->tester()->associate(Tester::find($request->input('tester_id')));

      if(intval($testUnit->status) !== intval($request->input('status')))
        $testUnit->statuses()->create([
          'status' => $request->input('status')
        ]);

      $testUnit->status = $request->input('status');
      $testUnit->save();

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
