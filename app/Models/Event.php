<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','slug','description','speaker_name','event_date','end_date','location_type','location_detail','cover_image','capacity','status','created_by','updated_by'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'end_date' => 'datetime',
        'capacity' => 'integer',
    ];

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updater(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
    public function rsvps(): HasMany { return $this->hasMany(EventRsvp::class); }

    public function scopePublished($q){ return $q->where('status','published'); }
    public function scopeUpcoming($q){ return $q->where('event_date','>=', now())->orderBy('event_date'); }
    public function scopePast($q){ return $q->where('event_date','<', now())->orderByDesc('event_date'); }

    public function isFull(): bool
    {
        if (is_null($this->capacity)) return false;
        return $this->rsvps()->where('status','registered')->count() >= $this->capacity;
    }
}
