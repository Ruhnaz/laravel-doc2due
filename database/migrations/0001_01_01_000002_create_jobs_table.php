<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('jobs') && Schema::hasTable('job_batches') && Schema::hasTable('failed_jobs')) {
            return;
        }

        if (! Schema::hasTable('jobs')) {
            DB::statement('create table `jobs` (
                `id` bigint unsigned not null auto_increment,
                `queue` varchar(255) not null,
                `payload` longtext not null,
                `attempts` smallint unsigned not null,
                `reserved_at` int unsigned null,
                `available_at` int unsigned not null,
                `created_at` int unsigned not null,
                primary key (`id`),
                index `jobs_queue_index` (`queue`)
            ) default character set utf8mb4 collate utf8mb4_unicode_ci');
        }

        if (! Schema::hasTable('job_batches')) {
            DB::statement('create table `job_batches` (
                `id` varchar(255) not null,
                `name` varchar(255) not null,
                `total_jobs` int not null,
                `pending_jobs` int not null,
                `failed_jobs` int not null,
                `failed_job_ids` longtext not null,
                `options` mediumtext null,
                `cancelled_at` int null,
                `created_at` int not null,
                `finished_at` int null,
                primary key (`id`)
            ) default character set utf8mb4 collate utf8mb4_unicode_ci');
        }

        if (! Schema::hasTable('failed_jobs')) {
            DB::statement('create table `failed_jobs` (
                `id` bigint unsigned not null auto_increment,
                `uuid` varchar(255) not null,
                `connection` varchar(255) not null,
                `queue` varchar(255) not null,
                `payload` longtext not null,
                `exception` longtext not null,
                `failed_at` timestamp not null default current_timestamp,
                primary key (`id`),
                unique key `failed_jobs_uuid_unique` (`uuid`),
                index `failed_jobs_connection_queue_failed_at_index` (`connection`, `queue`, `failed_at`)
            ) default character set utf8mb4 collate utf8mb4_unicode_ci');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
