<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table ='products';
    protected $primaryKey ='id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'cantidad', 'stock', 'type', 'unit_price','provider_id','description','photo','city','country','is_new'
    ];

    public function provider()
    {
        return $this->belongsTo('App\Provider');
    }

    public function purchase()
    {
        return $this->belongsTo('App/Purchase');
    }

}
