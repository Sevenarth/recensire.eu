@extends('layouts.panel')

@section('title') Homepage @endsection

@section('breadcrumb')
{{ Breadcrumbs::render('home') }}
@endsection

@section('content')
  <div class="px-4 py-3 m-0 h3 border-bottom">
    Ordini incompleti
  </div>
  <ul class="my-2 list-group list-group-flush">
    @forelse($incomplete as $order)
      <li class="list-group-item rounded-0">L'<a href="{{ route('panel.testOrders.view', $order->id) }}">ordine di lavoro #{{ $order->id }}</a> ha ancora {{ $order->quantity-$order->present }} unità di test da creare.</li>
    @empty
      <li class="list-group-item rounded-0 text-center"><i>Non ci sono ordini di lavoro da completare al momento!</i></li>
    @endforelse
  </ul>
  <div class="px-4 py-3 mt-2 h3 border-bottom">
    Test accettati oggi
  </div>
  <div class="px-3 py-2">
    <table class="table table-striped">
      @forelse($acceptedToday as $status)
      <tr>
        <td class="p-2"><b>{{ $status->unit->hash_code }}</b> <a href="{{ route('panel.testOrders.testUnits.view', $status->unit->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2">{{ !empty($status->unit->tester) ? $status->unit->tester->name : '' }}  <a href="{{ route('panel.testers.view', !empty($status->unit->tester) ? $status->unit->tester->id : '') }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2 relative-time">{{ (new \Carbon\Carbon($status->created_at, config('app.timezone')))->toIso8601String() }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="3" class="p-2 text-center"><i>Non ci sono stati test accettati oggi.</i></td>
      </tr>
    @endforelse
    </table>
  </div>

  <div class="px-4 py-3 mt-2 h3 border-bottom">
    Test recensiti oggi
  </div>
  <div class="px-3 py-2">
    <table class="table table-striped">
      @forelse($reviewedToday as $status)
      <tr>
        <td class="p-2"><b>{{ $status->unit->hash_code }}</b> <a href="{{ route('panel.testOrders.testUnits.view', $status->unit->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2">{{ !empty($status->unit->tester) ? $status->unit->tester->name : '' }}  <a href="{{ route('panel.testers.view', !empty($status->unit->tester) ? $status->unit->tester->id : '') }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2 relative-time">{{ (new \Carbon\Carbon($status->created_at, config('app.timezone')))->toIso8601String() }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="3" class="p-2 text-center"><i>Non ci sono stati test recensiti oggi.</i></td>
      </tr>
    @endforelse
    </table>
  </div>

  <div class="px-4 py-3 mt-2 h3 border-bottom">
    Test rimborsati oggi
  </div>
  <div class="px-3 py-2">
    <table class="table table-striped">
      @forelse($refundedToday as $status)
      <tr>
        <td class="p-2"><b>{{ $status->unit->hash_code }}</b> <a href="{{ route('panel.testOrders.testUnits.view', $status->unit->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2">{{ !empty($status->unit->tester) ? $status->unit->tester->name : '' }}  <a href="{{ route('panel.testers.view', !empty($status->unit->tester) ? $status->unit->tester->id : '') }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2 relative-time">{{ (new \Carbon\Carbon($status->created_at, config('app.timezone')))->toIso8601String() }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="3" class="p-2 text-center"><i>Non ci sono stati test rimborsati oggi.</i></td>
      </tr>
    @endforelse
    </table>
  </div>

  <div class="px-4 py-3 mt-2 h3 border-bottom">
    Test recensiti oggi
  </div>
  <div class="px-3 py-2">
    <table class="table table-striped">
      @forelse($reviewedToday as $status)
      <tr>
        <td class="p-2"><b>{{ $status->unit->hash_code }}</b> <a href="{{ route('panel.testOrders.testUnits.view', $status->unit->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2">{{ !empty($status->unit->tester) ? $status->unit->tester->name : '' }}  <a href="{{ route('panel.testers.view', !empty($status->unit->tester) ? $status->unit->tester->id : '') }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i></a></td>
        <td class="p-2 relative-time">{{ (new \Carbon\Carbon($status->created_at, config('app.timezone')))->toIso8601String() }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="3" class="p-2 text-center"><i>Non ci sono stati test recensiti oggi.</i></td>
      </tr>
    @endforelse
    </table>
  </div>
@endsection
