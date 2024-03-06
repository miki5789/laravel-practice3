<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::connection(config('activitylog.database_connection'))->create('activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('operator_id')->comment('操作したユーザー');
            $table->integer('target_id')->comment('操作対象のユーザー');
            $table->string('model')->comment('モデル名');
            $table->unsignedBigInteger('model_id')->comment('モデルのID');
            $table->string('operation_type')->comment('操作のタイプ'); // created, updated, deleted
            $table->timestamps(); // created_at と updated_at カラムの追加
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('activitylog.database_connection'))->dropIfExists('activity_logs');
    }
};