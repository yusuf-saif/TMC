<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class DailyReflection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','type','body','source','publish_date','status','created_by','updated_by',
    ];

    protected $casts = [
        'publish_date' => 'date',
    ];

    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public static function forTodayOrLatest(): ?self
    {
        $today = Carbon::today();
        $todayItem = static::published()->whereDate('publish_date', $today)->orderByDesc('publish_date')->first();
        if ($todayItem) return $todayItem;
        return static::published()->whereDate('publish_date', '<=', $today)->orderByDesc('publish_date')->first();
    }
}
