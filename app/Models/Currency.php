<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
        protected $fillable = ['name','code','symbol','rate','is_default'];
    protected $casts = ['rate'=>'float','is_default'=>'boolean'];

    public static function defaultCurrency()
    {
        return static::where('is_default', true)->first() ?? static::first();
    }

}




