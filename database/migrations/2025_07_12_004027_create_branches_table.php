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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('alamat');
            $table->string('phone');
            $table->string('logo')->nullable();
            $table->unsignedBigInteger('pic_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->string('created_by_name');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->string('deleted_by_name')->nullable();
            $table->tinyInteger('deleted_status')->nullable()->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['code', 'deleted_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
