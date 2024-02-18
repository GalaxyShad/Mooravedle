<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $t) {
            $t->id();
            $t->string('name');
        });

        DB::table('roles')->insert(array(
            [
                'name' => 'Студент',
                'id' => 1
            ],
            [
                'name' => 'Преподаватель',
                'id' => 2
            ],
        ));

        Schema::create('users', function (Blueprint $t) {
            $t->id();
            $t->string('name')->comment('ФИО пользователя');
            $t->string('email')->unique()->comment('Электронная почта');
            $t->string('password')->comment('Захешированный пароль');
            $t->timestamp('email_verified_at')->nullable()->comment('Дата подтверждения почты');
            $t->rememberToken();
            $t->timestamps();

            $t->foreignId('role_id')
              ->default(1)
              ->comment('Роль пользователя')
              ->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
