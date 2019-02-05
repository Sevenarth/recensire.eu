<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TestOrder;
use App\TestUnit;
use App\Http\Requests\TestUnitFormRequest;
use Carbon\Carbon;
use App\Tester;
use DB;

class TestUnitsController extends Controller
{
    public function index(Request $request) {
      $orderBy = $request->query('orderBy', null);

      if(!in_array($orderBy, ['hash_code', 'test_order_id', 'tester_name', 'test_unit.status', 'test_unit.created_at']))
        $orderBy = null;
      $sort = $request->query('sort');
      if($sort != "asc" && $sort != "desc")
        $sort = "asc";
      $search = trim($request->query('s', null));

      $testUnits = DB::table('test_unit')
          ->leftJoin('tester', 'test_unit.tester_id', '=', 'tester.id');

      if(!empty($search))
        $testUnits = $testUnits->where(function($query) use($search) {
          $query->where("test_order_id", $search)
            ->orWhere("hash_code", $search)
            ->orWhere("amazon_order_id", 'like', '%'.$search.'%')
            ->orWhere("paypal_account", "like", '%'.$search.'%');
        });

      if(!empty($orderBy))
        $testUnits = $testUnits->orderBy($orderBy, $sort)->orderBy('test_unit.id', $sort);
      else
        $testUnits = $testUnits->orderBy('test_unit.created_at', 'desc')->orderBy('test_unit.id', 'desc');

      $testUnits = $testUnits
          ->select(
            "test_order_id",
            "test_unit.status",
            "tester.id as tester_id",
            "tester.name as tester_name",
            "tester.status as tester_status",
            "test_unit.id as id",
            "test_unit.created_at as created_at",
            "expires_on",
            "hash_code"
          )->paginate(15);

      return view("panel/testUnits/home", compact('testUnits'));
    }

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
      $count = $testOrder->testUnits()->where(function ($q) {
        $q->where('status', '>', 0)
        ->orWhere(function($q) {
          $q->where('status', 0)
            ->where('expires_on', '>', Carbon::now(config('app.timezone')));
        });
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

        if($request->input('starts_on_date'))
          $starts_on = new Carbon($request->input('starts_on_date'). " " . $request->input('starts_on_time'), config('app.timezone'));
        else
          $starts_on = Carbon::now(config('app.timezone'));

        $testUnit->starts_on = $starts_on->toDateTimeString();

        $testUnit->expires_on = $starts_on->add(new \DateInterval(sprintf('P'.$spaceSpecs[$request->input('expires_on_space')], $request->input('expires_on_time'))));
        $testUnit->status_updated_at = Carbon::now(config('app.timezone'));
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

      if($request->input('starts_on_date'))
        $starts_on = new Carbon($request->input('starts_on_date'). " " . $request->input('starts_on_time'), config('app.timezone'));
      else
        $starts_on = Carbon::now(config('app.timezone'));

      $testUnit->starts_on = $starts_on->toDateTimeString();

      $testUnit->expires_on = $starts_on->add(new \DateInterval(sprintf('P'.$spaceSpecs[$request->input('expires_on_space')], $request->input('expires_on_time'))));

      $testUnit->tester()->associate(Tester::find($request->input('tester_id')));
      $testUnit->testOrder()->associate($testOrder);
      $testUnit->status_updated_at = Carbon::now(config('app.timezone'));
      $testUnit->save();

      $testUnit->statuses()->create([
        'status' => $request->input('status')
      ]);

      if($testUnit->refunded)
        $testUnit->statuses()->create([
          'status' => 4
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
          'status' => 4
        ]);

      $testUnit->refunded = $request->input('refunded') == 'on' ? 1 : 0;

      $starts_on = new Carbon($request->input('starts_on_date')." ".$request->input('starts_on_time'), config('app.timezone'));
      if($testUnit->status < 1 &&
          ($testUnit->starts_on != $starts_on
          || $testUnit->expires_on_time != trim($request->input('expires_on_time'))
          || $testUnit->expires_on_space != trim($request->input('expires_on_space'))))
      {
        $testUnit->expires_on_time = $request->input('expires_on_time');
        $testUnit->expires_on_space = $request->input('expires_on_space');
        $spaceSpecs = ['T%dS', 'T%dM', 'T%dH', '%dD', '%dW', '%dM', '%dY'];

        if($request->input('starts_on_date'))
          $starts_on = new Carbon($request->input('starts_on_date'). " " . $request->input('starts_on_time'), config('app.timezone'));
        else
          $starts_on = Carbon::now(config('app.timezone'));

        $testUnit->starts_on = $starts_on->toDateTimeString();
        $testUnit->expires_on = $starts_on->add(new \DateInterval(sprintf('P'.$spaceSpecs[$request->input('expires_on_space')], $request->input('expires_on_time'))));
      } else {
        $testUnit->starts_on = $testUnit->starts_on;
        $testUnit->expires_on = $testUnit->expires_on;
      }

      if($testUnit->status < 1 && (empty($testUnit->tester) || $testUnit->tester->id != $request->input('tester_id')))
        $testUnit->tester()->associate(Tester::find($request->input('tester_id')));

      if(intval($testUnit->status) !== intval($request->input('status'))) {
        $testUnit->statuses()->create([
          'status' => $request->input('status')
        ]);
        $testUnit->status_updated_at = Carbon::now(config('app.timezone'));
      }

      $testUnit->status = $request->input('status');
      $testUnit->save();

      return redirect()
        ->route('panel.testUnits.view', $testUnit->id)
        ->with('status', 'Unità di test modificata con successo!');
    }

    public function delete(Request $request, TestUnit $testUnit) {
      $id = $testUnit->testOrder->id;
      $testUnit->delete();

      return redirect()
        ->route('panel.testOrders.view', $id)
        ->with('status', 'Unità di test eliminata con successo!');
    }

    public function renew(Request $request, TestUnit $testUnit) {
      $unit_new = $testUnit->replicate();
      $unit_new->hash_code = "placeholder";
      $unit_new->viewed = false;
      $unit_new->save();

      foreach($testUnit->statuses as $status) {
        $status->unit()->associate($unit_new);
        $status->save();
      }

      $testUnit->delete();

      return redirect()
        ->route('panel.testUnits.view', $unit_new->id)
        ->with('status', 'Unità di test rinnovata con successo!');
    }

    public function duplicate(Request $request, TestUnit $testUnit) {
      $unit_new = new TestUnit;
      $unit_new->hash_code = "placeholder";
      $unit_new->instructions = $testUnit->instructions;
      $unit_new->refunding_type = $testUnit->refunding_type;
      $unit_new->reference_url = $testUnit->reference_url;
      $unit_new->expires_on = $testUnit->expires_on;
      $unit_new->expires_on_space = $testUnit->expires_on_space;
      $unit_new->expires_on_time = $testUnit->expires_on_time;
      $unit_new->starts_on = $testUnit->starts_on;
      $unit_new->testOrder()->associate($testUnit->testOrder);
      $unit_new->save();
      $unit_new->statuses()->create([
        'status' => 0
      ]);

      return redirect()
        ->route('panel.testUnits.view', $testUnit->id)
        ->with('status', 'Duplicato creato con hash <b><a href="'.route('panel.testUnits.view', $unit_new->id).'">#'.$unit_new->hash_code.'</a></b>');
    }

    public function refunds() {
      $testUnits = DB::table('test_unit')
          ->leftJoin('tester', 'test_unit.tester_id', '=', 'tester.id')
          ->orWhere('status', 2)
          ->orWhere('status', 11)
          ->orWhere('status', 7)
          ->orderBy('test_unit.created_at', 'desc')
          ->orderBy('test_unit.id', 'desc');

      $testUnits = $testUnits
          ->select(
            "test_order_id",
            "test_unit.status",
            "tester.id as tester_id",
            "tester.name as tester_name",
            "tester.status as tester_status",
            "tester.profile_image as tester_image",
            "test_unit.id as id",
            "test_unit.created_at as created_at",
            "expires_on",
            "hash_code"
          )->get();
        
      return view('panel.refunds', compact('refunds'));
    }
}
