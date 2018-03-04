@extends('layouts.panel')

@section('title')
Prodotti di {{ $store->name }}
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('stores.products', $store) }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    Prodotti di {{ $store->name }}
  </div>
  <div class="px-4 py-3">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

      <div class="row">
        <form class="fakelink-get col-sm-6" data-action="{{ route("panel.stores.products", $store->id) }}" method="get">
          <div class="input-group mb-3">
              <input type="text" name="s" value="{{ Request::query("s") }}" class="form-control" placeholder="Marchio, nome prodotto, ASIN...">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">
                  <i class="fa fa-fw fa-search"></i>
                </button>
              </div>
          </div>
        </form>

        <form class="col-sm-6" action="{{ route('panel.stores.attachProduct', $store)}}" method="post">
          @method('put')
          @csrf()
          <div class="input-group mb-3">
              <input type="text" class="form-control{{ $errors->has('product_id') ? ' is-invalid' : '' }}" name="product_id" placeholder="Amazon ASIN del prodotto">
              <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Associa</button>
              </div>
              @if($errors->has('product_id'))
              <div class="invalid-feedback d-block">
                @foreach($errors->get('product_id') as $err)
                  {{ $err }}<br>
                @endforeach
              </div>
              @endif
          </div>
        </form>
      </div>

    <div class="table-responsive">
      <table class="table table-condensed-sm">
        <thead class="thead-light">
          <tr>
            <th scope="col" class="p-2">
              @orderable('brand', 'Marchio') -
              @orderable('title', 'Nome')
            </th>
            <th scope="col" class="p-2">
              @orderable('ASIN', 'ASIN')
            </th>
            <th scope="col" class="p-2">
              Ordini di lavoro
            </th>
            <th scope="col" class="p-2"></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($products as $product)
          <tr>
            <td class="align-middle">
              <b>{{ $product->brand }}</b>
              {{ $product->title }}
              <a href="{{ route('panel.products.view', ['product' => $product->id]) }}" title="Apri prodotto" class="btn btn-sm btn-primary">
                <i class="fa fa-fw fa-external-link-alt"></i>
              </a>
            </td>
            <td class="align-middle">
              {{ $product->ASIN }}
            </td>
            <td class="align-middle">
              {{ $product->storeTestOrders($store)->count() }}
              <a title="Vai agli ordini di lavoro" href="{{ route('panel.testOrders.home') }}?s={{ urlencode(':product='.$product->id.',store='.$store->id) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-external-link-alt"></i>
              </a>
            </td>
            <td class="align-middle">
              <form action="{{ route('panel.stores.detachProduct', ['store' => $store, 'product' => $product]) }}" method="post">
                @method('delete')
                @csrf()
                <div class="btn-group">
                  <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fas fa-unlink"></i> Disassocia
                  </button>
                  <a href="{{ route('panel.testOrders.create', ['store' => $store, 'product' => $product]) }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-bug"></i> Crea ordine
                  </a>
                </div>
              </form>
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">
                <i>Non ci sono prodotti con questi criteri di ricerca.</i>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="float-right mb-2">
      Mostrando pagina {{ $products->currentPage() }} di {{ $products->lastPage() }}
    </div>
    {{ $products->appends(request()->query())->links() }}
    <div class="clearfix"></div>
</div>
@endsection
