<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfileFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone_number')->nullable()->after('email');
            $table->string('job_title')->nullable()->after('phone_number');
            $table->string('role')->nullable()->after('job_title');
            $table->string('permissions')->nullable()->after('role');
            $table->text('bio')->nullable()->after('permissions');
            $table->string('country')->nullable()->after('bio');
            $table->string('city')->nullable()->after('country');
            $table->string('address')->nullable()->after('city');
            $table->string('timezone')->nullable()->after('address');
            $table->string('linkedin')->nullable()->after('timezone');
            $table->string('github')->nullable()->after('linkedin');
            $table->string('instagram')->nullable()->after('github');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'phone_number', 'job_title', 'role',
                'permissions', 'bio', 'country', 'city', 'address', 'timezone',
                'linkedin', 'github', 'instagram'
            ]);
        });
    }
}
