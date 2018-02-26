@extends('layouts.panel')

@section('breadcrumb')
{{ Breadcrumbs::render('home') }}
@endsection

@section('content')
  <div class="px-4 py-3 m-0 h3 border-bottom">
    Ordini incompleti
  </div>
  <ul class="list-group list-group-flush">
    @forelse($testOrders as $order)
      <li class="list-group-item rounded-0">L'<a href="{{ route('panel.testOrders.view', $order->id) }}">ordine di lavoro #{{ $order->id }}</a> ha ancora {{ $order->quantity-$order->present }} unità di test da creare.</li>
    @empty
      <li class="list-group-item rounded-0 text-center"><i>Non ci sono ordini di lavoro da completare al momento!</i></li>
    @endforelse
  </ul>
@endsection
