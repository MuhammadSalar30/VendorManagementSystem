<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuSection extends Model
{
    protected $fillable = ['name','discount'];

    public function items()
    {
        return $this->hasMany(MenuItem::class,'menu_section_id');
    }
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'menu_section_id');
    }
}

