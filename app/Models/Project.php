<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes, ModelTrait;

    protected $fillable = [
        'title'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class,'projectId', 'id');
    }
}
