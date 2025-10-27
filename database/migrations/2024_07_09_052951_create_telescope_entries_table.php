<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelescopeEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telescope_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('sequence')->autoIncrement();
            $table->char('uuid', 36)->unique();
            $table->char('batch_id', 36);
            $table->string('family_hash', 255)->nullable();
            $table->boolean('should_display_on_index')->default(true);
            $table->string('type', 20);
            $table->longText('content');
            $table->dateTime('created_at')->nullable();

            // Indexes
            $table->index('batch_id', 'telescope_entries_batch_id_index');
            $table->index('family_hash', 'telescope_entries_family_hash_index');
            $table->index(['type', 'should_display_on_index'], 'telescope_entries_type_should_display_on_index_index');
            $table->index('created_at', 'telescope_entries_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telescope_entries');
    }
}
