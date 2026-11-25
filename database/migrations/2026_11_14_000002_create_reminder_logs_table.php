<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reminder_logs')) {
            return;
        }

        DB::statement('create table `reminder_logs` (
            `id` bigint unsigned not null auto_increment,
            `document_id` bigint unsigned not null,
            `days_before` smallint unsigned not null,
            `sent_at` timestamp not null,
            `created_at` timestamp null,
            `updated_at` timestamp null,
            primary key (`id`),
            unique key `reminder_logs_document_id_days_before_unique` (`document_id`, `days_before`),
            constraint `reminder_logs_document_id_foreign` foreign key (`document_id`) references `documents` (`id`) on delete cascade
        ) default character set utf8mb4 collate utf8mb4_unicode_ci');
    }

    public function down(): void
    {
        Schema::dropIfExists('reminder_logs');
    }
};
