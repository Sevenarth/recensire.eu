@extends('layouts.panel')

@section('title')
@php if(!empty($store)) echo 'Modifica negozio #'.$store->id; else echo 'Nuovo negozio'; @endphp
@endsection

@section('breadcrumb')
@php echo !empty($store) ? Breadcrumbs::render('stores.edit', $store) : Breadcrumbs::render('stores.create') @endphp
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    @php echo !empty($seller) ? 'Modifica negozio #'.$seller->id : 'Nuovo negozio' @endphp
  </div>
  <div class="px-4 py-3">
    @if(!empty($seller))
    @openForm('panel.stores.update', 'patch', arg="store->id")
    @else
    @openForm('panel.stores.put', 'put')
    @endif
      @formTextfield('name', 'Nome negozio', placeholder="Casa e bricolage", editMode="store")
      @formTextfield('url', 'Link al negozio', placeholder="http://www.casaebricolage.it", required="false", editMode="store")
      <div class="row">
        <div class="col-sm-6">
        @formTextfield('company_name', 'Nome impresa', placeholder="ProdottiCasa spa", editMode="store")
        </div>
        <div class="col-sm-6">
        @formTextfield('company_registration_no', 'Numero registrazione impresa', required="false", placeholder="11214984", editMode="store")
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          @formTextfield('VAT', 'Partita IVA', placeholder="IT00000000000", required="false", editMode="store")
        </div>
        <div class="col-sm-6">
          @formTextfield('country', 'Paese di registrazione', placeholder="Italia", editMode="store")
        </div>
      </div>
      <fieldset class="form-group">
        <label for="seller_id">Venditore</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#select-seller" type="button">Cerca</button>
          </div>
          <input type="hidden" id="seller-id" name="seller_id">
          <input class="form-control{{ $errors->has('seller_id') ? ' is-invalid' : '' }}" id="seller-name" type="text" placeholder="Nessun venditore selezionato" required readonly>
          @if($errors->has('seller_id'))
          <div class="invalid-feedback">
            @php foreach($errors->get('seller_id') as $error) echo $error . "<br>"; @endphp
          </div>
          @endif
        </div>
      </fieldset>

      <button type="submit" class="mt-3 mb-2 btn btn-primary">@php echo !empty($store) ? 'Modifica negozio' : 'Aggiungi nuovo negozio' @endphp</button>
    @closeForm
  </div>
  <div id="select-seller" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 mb-3">
            <form class="json-request" data-event="select-seller" data-action="{{ route('panel.sellers.fetch' )}}" data-result="#seller-search-results">
              @csrf
              <fieldset class="form-group mb-0">
                <div class="input-group input-group-lg">
                  <input type="text" class="form-control" id="seller-search" name="s" placeholder="Ricerca venditore...">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-search"></i></button>
                  </div>
                </div>
              </fieldset>
            </form>
        </div>
        <div class="modal-content border-0">
            <ul class="list-group" id="seller-search-results">
            </ul>
        </div>
      </div>
  </div>
@endsection
