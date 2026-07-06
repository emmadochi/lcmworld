<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'mobile_app_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mobileApp()
    {
        return $this->belongsTo(MobileApp::class);
    }
}
