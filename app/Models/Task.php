<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes, ModelTrait;


    protected $fillable = [
        'projectId',
        'title',
        'description',
        'priority',
        'isDone'
    ];


    protected $with = [
        'project'
    ];


    public function project()
    {
        return $this->belongsTo(Project::class,'projectId','id')->withTrashed();
    }


}
