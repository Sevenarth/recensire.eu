@extends('layouts.panel')

@section('title')
Testers
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('testers') }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    <a href="{{ route('panel.testers.create') }}"><button type="button" class="float-right btn btn-outline-primary d-none d-md-block">
      <i class="fa fa-fw fa-plus"></i>
      Nuovo tester
    </button></a>
    Testers
  </div>
  <div class="px-4 py-3">
    <a href="{{ route('panel.testers.create') }}" class="mb-4 btn-block btn btn-outline-primary d-block d-md-none">
      <i class="fa fa-fw fa-plus"></i>
      Nuovo tester
    </a>

    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif


    <div class="row">
      <form class="col-sm-6 fakelink-get" data-action="{{ route("panel.testers.home") }}" method="get">
        <div class="input-group mb-3">
          <input type="text" name="s" value="{{ Request::query("s") }}" class="form-control" placeholder="Nome, email, ID...">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">
              <i class="fa fa-fw fa-search"></i>
            </button>
          </div>
        </div>
      </form>
      <form class="col-sm-6" action="{{ route('panel.testers.import')}}" method="post" enctype="multipart/form-data">
        @method('put')
        @csrf
        <div class="input-group mb-3">
          <input type="file" accept=".csv" class="form-control{{ $errors->has('sheet') ? ' is-invalid' : '' }} p-1" name="sheet" id="sheet">
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Importa</button>
          </div>
          @if($errors->has('sheet'))
            <div class="invalid-feedback">
            @foreach($errors->get('sheet') as $err)
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
              @orderable('id', '#')
            </th>
            <th scope="col" class="p-2">
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
          @forelse ($testers as $tester)
          <tr>
            <th class="align-middle" scope="row">{{ $tester->id }}</th>
            <td class="align-middle">
              <img style="min-width: 50px; max-height: 50px" id="profile_image" src="@if(empty($tester->profile_image)) /images/profile_image.svg @else{{ $tester->profile_image }}@endif" class="img-fluid img-thumbnail rounded border">
            </td>
            <td class="align-middle">{{ $tester->name }}</td>
            <td class="align-middle">{{ !empty($tester->email) ? $tester->email : '-' }}</td>
            <td class="align-middle">
              <a href="{{ route('panel.testers.view', ['tester' => $tester->id]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-fw fa-external-link-alt"></i> Visualizza
              </a>
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">
                <i>Non ci sono testers con questi criteri di ricerca.</i>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="float-right mb-2">
      Mostrando pagina {{ $testers->currentPage() }} di {{ $testers->lastPage() }}
    </div>
    {{ $testers->links() }}
    <div class="clearfix"></div>
  </div>
@endsection
