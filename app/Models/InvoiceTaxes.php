<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceTaxes extends Model{

  protected $fillable = ['tax_name','chagre_percent', 'chagre_amount', 'is_active'];
  
  
}