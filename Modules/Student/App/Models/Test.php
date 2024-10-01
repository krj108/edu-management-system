<?php

namespace Modules\Teacher\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\ClassRoom;
use Modules\Admin\Models\Subject;
use Modules\Admin\Models\room;
use App\Models\User; 
class Test extends Model
{
    protected $fillable = ['title', 'subject_id', 'class_id', 'room_id', 'teacher_id', 'total_marks', 'passing_marks'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

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
}
