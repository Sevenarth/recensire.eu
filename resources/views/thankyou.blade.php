@extends('layouts.public')

@section('title') Invito di test accettato @endsection

@section('content')
  <div class="container">
    <h2 class="border-bottom pb-3 mb-3">Grazie mille!</h2>
    <div class="p-4 h4 alert-success">
      Grazie mille per avere accettato l'invito a partecipare a questa unità di test di un prodotto a noi affiliato. Verrai ricontattato dal nostro staff presto! Ricordati il codice di referenza per questo testing è <b>{{ Request::route('testUnit') }}</b>.
    </div>
@endsection
