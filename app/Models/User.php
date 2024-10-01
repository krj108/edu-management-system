<?php

namespace App\Models;

use Modules\Admin\Models\Room;
use Laravel\Sanctum\HasApiTokens;
use Modules\Admin\Models\Subject;
use Modules\Teacher\Models\Lesson;
use Modules\Admin\Models\ClassRoom;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    public function teacherSubjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject')->withTimestamps();
    }

    public function studentSubjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subject', 'student_id', 'subject_id');
    }

    public function teacherRooms()
    {
        return $this->belongsToMany(Room::class, 'teacher_room')->withTimestamps();
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'student_lesson', 'student_id', 'lesson_id');
    }

}
