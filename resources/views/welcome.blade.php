<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="referrer" content="no-referrer">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
    <style type="text/css">
    	#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
    </style>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-white">
  <!-- Begin MailChimp Signup Form -->
  <div id="mc_embed_signup" class="container">
    <img src="/images/logo.svg" alt="logo" class="mx-4 mt-4 mb-2 img-fluid">
      <a href="{{ route('contactus') }}" class="btn btn-info float-right mx-4 mt-4 mb-2">Contattaci</a>
  <form action="https://comprovato.us17.list-manage.com/subscribe/post?u=af18c1f69b2e28a2be014a413&id=7c34b249d6" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
      <div id="mc_embed_signup_scroll">
  	<h2>Se sei un recensore e vorresti ricevere i nostri prodotti, lascia i tuoi contatti compilando il modulo qui sotto.</h2>
  <div class="indicates-required"><span class="asterisk">*</span> indica campo obbligatorio</div>
  <div class="mc-field-group">
  	<label for="mce-EMAIL">Indirizzo email  <span class="asterisk">*</span>
  </label>
  	<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
  </div>
  <div class="mc-field-group">
  	<label for="mce-FNAME">Nome  <span class="asterisk">*</span>
  </label>
  	<input type="text" value="" name="FNAME" class="required" id="mce-FNAME">
  </div>
  <div class="mc-field-group">
  	<label for="mce-LNAME">Cognome  <span class="asterisk">*</span>
  </label>
  	<input type="text" value="" name="LNAME" class="required" id="mce-LNAME">
  </div>
  <div class="mc-field-group">
  	<label for="mce-MMERGE5">Collegamento profilo Amazon  <span class="asterisk">*</span>
  </label>
  	<input type="url" value="" name="MMERGE5" class="required url" id="mce-MMERGE5">
  </div>
  	<div id="mce-responses" class="clear">
  		<div class="response" id="mce-error-response" style="display:none"></div>
  		<div class="response" id="mce-success-response" style="display:none"></div>
  	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
      <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_af18c1f69b2e28a2be014a413_7c34b249d6" tabindex="-1" value=""></div>
      <div class="clear"><input type="submit" value="Sottoscriviti" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
      </div>
  </form>
  </div>
  <!--End mc_embed_signup-->

      <!-- Scripts -->
      <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[5]='MMERGE5';ftypes[5]='url';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
  </body>
  </html>
