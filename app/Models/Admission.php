<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    protected $table = 'admissions';

    protected $fillable = [
        'student_name', 'dob', 'dob_words', 'gender', 'mobile', 'whatsapp', 'address',
        'class_applied', 'section', 'father_name', 'mother_name', 'parent_email',
        'parent_occupation', 'prev_school_details', 'permanent_edu_no', 'appar_id_no',
        'subject_opted', 'registration_no', 'transport_facility', 'transport_stoppage',
        'student_photo', 'birth_certificate', 'student_aadhar', 'father_aadhar', 'mother_aadhar',
        'transfer_certificate', 'medical_certificate', 'status', 
    ];
}
