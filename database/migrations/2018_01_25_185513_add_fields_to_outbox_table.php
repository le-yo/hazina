<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToOutboxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outbox', function(Blueprint $table)
        {
            $table->integer('status')->after('message')->default(0);
            $table->integer('reminder_id')->after('message')->default(0);
            $table->longText('content')->after('message')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outbox', function(Blueprint $table)
        {
            $table->dropColumn('status');
            $table->dropColumn('reminder_id');
            $table->dropColumn('content');
        });
    }
}
