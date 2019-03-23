<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
	protected $table='marks';
    
    protected $fillable = [
        'student_id', 'teacher_id', 'course_name','assignment','presentation','class_test','attendance','mid','final','GT','GRD','GP'
    ];
}
