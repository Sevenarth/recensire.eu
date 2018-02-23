@extends('layouts.panel')

@section('title')
Categorie
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('categories') }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    <a href="{{ route('panel.categories.create') }}"><button type="button" class="float-right btn btn-outline-primary d-none d-md-block">
      <i class="fa fa-fw fa-plus"></i>
      Nuova categoria
    </button></a>
    Categorie
  </div>
  <div class="px-4 py-3">
    <a href="{{ route('panel.categories.create') }}" class="mb-4 btn-block btn btn-outline-primary d-block d-md-none">
      <i class="fa fa-fw fa-plus"></i>
      Nuova categoria
    </a>

    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div>
      <ul class="list-group list-group-flush">
        @forelse($cats as $cat)
        <li class="list-group-item px-3 py-2" style="font-size: 18px">
        @openForm('panel.categories.delete', 'delete', arg="cat->id")
          <div class="float-right btn-group">
            <a href="{{ route('panel.categories.edit', $cat->id) }}" class="btn-outline-primary btn-sm btn"><i class="fas fa-fw fa-edit"></i> Modifica</a>
            <button type="submit" data-placement="bottom" class="remove-confirmation btn btn-sm btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questa categoria?"><i class="fa fa-fw fa-times"></i> Elimina</button>
          </div>
          @closeForm
          {!! $cat->title !!}
        </li>
        @empty
        <li class="list-group-item text-center"><i>Non ci sono categorie nel sistema.</i></li>
        @endif
      </ul>
    </div>
  </div>
@endsection
