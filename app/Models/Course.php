<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'creator_id'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, "creator_id", "id");
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_participants');
    }

    public function tasks(): HasMany {
        return $this->hasMany(Task::class);
    }
}
