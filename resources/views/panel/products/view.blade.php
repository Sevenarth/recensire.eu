@extends('layouts.panel')

@section('title')
Prodotto #{{ $product->id }}
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('products.view', $product) }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom d-none d-md-block">
    @openForm('panel.products.delete', 'delete', arg="product->id")
    <div class="btn-group float-right" role="group">
      <a href="{{route("panel.products.edit", $product->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a>
      <button type="submit" data-placement="bottom" class="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo prodotto?"><i class="fa fa-fw fa-times"></i> Elimina</button>
    </div>
    @closeForm
    Prodotto #{{ $product->id }}
  </div>
  <div class="px-4 py-3">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @openForm('panel.products.delete', 'delete', arg="product->id")
    <div class="btn-group text-center d-block d-md-none mb-4" role="group">
      <a href="{{route("panel.products.edit", $product->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a><button type="submit" class="remove-confirmation btn btn-danger" data-html="true" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo prodotto?"><i class="fa fa-fw fa-times"></i> Elimina</button>
    </div>
    @closeForm

    <div class="row">
      <div class="col-sm-3">
        <fieldset class="form-group">
          <label for="brand"><b>Marchio</b></label>
          <input type="text" readonly class="form-control-plaintext" name="brand" value="{{ $product->brand }}">
        </fieldset>
      </div>
      <div class="col-sm-9">
        <fieldset class="form-group">
          <label for="title"><b>Nome prodotto
          <a title="Apri su Amazon" target="_blank" href="{{ $product->URL }}" class="btn btn-sm btn-primary">
            <i class="fa fa-external-link-alt"></i>
          </a></b></label>
          <input type="text" readonly class="form-control-plaintext" name="title" value="{{ $product->title }}">
        </fieldset>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-2">
        <fieldset class="form-group">
          <label for="ASIN"><b>ASIN</b></label>
          <input type="text" readonly class="form-control-plaintext" name="ASIN" value="{{  $product->ASIN }}">
        </fieldset>
      </div>
      <div class="col-sm-5">
        <fieldset class="form-group">
          <label><b>Categorie</b></label>
          <div class="form-control">
          @forelse($product->categories as $cat)
            <span class="badge badge-secondary">{{ $cat->title }}</span>
          @empty
            <i>(nessuna categoria)</i>
          @endforelse
        </div>
        </fieldset>
      </div>
      <div class="col-sm-5">
        <fieldset class="form-group">
          <label><b>Etichette</b></label>
          <div class="form-control">
          @forelse($product->tags as $tag)
            <span class="badge badge-secondary">{{ $tag->name }}</span>
          @empty
            -
          @endforelse
        </div>
        </fieldset>
      </div>
    </div>
    <fieldset class="form-group">
      <label><b>Descrizione</b></label>
      <div class="form-control markdown" style="max-width: 300px: overflow-y: auto">{{ !empty($product->description) ?  $product->description : '*Nessuna descrizione inserita.*' }}</div>
    </fieldset>

    <label><b>Immagini</b></label>
    @php $images = $product->images; @endphp
    <div id="image-slideshow" class="mb-3 bg-secondary form-control carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        @forelse(array_keys($images) as $id)
        <li data-target="#image-slideshow" data-slide-to="{{ $id }}"{{ $id === 0 ? ' class="active"' : '' }}></li>
      @empty
        <li data-target="#image-slideshow" data-slide-to="0" class="active"></li>
      @endforelse
      </ol>
      <div class="carousel-inner">
        @forelse($images as $id => $image)
        <div class="text-center carousel-item{{ $id === 0 ? ' active' : '' }}">
          <img style="max-height: 300px" class="mw-100" src="{{ !empty($image) ? $image : '/images/package.svg' }}" alt="image-{{ $id+1 }}">
        </div>
        @empty
          <div class="text-center carousel-item active">
            <img style="max-height: 300px" class="mw-100" src="/images/package.svg" alt="default image">
          </div>
      @endforelse
      </div>
      <a class="carousel-control-prev" href="#image-slideshow" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Precedente</span>
      </a>
      <a class="carousel-control-next" href="#image-slideshow" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Successivo</span>
      </a>
    </div>
  </div>
@endsection
