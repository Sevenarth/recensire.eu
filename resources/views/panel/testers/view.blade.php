@extends('layouts.panel')

@section('title')
Tester #{{ $tester->id }}
@endsection

@section('breadcrumb')
{{ Breadcrumbs::render('testers.view', $tester) }}
@endsection

@section('content')
  <div class="px-4 py-3 h3 border-bottom d-none d-md-block">
    @openForm('panel.testers.delete', 'delete', arg="tester->id")
    <div class="btn-group float-right" role="group">
      <a href="{{route("panel.testers.edit", $tester->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a>
      <button type="submit" data-placement="bottom" class="remove-confirmation btn btn-danger" data-html="true" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo tester?"><i class="fa fa-fw fa-times"></i> Elimina</button>
    </div>
    @closeForm
    Tester #{{ $tester->id }}
  </div>
  <div class="px-4 py-3">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
    @endif

    @openForm('panel.testers.delete', 'delete', arg="tester->id")
    <div class="btn-group text-center d-block d-md-none mb-4" role="group">
      <a href="{{route("panel.testers.edit", $tester->id)}}" class="btn btn-outline-primary"><i class="fa fa-fw fa-edit"></i> Modifica</a><button type="submit" class="remove-confirmation btn btn-danger" data-html="true" data-placement="bottom" data-toggle="popover" data-trigger="focus" title="Richiesta di conferma" data-content="Sei sicuro di voler eliminare questo tester?"><i class="fa fa-fw fa-times"></i> Elimina</button>
    </div>
    @closeForm

    <div class="row mb-xl-4">
      <div class="col-sm-3 mb-4 mb-sm-0 text-center">
        <img style="max-height: 190px" id="profile_image" src="@if(empty($tester->profile_image)) /images/profile_image.svg @else{{ $tester->profile_image }}@endif" class="img-fluid img-thumbnail rounded border">
      </div>
      <div class="col-sm-9">
        <div class="row">
          <div class="col-sm-6">
            <fieldset class="form-group">
              <label for="tester"><b>Nome tester</b></label>
              <input type="text" id="tester" readonly class="form-control-plaintext" value="{{ $tester->name }}">
            </fieldset>
          </div>
          <div class="col-sm-6">
            <fieldset class="form-group">
              <label for="email"><b>Indirizzo email</b></label>
              <input type="text" id="product" readonly class="form-control-plaintext" value="{{ !empty($tester->email) ? $tester->email : '-' }}">
            </fieldset>
          </div>
        </div>
        <fieldset class="form-group">
          <label for="wechat"><b><i class="fab fa-fw fa-weixin"></i> WeChat ID</b></label>
          <input type="text" id="wechat" readonly class="form-control-plaintext" value="{{ !empty($tester->wechat) ? $tester->wechat : '-' }}">
        </fieldset>
      </div>
    </div>
    <fieldset class="form-group">
      <label for="amazon_profiles"><b><i class="fab fa-fw fa-amazon"></i> Profili Amazon</b></label>
      @foreach($tester->amazon_profiles as $amz)
        @if(!empty($amz)) <div class="input-group mb-2"> @endif
        <input type="text" class="form-control{{ !empty($amz)?'':'-plaintext'}}" readonly value="{{ !empty($amz) ? $amz : '-' }}">
        @if(!empty($amz)) <div class="input-group-append">
          <a title="Apri link in una nuova pagina" class="btn btn-primary" href="{{ $amz }}" target="_blank"><i class="fa fa-fw fa-external-link-alt"></i></a>
        </div></div> @endif
      @endforeach
    </fieldset>
    <fieldset class="form-group">
      <label for="facebook_profiles"><b><i class="fab fa-fw fa-facebook-square"></i> Profili Facebook</b></label>
      @foreach($tester->facebook_profiles as $fb)
        @if(!empty($fb)) <div class="input-group mb-2"> @endif
        <input type="text" class="form-control{{ !empty($fb)?'':'-plaintext'}}" readonly value="{{ !empty($fb) ? "https://www.facebook.com/profile.php?id=".$fb : '-' }}">
        @if(!empty($fb)) <div class="input-group-append">
          <a title="Apri link in una nuova pagina" class="btn btn-primary" href="{{ "https://www.facebook.com/profile.php?id=".$fb }}" target="_blank"><i class="fa fa-fw fa-external-link-alt"></i></a>
        </div></div> @endif

      @endforeach
    </fieldset>

      <h5 class="my-4">Unit&agrave; di test</h5>

      <table class="table table-sm table-striped">
        <thead>
          <th>Indice</th>
          <th>Ordine di lavoro</th>
          <th>Ultimo stato</th>
          <th>Scadenza in</th>
          <th></th>
        </thead>
        <tbody>
          @foreach($testUnits as $unit)
          <tr>
            <th class="p-2" scope="row">{{ $unit->hash_code }}</th>
            <td><a href="{{ route('panel.testOrders.view', $unit->testOrder->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-external-link-alt"></i> Vai</a></td>
            <td class="p-2">{{ config('testUnit.statuses')[$unit->status] }}</td>
            <td class="p-2">
              @php $expiration = new \Carbon\Carbon($unit->expires_on, config('app.timezone')); @endphp
              @if($expiration->gt(\Carbon\Carbon::now(config('app.timezone'))))
              <div class="relative-time">{{ $expiration->toIso8601String() }}</div>
              @else
              <div class="text-danger"><b>Scaduto</b></div>
              @endif
            </td>
            <td>
              <a href="{{ route('panel.testOrders.testUnits.view', $unit->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-fw fa-external-link-alt"></i> Visualizza</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $testUnits->links() }}
    </div>
  </div>
</div>
@endsection
