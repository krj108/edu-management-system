<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'class_id'];

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subject', 'subject_id', 'user_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_subject', 'subject_id', 'user_id');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
}
