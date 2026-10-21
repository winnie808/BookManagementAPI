<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tblBookType', function (Blueprint $table) {
            $table->string('BookTypePicture')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tblBookType', function (Blueprint $table) {
            $table->dropColumn('BookTypePicture');
        });
    }
};
