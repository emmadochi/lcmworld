<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetaFeedback extends Model
{
    use HasFactory;

    protected $table = 'beta_feedback';

    protected $fillable = [
        'mobile_app_id',
        'package_name',
        'app_name',
        'app_version',
        'type',
        'message',
        'rating',
        'device_model',
        'os_version',
        'tester_name',
        'tester_email',
        'would_pay',
        'status',
        'admin_notes',
    ];

    public function app()
    {
        return $this->belongsTo(MobileApp::class, 'mobile_app_id');
    }
}
