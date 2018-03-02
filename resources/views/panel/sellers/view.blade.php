@extends('layouts.panel')

@section('title')
Venditore #{{ $seller->id }}
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('sellers.view', $seller) }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom d-none d-md-block">
    @openForm('panel.sellers.delete', 'delete', arg="seller->id")
    <div class="btn-group float-right" role="group">
      <a href="{{route("panel.sellers.edit", $seller->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a>
      <button type="submit" data-placement="bottom" class="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo venditore?"><i class="fa fa-fw fa-times"></i> Elimina</button>
    </div>
    @closeForm
    Venditore #{{ $seller->id }}
  </div>
  <div class="px-4 py-3">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @openForm('panel.sellers.delete', 'delete', arg="seller->id")
    <div class="btn-group text-center d-block d-md-none mb-4" role="group">
      <a href="{{route("panel.sellers.edit", $seller->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a><button type="submit" class="remove-confirmation btn btn-danger" data-html="true" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo venditore?"><i class="fa fa-fw fa-times"></i> Elimina</button>
    </div>
    @closeForm

    <div class="row mb-xl-4">
      <div class="col-sm-3 mb-4 mb-sm-0 text-center">
        <img style="max-height: 190px" id="profile_image" src="@if(empty($seller->profile_image)) /images/profile_image.svg @else{{ $seller->profile_image }}@endif" class="img-fluid img-thumbnail rounded border">
      </div>
      <div class="col-sm-9">
        <fieldset class="form-group">
          <label for="nickname"><b>Pseudonimo</b></label>
          <input type="text" readonly class="form-control-plaintext" name="nickname" value="{{ (!empty($seller->nickname)) ? $seller->nickname : '-' }}">
        </fieldset>
        <div class="row">
          <div class="col-sm-6">
            <fieldset class="form-group">
              <label for="name"><b>Nome venditore</b></label>
              <input type="text" readonly class="form-control-plaintext" name="name" value="{{  $seller->name }}">
            </fieldset>
          </div>
          <div class="col-sm-6">
            <fieldset class="form-group">
              <label for="email"><b>Indirizzo email</b></label>
              <input type="text" readonly class="form-control-plaintext" name="email" value="{{  $seller->email }}">
            </fieldset>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label for="facebook"><b>Facebook ID</b></label>
          <input type="text" readonly class="form-control-plaintext" name="facebook" value="{{ !empty($seller->facebook)? 'https://www.facebook.com/profile.php?id='.$seller->facebook : '-' }}">
        </fieldset>
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label for="wechat"><b>WeChat ID</b></label>
          <input type="text" readonly class="form-control-plaintext" name="wechat" value="{{ !empty($seller->wechat)? $seller->wechat : '-' }}">
        </fieldset>
      </div>
    </div>

    <fieldset class="form-group">
      <label><b>Note</b></label>
      <div class="markdown form-control">{{ !empty($seller->notes) ? $seller->notes : 'N/D' }}</div>
    </fieldset>

    <div class="h5">
      Negozi
    </div>

    <div class="table-responsive">
      <table class="table table-condensed-sm">
        <thead class="thead-light">
          <tr>
            <th scope="col" class="p-2">#</th>
            <th scope="col" class="p-2">Nome negozio</th>
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
                <a title="Apri il sito del negozio" target="_blank" href="{{ $store->url }}" class="btn btn-sm btn-primary">
                  <i class="fa fa-external-link-alt"></i>
                </a>
              @endif
            </td>
            <td class="align-middle">
              <a href="{{ route('panel.stores.view', ['store' => $store->id]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-fw fa-external-link-alt"></i> Visualizza
              </a>
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">
                <i>Questo venditore non ha negozi al momento.</i>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $stores->links() }}
  </div>
@endsection
