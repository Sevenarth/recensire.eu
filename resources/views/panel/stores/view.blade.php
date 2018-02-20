@extends('layouts.panel')

@section('title')
Negozio #{{ $store->id }}
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('stores.view', $store) }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom d-none d-md-block">
    @openForm('panel.stores.delete', 'delete', arg="store->id")
    <div class="btn-group float-right" role="group">
      <a href="{{route("panel.stores.edit", $store->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a>
      <button type="submit" data-placement="bottom" class="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo negozio?"><i class="fa fa-fw fa-times"></i> Elimina</button>
    </div>
    @closeForm
    Negozio #{{ $store->id }}
  </div>
  <div class="px-4 py-3">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @openForm('panel.stores.delete', 'delete', arg="store->id")
    <div class="btn-group text-center d-block d-md-none mb-4" role="group">
      <a href="{{route("panel.stores.edit", $store->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a><button type="submit" class="remove-confirmation btn btn-danger" data-html="true" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo negozio?"><i class="fa fa-fw fa-times"></i> Elimina</button>
    </div>
    @closeForm

    <div class="row">
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label for="name"><b>Nome negozio
          @if(!empty($store->url))
            <a title="Apri il sito del negozio" target="_blank" href="{{ $store->url }}" class="btn btn-sm btn-primary">
              <i class="fa fa-external-link-alt"></i>
            </a>
          @endif</b></label>
          <input type="text" readonly class="form-control-plaintext" name="name" value="{{ $store->name }}">
        </fieldset>
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label for="name"><b>Venditore
          <a title="Visualizza" href="{{ route('panel.sellers.view', ['seller' => $store->seller->id]) }}" class="btn btn-sm btn-primary">
            <i class="fa fa-user"></i>
          </a></b></label>
          <input type="text" readonly class="form-control-plaintext" name="name" value="{{ $store->seller->name }}">
        </fieldset>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label for="company_name"><b>Nome impresa</b></label>
          <input type="text" readonly class="form-control-plaintext" name="company_name" value="{{  $store->company_name }}">
        </fieldset>
      </div>
      <div class="col-sm-6">
        <fieldset class="form-group">
          <label for="company_registration_no"><b>Numero registrazione impresa</b></label>
          <input type="text" readonly class="form-control-plaintext" name="company_registration_no" value="{{ (!empty($store->company_registration_no)) ? $store->company_registration_no : '-' }}">
        </fieldset>
      </div>
    </div>
    <fieldset class="form-group">
      <label for="VAT"><b>Partiva IVA</b></label>
      <input type="text" readonly class="form-control-plaintext" name="VAT" value="{{ !empty($store->VAT)? $store->VAT : '-' }}">
    </fieldset>

    <div class="h5">
      Prodotti
    </div>

    <div class="table-responsive">
      <table class="table table-condensed-sm">
        <thead class="thead-light">
          <tr>
            <th scope="col" class="p-2">#</th>
            <th scope="col" class="p-2">Nome prodotto</th>
            <th scope="col" class="p-2"></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($products as $product)
          <tr>
            <th class="align-middle" scope="row">{{ $product->id }}</th>
            <td class="align-middle">
              {{ $product->name }}
              @if(!empty($product->url))
                <a title="Vai al prodotto su Amazon" target="_blank" href="{{ $product->url }}" class="btn btn-sm btn-primary">
                  <i class="fa fa-external-link-alt"></i>
                </a>
              @endif
            </td>
            <td class="align-middle">
              <a href="{{ route('panel.products.view', ['product' => $product->id]) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-fw fa-external-link-alt"></i> Visualizza
              </a>
            </td>
          </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">
                <i>Questo negozio non ha prodotti al momento.</i>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $products->links() }}
  </div>
@endsection
