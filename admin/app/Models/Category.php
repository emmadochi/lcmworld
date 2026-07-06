<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'icon_url'];

    public function mobileApps()
    {
        return $this->hasMany(MobileApp::class);
    }
}
