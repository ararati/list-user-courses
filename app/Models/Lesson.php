<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'url',
        'user_id'
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
