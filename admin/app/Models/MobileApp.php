<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileApp extends Model
{
    protected $fillable = [
        'app_name',
        'package_name',
        'description',
        'icon_url',
        'screenshots',
        'apk_download_url',
        'version',
        'whats_new',
        'file_size',
        'rating',
        'review_count',
        'category_id',
        'is_featured',
    ];

    protected $casts = [
        'screenshots' => 'array',
        'is_featured' => 'boolean',
        'rating' => 'float',
    ];

    protected $appends = ['downloads_count', 'real_rating', 'real_reviews_count'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function analytics()
    {
        return $this->hasMany(Analytic::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getDownloadsCountAttribute()
    {
        return $this->analytics()->where('event_type', 'download')->count();
    }

    public function getRealRatingAttribute()
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round((float)$avg, 1) : 0.0;
    }

    public function getRealReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Helper to clean relative storage path.
     */
    protected function cleanStoragePath($value)
    {
        if (empty($value)) return '';
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $parsed = parse_url($value);
            if (isset($parsed['path'])) {
                $pos = strpos($parsed['path'], '/storage/');
                if ($pos !== false) {
                    return substr($parsed['path'], $pos + 9);
                }
            }
        }
        return ltrim($value, '/');
    }

    /**
     * Get the absolute URL for the icon dynamically.
     */
    public function getIconUrlAttribute($value)
    {
        if (empty($value)) return '';
        
        if (filter_var($value, FILTER_VALIDATE_URL) && !str_contains($value, 'localhost') && !str_contains($value, '127.0.0.1')) {
            return $value;
        }

        $clean = $this->cleanStoragePath($value);
        return url('api/admin/media/' . $clean);
    }

    /**
     * Get the absolute URL for the APK download dynamically.
     */
    public function getApkDownloadUrlAttribute($value)
    {
        if (empty($value)) return '';
        
        if (filter_var($value, FILTER_VALIDATE_URL) && !str_contains($value, 'localhost') && !str_contains($value, '127.0.0.1') && !str_contains($value, 'lifechangerstouch.org')) {
            return $value;
        }
        
        return url('api/admin/apps/' . $this->id . '/download');
    }

    /**
     * Get absolute URLs for screenshots dynamically.
     */
    public function getScreenshotsAttribute($value)
    {
        if (empty($value)) return [];

        $items = is_array($value) ? $value : json_decode($value, true);
        if (!is_array($items)) return [];

        return array_map(function ($item) {
            if (filter_var($item, FILTER_VALIDATE_URL) && !str_contains($item, 'localhost') && !str_contains($item, '127.0.0.1')) {
                return $item;
            }
            $clean = $this->cleanStoragePath($item);
            return url('api/admin/media/' . $clean);
        }, $items);
    }
}
