<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'section_id'];

   
    protected $table = 'classes';

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'class_id');
    }

}

