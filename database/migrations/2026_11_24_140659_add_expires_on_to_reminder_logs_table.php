<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reminder_logs', function (Blueprint $table) {
            $table->date('expires_on')->nullable()->after('days_before');
        });

        DB::table('reminder_logs')->orderBy('id')->lazy()->each(function (object $row): void {
            $expiry = DB::table('documents')->where('id', $row->document_id)->value('expiry_date');

            if ($expiry === null) {
                DB::table('reminder_logs')->where('id', $row->id)->delete();

                return;
            }

            DB::table('reminder_logs')->where('id', $row->id)->update([
                'expires_on' => $expiry,
            ]);
        });

        DB::statement('alter table `reminder_logs` modify `expires_on` date not null');

        Schema::table('reminder_logs', function (Blueprint $table) {
            $table->unique(
                ['document_id', 'days_before', 'expires_on'],
                'reminder_logs_document_days_expires_unique',
            );
        });

        Schema::table('reminder_logs', function (Blueprint $table) {
            $table->dropForeign('reminder_logs_document_id_foreign');
            $table->dropUnique('reminder_logs_document_id_days_before_unique');
            $table->foreign('document_id', 'reminder_logs_document_id_foreign')
                ->references('id')
                ->on('documents')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reminder_logs', function (Blueprint $table) {
            $table->dropForeign('reminder_logs_document_id_foreign');
            $table->unique(
                ['document_id', 'days_before'],
                'reminder_logs_document_id_days_before_unique',
            );
            $table->dropUnique('reminder_logs_document_days_expires_unique');
            $table->foreign('document_id', 'reminder_logs_document_id_foreign')
                ->references('id')
                ->on('documents')
                ->cascadeOnDelete();
        });

        Schema::table('reminder_logs', function (Blueprint $table) {
            $table->dropColumn('expires_on');
        });
    }
};
