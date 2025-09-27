<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key','value'];

    public $timestamps = false;

    public static function getValue(string $key, $default = null)
    {
        $rec = static::where('key', $key)->first();
        if (!$rec) return $default;
        $val = json_decode($rec->value, true);
        return $val === null ? $rec->value : $val;
    }
}


