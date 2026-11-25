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
        if (! Schema::hasTable('cache')) {
            DB::statement('create table `cache` (
                `key` varchar(255) not null,
                `value` mediumtext not null,
                `expiration` bigint not null,
                primary key (`key`),
                index `cache_expiration_index` (`expiration`)
            ) default character set utf8mb4 collate utf8mb4_unicode_ci');
        }

        if (! Schema::hasTable('cache_locks')) {
            DB::statement('create table `cache_locks` (
                `key` varchar(255) not null,
                `owner` varchar(255) not null,
                `expiration` bigint not null,
                primary key (`key`),
                index `cache_locks_expiration_index` (`expiration`)
            ) default character set utf8mb4 collate utf8mb4_unicode_ci');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
