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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'surname');
            $table->string('given_name')->after('name');
            $table->string('image_path')->after('given_name');
            $table->string('phone')->after('image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('surname', 'name');
            $table->dropColumn('given_name');
            $table->dropColumn('image_path');
            $table->dropColumn('phone');
        });
    }
};
