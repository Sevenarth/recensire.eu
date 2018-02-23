@extends('layouts.panel')

@section('title')
@if(!empty($product->id)) Modifica prodotto #{{ $product->id }} @else Nuovo prodotto @endif
@endsection

@section('breadcrumb')
@if(!empty($product->id)) {{ Breadcrumbs::render('products.edit', $product) }} @else {{ Breadcrumbs::render('products.create') }} @endif
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    @if(!empty($product->id)) Modifica prodotto #{{ $product->id }} @else Nuovo prodotto @endif
  </div>
  <div class="px-4 py-3">
    @if(!empty($product->id))
    @openForm('panel.products.update', 'patch', arg="product->id")
    @else
    @openForm('panel.products.put', 'put')
    @endif
    @formTextfield('title', 'Nome prodotto', placeholder="Galaxy S8 64GB Orchid Grey", editMode="product")
    <div class="row">
      <div class="col-sm-6">
        @formTextfield('brand', 'Marchio', placeholder="Samsung", editMode="product")
      </div>
      <div class="col-sm-6">
        @formTextfield('ASIN', 'Amazon ASIN', placeholder="A000AAAAA0", editMode="product")
      </div>
    </div>
    @formTextfield('URL', 'Link al prodotto', placeholder="https://www.amazon.it/dp/A000AAAAAA0/", editMode="product")

    <fieldset class="form-group">
      <label for="description">Descrizione <small class="text-muted">(opzionale)</small></label>
      <textarea id="description" name="description">{{ old('description', $product->description) }}</textarea>
    </fieldset>

    <fieldset class="form-group">
      <label for="categories">Categorie <small class="text-muted">(opzionale)</small></label>
      <select multiple="multiple" class="form-control" style="min-height: 150px" name="categories[]">
        <option value=""{{ count(old('categories', $product->catsIds())) > 0 ? '' : ' selected' }}>(nessuna categoria)</option>
        @foreach($catsTree as $cat)
        <option value="{{ $cat->id }}"{{ in_array($cat->id, old('categories', $product->catsIds())) ? " selected" : "" }}>{!! $cat->title !!}</option>
        @endforeach
      </select>
      <small class="text-muted">Seleziona pi&ugrave; categorie tenendo premuto <code class="border rounded p-1">Ctrl</code> su PC o <code class="h6 border rounded">&#8984;</code> su Mac.</small>
    </fieldset>

    <fieldset class="form-group">
      <label for="tags">Etichette <small class="text-muted"><i class="fas fa-fw fa-tags"></i> (opzionale)</small></label>

      <input type="text" id="tags" name="tags" data-role="tagsinput">
      @if($errors->has('tags'))
      <div class="invalid-feedback">
        @foreach ($errors->get('tags') as $err)
          {{ $err }}<br>
        @endforeach
      </div>
      @endif
      <small class="text-muted">Scrivi le etichette separate da una virgola. Esempio: tecnologia,smartphone,nextgen</small>
    </fieldset>

    <fieldset class="form-group">
      <label class="mb-2">Immagini <small class="text-muted">(opzionale)</small></label>
      <div class="row no-gutters justify-content-center align-middle rounded border p-3 tab-content" id="images-box" data-page="{{ route("panel.upload") }}" data-quantity="1">

          <div id="image-1-wrapper" class="col-3 my-2 px-3">
            <img id="image-1" src="{{ old('images.0', (!empty($product->images[0])) ? $product->images[0] : '/images/package.svg') }}" class="img-fluid rounded border image-field">
          </div>
          <div id="image-1-box" class="image-box col-9 d-none">
            <div class="rounded border px-3 py-3">
              <fieldset class="form-group">
                <label for="image-1-field">Link immagine</label>
                <input type="text" name="images[]" id="image-1-field" data-target="image-1" value="{{ old('images.0', !empty($product->images[0]) ? $product->images[0] : '') }}" placeholder="http://" class="image-field-input form-control">
              </fieldset>
              <div class="btn-group">
                <button class="btn btn-primary upload-imageBox" data-target="image-1" type="button">Carica immagine</button>
              </div>
            </div>
          </div>

          @if(count(old('images', $product->images)) > 1)
          @for ($i=1; $i < count(old('images', $product->images)); $i++)
            <div id="image-{{ $i+1 }}-wrapper" class="col-3 my-2 px-3">
              <img id="image-{{ $i+1 }}" src="{{ old('images.'.$i, !empty($product->images[$i]) ? $product->images[$i] : '/images/package.svg') }}" class="img-fluid rounded border image-field">
            </div>
            <div id="image-{{ $i+1 }}-box" class="image-box col-9 d-none">
              <div class="rounded border px-3 py-3">
                <fieldset class="form-group">
                  <label for="image-{{ $i+1 }}-field">Link immagine</label>
                  <input type="text" name="images[]" id="image-{{ $i+1 }}-field" data-target="image-{{ $i+1 }}" value="{{ old('images.'.$i, !empty($product->images[$i]) ? $product->images[$i] : '') }}" placeholder="http://" class="image-field-input form-control">
                </fieldset>
                <div class="btn-group">
                  <button class="btn btn-primary upload-imageBox" data-target="image-{{ $i+1 }}" type="button">Carica immagine</button>
                  <button type="button" class="btn btn-danger image-remove" data-target="image-{{ $i+1 }}">Elimina immagine</button>
                </div>
              </div>
            </div>
          @endfor
          @endif

          <div title="Aggiungi nuova immagine" id="image-add-wrapper" class="col-3 my-2 px-3">
            <svg id="image-add" viewBox="0 0 150 150" class="btn btn-outline-secondary p-0 rounded border d-block img-fluid image-field svg-outline-secondary">
              <g transform="translate(-11.341498,-79.714325)" id="layer1">
                <path id="rect819" transform="scale(0.26458333)" d="m 298.30859,463.04688 c -4.50856,-10e-6 -8.13867,3.62815 -8.13867,8.13671 v 77.40821 h -78.75586 c -3.37499,0 -6.09179,2.7168 -6.09179,6.09179 v 60.12696 c 0,3.37499 2.7168,6.09179 6.09179,6.09179 h 78.75586 v 77.40821 c 0,4.50856 3.63011,8.13672 8.13867,8.13672 h 56.04297 c 4.50856,0 8.13672,-3.62816 8.13672,-8.13672 v -77.40821 h 78.75781 c 3.375,0 6.0918,-2.7168 6.0918,-6.09179 v -60.12696 c 0,-3.37499 -2.7168,-6.09179 -6.0918,-6.09179 h -78.75781 v -77.40821 c 0,-4.50856 -3.62816,-8.13671 -8.13672,-8.13671 z" style="opacity:1;vector-effect:none;fill-opacity:1;fill-rule:evenodd;stroke-width:1.19450116;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal" />
              </g>
            </svg>
          </div>
      </div>
      <small class="text-muted">L'ordine della mostra delle immagini corrisponde all'ordine qui visto. Dal primo all'ultimo elemento.</small>
    </fieldset>

    <button type="submit" class="mt-3 mb-2 btn btn-primary">@if(!empty($product->id)) Modifica prodotto @else Aggiungi nuovo prodotto @endif</button>
  </form>
  </div>
@endsection

@section('scripts')
<script>
var simplemde = new SimpleMDE({
  element: $("#description")[0],
  spellChecker: false,
  status: false
});

var tags = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  remote: {
    wildcard: '%QUERY',
    url: '{{ route('panel.products.tags') }}?s=%QUERY',
    transform: function (data) {
      return $.map(data, function (tag) {
          return {
              name: tag.name
          };
      });
    }
  }
});

tags.initialize();

$('#tags').tagsinput({
  tagClass: 'badge',
  typeaheadjs: {
    name: 'tags',
    displayKey: 'name',
    valueKey: 'name',
    source: tags.ttAdapter()
  }
});

$('#tags').tagsinput('add', '{{ old('tags', $product->inlineTags()) }}');
</script>
@endsection
