@extends('layouts.panel')

@section('title')
Negozi
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('stores') }}
@endsection

@section('content')
    <div class="px-4 py-3 h3 border-bottom">
      <a href="{{ route('panel.stores.create') }}"><button type="button" class="float-right btn btn-outline-primary d-none d-md-block">
        <i class="fa fa-fw fa-plus"></i>
        Nuovo negozio
      </button></a>
      Negozi
    </div>
    <div class="px-4 py-3">
      <a href="{{ route('panel.stores.create') }}" class="mb-4 btn-block btn btn-outline-primary d-block d-md-none">
        <i class="fa fa-fw fa-plus"></i>
        Nuovo negozio
      </a>

      @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
      @endif


      <form class="fakelink-get" data-action="{{ route("panel.stores.home") }}" method="get">
        <div class="row">
          <div class="input-group mb-3 col-sm-6">
              <input type="text" name="s" value="{{ Request::query("s") }}" class="form-control" placeholder="Nome negozio, nome impresa, partita IVA...">
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
                @orderable('store.id', '#')
              </th>
              <th scope="col" class="p-2">
                @orderable('store.name', 'Nome negozio')
              </th>
              <th scope="col" class="p-2">
                @orderable('seller.name', 'Venditore')
              </th>
              <th scope="col" class="p-2">
                @orderable('reports.title', 'Reports')
              </th>
              <th scope="col" class="p-2"></th>
            </tr>
          </thead>
          <tbody>
            @forelse ($stores as $store)
            <tr>
              <th class="align-middle" scope="row">{{ $store->id }}</th>
              <td class="align-middle">
                {{ $store->name }}
                @if(!empty($store->url))
                  <a title="Apri il sito del negozio" target="_blank" rel="noreferrer nofollow" href="{{ $store->url }}" class="btn btn-sm btn-info">
                    <i class="fa fa-link"></i>
                  </a>
                @endif
              </td>
              <td class="align-middle">
                <a title="Visualizza" href="{{ route('panel.sellers.view', ['seller' => $store->seller_id]) }}"><img style="max-width: 50px; max-height: 50px" id="profile_image" src="@if(empty($store->profile_image)) /images/profile_image.svg @else{{ $store->profile_image }}@endif" class="img-fluid img-thumbnail rounded border"></a>
                   {{ !empty($store->seller_nickname) ? $store->seller_nickname : $store->seller_name }}
              </td>
              <td class="align-middle">
                {{ !empty($store->report_title) ? $store->report_title : 'No'}}
              </td>
              <td class="align-middle">
                <a href="{{ route('panel.stores.view', ['store' => $store->id]) }}" class="btn btn-sm btn-primary">
                  <i class="fa fa-fw fa-external-link-alt"></i> Visualizza
                </a>
              </td>
            </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center">
                  <i>Non ci sono negozi con questi criteri di ricerca.</i>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="float-right mb-2">
        Mostrando pagina {{ $stores->currentPage() }} di {{ $stores->lastPage() }}
      </div>
      {{ $stores->appends(request()->query())->links() }}
      <div class="clearfix"></div>
    </div>
@endsection
