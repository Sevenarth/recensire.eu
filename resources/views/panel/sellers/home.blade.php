@extends('layouts.panel')

@section('title')
Venditori
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('sellers') }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    <a href="{{ route('panel.sellers.create') }}"><button type="button" class="float-right btn btn-outline-primary d-none d-md-block">
      <i class="fa fa-fw fa-plus"></i>
      Nuovo venditore
    </button></a>
    Venditori
  </div>
  <div class="px-4 py-3">
    <a href="{{ route('panel.sellers.create') }}"><button type="button" class="mb-4 btn-block btn btn-outline-primary d-block d-md-none">
      <i class="fa fa-fw fa-plus"></i>
      Nuovo venditore
    </button></a>

    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif


    <form class="fakelink-get" data-action="{{ route("panel.sellers.home") }}" method="get">
      <div class="row">
        <div class="input-group mb-3 col-sm-6">
            <input type="text" name="s" value="{{ Request::query("s") }}" class="form-control" placeholder="Nome, email, ID...">
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
              @orderable('nickname', 'Pseudonimo')
            </th>
            <th scope="col" class="p-2">
              @orderable('name', 'Nome')
            </th>
            <th scope="col" class="p-2">
              @orderable('email', 'Indirizzo email')
            </th>
            <th scope="col" class="p-2"></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($sellers as $seller)
          <tr>
            <th class="align-middle" scope="row">{{ $seller->id }}</th>
            <td class="align-middle">@php echo !empty($seller->nickname) ? $seller->nickname : '-' @endphp</td>
            <td class="align-middle">{{ $seller->name }}</td>
            <td class="align-middle">{{ $seller->email}}</td>
            <td class="align-middle">
              <a href="{{ route('panel.sellers.view', ['seller' => $seller->id]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-fw fa-external-link-alt"></i> Visualizza
              </a>
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">
                <i>Non ci sono venditori con questi criteri di ricerca.</i>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="float-right mb-2">
      Mostrando pagina {{ $sellers->currentPage() }} di {{ $sellers->lastPage() }}
    </div>
    {{ $sellers->links() }}
    <div class="clearfix"></div>
  </div>
@endsection
