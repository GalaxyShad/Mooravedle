<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
