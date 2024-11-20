<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutData extends Model
{
  use HasFactory;

  protected $fillable = [
    'event_id',
    'charge_id',
    'price',
    'tax',
    'commission',
    'quantity',
    'discount',
    'total_early_bird_dicount',
    'currencyText',
    'currencyTextPosition',
    'currencySymbol',
    'currencySymbolPosition',
    'fname',
    'lname',
    'email',
    'phone',
    'country',
    'state',
    'city',
    'zip_code',
    'address',
    'paymentMethod',
    'gatewayType',
    'paymentStatus',
  ];
}
