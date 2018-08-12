<?php
return [
  'statuses' => [
    0 => "In attesa",
    1 => "Acquistato",
    2 => "Recensito",
    7 => "Feedback",
    3 => "Completato",
    6 => "Banned",
    5 => "Annullato",
    8 => "Restituito",
    9 => "Saldato",
    4 => "Rimborsato"
  ],
  'englishStatuses' => [
    0 => "Waiting",
    1 => "Bought",
    2 => "Reviewed",
    7 => "Feedback",
    3 => "Completed",
    6 => "Banned",
    5 => "Cancelled",
    8 => "Returned",
    9 => "Settled",
    4 => "Refunded"
  ],
  'timeSpaces' => [
    'secondi',
    'minuti',
    'ore',
    'giorni',
    'settimane',
    'mesi',
    'anni'
  ],
  'refundingTypes' => [
    'PayPal + Fee dopo recensione',
    'PayPal + Fee dopo spedizione',
    'PayPal + Fee dopo ordine',
    'PayPal - Fee dopo recensione',
    'PayPal - Fee dopo spedizione',
    'PayPal - Fee dopo ordine'
  ],
  'reportFields' => [
    'amazon-order-no' => 'Amazon Order No.',
    'asin' => 'ASIN',
    'paypal-account' => 'PayPal Account',
    'refunded' => 'Refunded?',
    'refunded-amount' => 'Refunded Amount',
    'review-url' => 'Review URL',
    'state' => 'State',
    'tester-name' => 'Tester Name'
  ]
];
