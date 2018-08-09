@extends('layouts.panel')

@section('title')
@if(!empty($cat->id)) Modifica categoria #{{ $cat->id }} @else Nuova categoria @endif
@endsection

@section('breadcrumb')
@if(!empty($cat->id)) {{ Breadcrumbs::render('categories.edit', $cat) }} @else {{ Breadcrumbs::render('categories.create') }} @endif
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom">
    @if(!empty($cat->id)) Modifica categoria #{{ $cat->id }} @else Nuova categoria @endif
  </div>
  <div class="px-4 py-3">
    @if(!empty($cat->id))
    @openForm('panel.categories.update', 'patch', arg="cat->id")
    @else
    @openForm('panel.categories.put', 'put')
    @endif

    @formTextfield('title', 'Nome categoria', placeholder="Tecnologia", editMode="cat")

    <fieldset class="form-group">
      <label for="description">Descrizione <small class="text-muted">(opzionale)</small></label>
      <textarea id="description" name="description">{{ old('description', $cat->originalDescription()) }}</textarea>
    </fieldset>

    <fieldset class="form-group">
      <label for="categories">Categoria madre</label>
      <select class="form-control" name="parent_id">
        <option value=""{{ !empty(old('parent_id', $cat->parent_id)) ? '' : ' selected' }}>(nessuna categoria)</option>
        @foreach($cats as $cat_)
        <option value="{{ $cat_->id }}"{{ old('categories', $cat->parent_id) == $cat_->id ? " selected" : "" }}>{!! $cat_->title !!}</option>
        @endforeach
      </select>
      <small class="text-muted">Seleziona pi&ugrave; categorie tenendo premuto <code class="border rounded p-1">Ctrl</code> su PC o <code class="h6 border rounded">&#8984;</code> su Mac.</small>
    </fieldset>

    <button type="submit" class="mt-3 mb-2 btn btn-primary">@if(!empty($cat->id)) Modifica categoria @else Aggiungi nuova categoria @endif</button>
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
</script>
@endsection
