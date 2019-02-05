@extends('layouts.panel')

@section('title')
Rimborsi
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('refunds') }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    Rimborsi
  </div>
  <div class="px-4 py-3">

    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif
    <div class="table-responsive">
      <table class="table table-condensed-sm">
        <thead class="thead-light">
          <tr>
            <th scope="col" class="p-2">
              Venditore
            </th>
            <th scope="col"></th>
            <th scope="col" class="p-2">
              No Ordine
            </th>
            <th scope="col"></th>
            <th scope="col" class="p-2">
              Nome tester
            </th>
            <th scope="col" class="p-2">
              Stato
            </th>
            <th scope="col" class="p-2">
              Da
            </th>
          </tr>
        </thead>
        <tbody>
          @forelse ($testUnits as $testUnit)
          <tr>
            <th class="align-middle" scope="row">
                @if(!empty($testUnit->test_order_id))
                @php $seller = \App\TestOrder::find($testUnit->test_order_id)->store->seller; @endphp
                <a href="{{ route('panel.sellers.view', $seller->id) }}" title="Vai al venditore">
                    {{ $seller->nickname }}
                </a>
                @else
                -
                @endif
            </th>
            <td class="align-middle">
              @if($testUnit->test_order_id)
              <a title="Vai all'ordine di lavoro" href="{{ route('panel.testOrders.view', $testUnit->test_order_id) }}">
              @php $product = \App\TestOrder::find($testUnit->test_order_id)->product; @endphp
              <img style="min-width: 70px; max-height: 70px" src="@if(empty($product->images[0])) /images/package.svg @else{{ $product->images[0] }}@endif" class="img-fluid img-thumbnail rounded border">
              </a>
              @endif
            </td>
            <td class="align-middle">
              @if($testUnit->test_order_id)
              {{ $testUnit->test_order_id }}@else <i>Assente</i> @endif
              <a title="Vai all'unità test" href="{{ route('panel.testUnits.view', $testUnit->id) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-external-link-alt"></i>
              </a>
            </td>
            <td class="align-middle">
            <img style="min-width: 70px; max-height: 70px" src="@if(empty($testUnit->tester_image)) /images/profile_image.svg @else{{ $testUnit->tester_image }}@endif" class="img-fluid img-thumbnail rounded border">
            </td>
          <td class="align-middle {{ $testUnit->tester_status ? 'tester-status-' . $testUnit->tester_status : "" }}">
              @if($testUnit->tester_id)
                {{ $testUnit->tester_name }}
                <a title="Vai al tester" href="{{ route('panel.testers.view', ['tester' => $testUnit->tester_id]) }}" class="btn btn-sm btn-primary">
                  <i class="fa fa-external-link-alt"></i>
                </a>@else <i>Assente</i> @endif
            </td>
            <td class="align-middle">
                {{ config('testUnit.statuses')[$testUnit->status] }}
            </td>
            <td class="align-middle">
              {{ \Carbon\Carbon::today(config('app.timezone'))->endOfDay()->diffInDays(new \Carbon\Carbon($testUnit->created_at, config('app.timezone'))) }} giorni
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center">
                <i>Non ci sono unità di test da rimborsare.</i>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
