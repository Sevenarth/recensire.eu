<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    protected $table = "store";
    protected $fillable = ['name', 'company_name', 'company_registration_no', 'url', 'VAT', 'country', 'seller_id', 'to_emails', 'bcc_emails', 'report_id'];
    protected $casts = [
      'to_emails' => 'array',
      'bcc_emails' => 'array'
    ];

    public function seller() {
      return $this->belongsTo('App\Seller');
    }

    public function products() {
      return $this->belongsToMany('App\Product', 'store_product', 'store_id', 'product_id');
    }

    public function report() {
      return $this->belongsTo(Report::class);
    }
}
