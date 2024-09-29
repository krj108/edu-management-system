<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'class_id', 'room_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // العلاقة بين المدرس والمواد
    public function teacherSubjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject')->withTimestamps();
    }

    // العلاقة بين الطالب والمواد
    public function studentSubjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subject', 'student_id', 'subject_id');
    }

    // العلاقة مع الغرفة عبر المدرس
    public function teacherRooms()
    {
        return $this->belongsToMany(Room::class, 'teacher_room')->withTimestamps();
    }

    // العلاقة مع الصف
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    // العلاقة مع الغرفة
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // العلاقة مع الدروس
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'student_lesson', 'student_id', 'lesson_id');
    }

}
