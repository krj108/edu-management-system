<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'content', 'subject_id', 'class_id', 'room_id', 'teacher_id', 'pdf', 'video'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassRoom::class); 
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    public function students()
{
    return $this->belongsToMany(User::class, 'student_lesson', 'lesson_id', 'student_id');
}

}

