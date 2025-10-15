<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            
            // Basic student info
            $table->string('student_name');
            $table->date('dob');
            $table->string('dob_words')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->text('address')->nullable();

            $table->string('class');  // was class_applied
            $table->string('section')->nullable();

            // Parent info
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('parent_email')->nullable();
            $table->string('parent_occupation')->nullable();

            // Previous school and academic info
            $table->text('prev_school_details')->nullable();
            $table->string('permanent_edu_no')->nullable();
            $table->string('appar_id_no')->nullable();
            $table->string('subject_opted')->nullable();
            $table->string('registration_no')->nullable();

            // Transport
            $table->enum('transport_facility', ['Yes','No'])->default('No');
            $table->string('transport_stoppage')->nullable();

            // Files
            $table->string('student_photo')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->string('student_aadhar')->nullable();
            $table->string('father_aadhar')->nullable();
            $table->string('mother_aadhar')->nullable();
            $table->string('transfer_certificate')->nullable();
            $table->string('medical_certificate')->nullable();

            // Status
            $table->enum('status', ['Pending','Approved','Rejected'])->default('Pending');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admissions');
    }
};
