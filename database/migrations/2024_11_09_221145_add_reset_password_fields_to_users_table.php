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
    Schema::table('users', function (Blueprint $table) {
        $table->string('reset_password_token')->nullable();
        $table->timestamp('reset_password_resend_at')->nullable();
        $table->timestamp('reset_password_expired_at')->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'reset_password_token',
            'reset_password_resend_at',
            'reset_password_expired_at',
        ]);
    });
}

};