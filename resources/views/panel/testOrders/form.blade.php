@extends('layouts.panel')

@section('title')
@php if(!empty($testOrder->id)) echo 'Modifica ordine di lavoro #'.$testOrder->id; else echo 'Nuovo ordine di lavoro'; @endphp
@endsection

@section('breadcrumb')
@php echo !empty($testOrder->id) ? Breadcrumbs::render('testOrders.edit', $testOrder) : Breadcrumbs::render('testOrders.create', $testOrder) @endphp
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    @php echo !empty($testOrder->id) ? 'Modifica ordine di lavoro #'.$testOrder->id : 'Nuovo ordine di lavoro' @endphp
  </div>
  <div class="px-4 py-3">
    @if(!empty($testOrder->id))
    @openForm('panel.testOrders.update', 'patch', arg="testOrder->id")
    @else
    <form action="{{ route('panel.testOrders.put', ['product' => $testOrder->product->id, 'store' => $testOrder->store->id]) }}" method="post">
      @method('put')
      @csrf
    @endif
      <fieldset class="form-group">
        <label for="store"><b>Negozio</b>
        @if(!empty($testOrder->store))<a title="Vai al negozio" href="{{ route('panel.stores.view', ['store' => $testOrder->store->id]) }}" class="btn btn-sm btn-primary">
          <i class="fas fa-external-link-alt"></i>
        </a>@endif</label>
        <input type="text" id="store" readonly class="form-control-plaintext" value="{{ !empty($testOrder->store) ? $testOrder->store->name : 'Negozio assente' }}">
      </fieldset>
      <fieldset class="form-group">
        <label for="product"><b>Prodotto</b>
        @if(!empty($testOrder->product))<a title="Vai al prodotto" href="{{ route('panel.products.view', $testOrder->product->id) }}" class="btn btn-sm btn-primary">
          <i class="fas fa-external-link-alt"></i>
        </a>@endif</label>
        <input type="text" id="product" readonly class="form-control-plaintext" value="{{ !empty($testOrder->product) ? $testOrder->product->title : 'Prodotto assente' }}">
      </fieldset>

      <fieldset class="form-group">
        <label for="fee">Commissione <small class="text-muted">(opzionale)</small></label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">&euro;</span>
          </div>
          <input class="form-control{{ $errors->has("fee") ? ' is-invalid' : '' }}" type="number" min="0.01" step="0.01" id="fee" name="fee" placeholder="3.00" value="{{ old('fee', !empty($testOrder->fee) ? $testOrder->fee : '') }}">
          @if($errors->has("fee"))
          <div class="invalid-feedback">
            @foreach($errors->get("fee") as $err)
              {{$err}}<br>
            @endforeach
          </div>
          @endif
        </div>
      </fieldset>
      @formTextfield('quantity', 'Numero di unità di test', placeholder="5", editMode="testOrder")
      <fieldset class="form-group">
        <a id="uploader" href="{{ route('panel.upload') }}" class="d-none"></a>
        <label for="description">Descrizione <small class="text-muted">(opzionale)</small></label>
        <textarea id="description" name="description">{{ old('description', $testOrder->description) }}</textarea>
      </fieldset>

      <button type="submit" class="mt-3 mb-2 btn btn-primary">@php echo !empty($testOrder->id) ? 'Modifica ordine di lavoro' : 'Crea nuovo ordine di lavoro' @endphp</button>
    @closeForm
  </div>
@endsection


@section('scripts')
<script>
$('#description').mde();
</script>
@endsection
