@extends('layouts.panel')

@section('title')
Ordini di lavoro
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('testOrders') }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    Ordini di lavoro
  </div>
  <div class="px-4 py-3">

    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    <form class="fakelink-get" data-action="{{ route("panel.testOrders.home") }}" method="get">
      <div class="row">
        <div class="input-group mb-3 col-sm-6">
            <input type="text" name="s" value="{{ Request::query("s") }}" class="form-control" placeholder="ASIN prodotto, ID negozio...">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="submit">
                <i class="fa fa-fw fa-search"></i>
              </button>
            </div>
        </div>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-condensed-sm">
        <thead class="thead-light">
          <tr>
            <th scope="col" class="p-2">
              @orderable('test_order.id', '#')
            </th>
            <th scope="col"></th>
            <th scope="col" class="p-2">
              @orderable('product.title', 'Nome prodotto')
            </th>
            <th scope="col" class="p-2">
              @orderable('store.name', 'Nome negozio')
            </th>
            <th scope="col" class="p-2">
              @orderable('test_order.created_at', 'Data creazione')
            </th>
            <th scope="col" class="p-2"></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($testOrders as $testOrder)
          <tr>
            <th class="align-middle" scope="row">{{ $testOrder->testOrder_id }}</th>
            <td class="align-middle">
              @php $product_images = json_decode($testOrder->product_images); @endphp
              <img style="min-width: 50px; max-height: 50px" src="@if(empty($product_images[0])) /images/package.svg @else{{ $product_images[0] }}@endif" class="img-fluid img-thumbnail rounded border">
            </td>
            <td class="align-middle">
              @if($testOrder->product_id)
              {{ $testOrder->product_name }}
              <a title="Vai al prodotto" href="{{ route('panel.products.view', $testOrder->product_id) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-external-link-alt"></i>
              </a>@else <i>Assente</i> @endif
            </td>
            <td class="align-middle">
              @if($testOrder->store_id)
                {{ $testOrder->store_name }}
                <a title="Vai al negozio" href="{{ route('panel.stores.view', ['store' => $testOrder->store_id]) }}" class="btn btn-sm btn-primary">
                  <i class="fa fa-external-link-alt"></i>
                </a>@else <i>Assente</i> @endif
            </td>
            <td class="align-middle">
              {{ $testOrder->testOrder_created_at }}
            </td>
            <td class="align-middle">
              <a href="{{ route('panel.testOrders.view', ['testOrder' => $testOrder->testOrder_id]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-fw fa-external-link-alt"></i> Visualizza
              </a>
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">
                <i>Non ci sono ordini di lavoro con questi criteri di ricerca.</i>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="float-right mb-2">
      Mostrando pagina {{ $testOrders->currentPage() }} di {{ $testOrders->lastPage() }}
    </div>
    {{ $testOrders->appends(request()->query())->links() }}
    <div class="clearfix"></div>
  </div>
@endsection
