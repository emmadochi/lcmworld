<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    protected $fillable = ['mobile_app_id', 'event_type', 'ip_address'];

    public function mobileApp()
    {
        return $this->belongsTo(MobileApp::class);
    }
}
