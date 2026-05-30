<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','slug','body','status','publish_at','published_at','send_email','created_by','updated_by',
    ];

    protected $casts = [
        'publish_at' => 'datetime',
        'published_at' => 'datetime',
        'send_email' => 'boolean',
    ];

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updater(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }

    public function scopePublished($q){ return $q->where('status','published'); }

    public static function publishDue(): int
    {
        $due = static::where('status','scheduled')->whereNotNull('publish_at')->where('publish_at','<=', now())->get();
        $count = 0;
        foreach ($due as $a) {
            $a->status = 'published';
            $a->published_at = now();
            $a->save();
            $count++;
        }
        return $count;
    }

    public static function latestPublished(): ?self
    {
        return static::published()->orderByDesc('published_at')->first();
    }
}
