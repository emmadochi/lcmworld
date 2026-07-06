<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileApp extends Model
{
    protected $fillable = [
        'app_name',
        'description',
        'icon_url',
        'apk_download_url',
        'version',
        'rating',
        'review_count',
        'category_id',
        'is_featured',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function analytics()
    {
        return $this->hasMany(Analytic::class);
    }
}
