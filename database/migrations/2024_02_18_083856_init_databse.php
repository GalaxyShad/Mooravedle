<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_participants', function (Blueprint $t) {
            $t->foreignId('user_id')->constrained();
            $t->foreignId('course_id')->constrained();
        });

        Schema::create('courses', function (Blueprint $t) {
            $t->id();
            $t->string('name')->comment('Название курса');

            $t->binary('picture')->nullable()->comment('Обложка курса');
            $t->string('picture_extension')->nullable()->comment('Расширение изображения');

            $t->foreignId('creator_id')->constrained('users');
        });

        Schema::create('tasks', function (Blueprint $t) {
            $t->id();
            $t->timestamps();
            $t->string('name')->comment('Название задания');
            $t->text('description')->comment('Описание задания');
            $t->timestamp('deadline')->comment('Срок сдачи задания');
            $t->unsignedInteger('max_mark')->comment('Максимальная оценка за задание');
            $t->unsignedInteger('passing_mark')->comment('Проходной балл');

            $t->foreignId('course_id')->constrained();
        });

        Schema::create('answers', function (Blueprint $t) {
            $t->id();

            $t->foreignId('student_id')->constrained(table: 'users');
            $t->foreignId('task_id')->constrained(table: 'tasks');

            $t->string('comment')->nullable()->comment('Комментарий студента');
            $t->timestamp('answer_date')->comment('Дата ответа на задание');
            $t->unsignedInteger('try')->comment('Попытка');

            $t->binary('file')->nullable()->comment('Файл выполненого задания студента');
            $t->string('file_name')->nullable()->comment('Название файла ответа студента');
            $t->string('file_extension', 10)->nullable()->comment('Расширение файла ответа студента');

            $t->foreignId('teacher_id')->nullable()->constrained(table: 'users');
            $t->string('teacher_comment')->nullable()->comment('Комментарий преподавателя');
            $t->unsignedInteger('mark')->nullable()->comment('Оценка');
            $t->timestamp('mark_date')->nullable()->comment('Дата оценки');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_participants');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('answers');
    }
};
