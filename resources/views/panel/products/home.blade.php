@extends('layouts.panel')

@section('title')
Prodotti
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('products') }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    <a href="{{ route('panel.products.create') }}"><button type="button" class="float-right btn btn-outline-primary d-none d-md-block">
      <i class="fa fa-fw fa-plus"></i>
      Nuovo prodotto
    </button></a>
    Prodotti
  </div>
  <div class="px-4 py-3">
    <a href="{{ route('panel.products.create') }}" class="mb-4 btn-block btn btn-outline-primary d-block d-md-none">
      <i class="fa fa-fw fa-plus"></i>
      Nuovo prodotto
    </a>

    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    <form class="fakelink-get" data-action="{{ route("panel.products.home") }}" method="get">
      <div class="row">
        <div class="input-group mb-3 col-sm-6">
            <input type="text" name="s" value="{{ Request::query("s") }}" class="form-control" placeholder="Marchio, nome prodotto, ASIN...">
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
              @orderable('id', '#')
            </th>
            <th scope="col" class="p-2">
              @orderable('brand', 'Marchio')
            </th>
            <th scope="col" class="p-2">
              @orderable('title', 'Nome')
            </th>
            <th scope="col" class="p-2">
              @orderable('ASIN', 'ASIN')
            </th>
            <th scope="col" class="p-2"></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($products as $product)
          <tr>
            <th class="align-middle" scope="row">{{ $product->id }}</th>
            <td class="align-middle">
              {{ $product->brand }}
            </td>
            <td class="align-middle">
              {{ $product->title }}
            </td>
            <td class="align-middle">
              {{ $product->ASIN }}
            </td>
            <td class="align-middle">
              <a href="{{ route('panel.products.view', ['product' => $product->id]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-fw fa-external-link-alt"></i> Visualizza
              </a>
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center">
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
    {{ $products->links() }}
    <div class="clearfix"></div>
</div>
@endsection
