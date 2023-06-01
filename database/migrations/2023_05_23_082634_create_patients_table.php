<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table-> string('first_name');
            $table-> string('mid_name');
            $table-> string('last_name');
            $table-> enum('sex',['male','female','other']);
            $table-> integer('age');
            $table-> string('age_group'); //what is age group?
            $table-> string('dob');
            $table-> string('blood_group');
            $table-> string('mob_num');
            $table-> string('second_mob_num');
            $table-> string('whatsapp_num');
            $table-> string('email'); //optional
            $table-> string('family_history');
            $table-> string('father_name');
            $table-> string('father_height');
            $table-> string('father_profession');
            $table-> string('mother_name');
            $table-> string('mother_height');
            $table-> string('mother_profession');
            $table-> string('address');
            $table-> string('city');
            $table-> string('locality');
            $table-> string('pincode');
            $table-> string('referred_by');
            $table-> string('allergies');
            $table-> string('pre_term_days'); //what are pre term days??
            $table-> string('sec_num_type'); //what is secondary number type?
            $table-> string('significant_history');
            $table-> string('school');
            $table-> string('street');
            $table-> string('office_id');
            $table-> string('patient_id');
            $table-> string('additional_notes');
            $table-> string('flag');
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
};
