<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('categories')) {
            DB::statement('create table `categories` (
                `id` bigint unsigned not null auto_increment,
                `company_id` bigint unsigned not null,
                `name` varchar(255) not null,
                `created_at` timestamp null,
                `updated_at` timestamp null,
                primary key (`id`),
                constraint `categories_company_id_foreign` foreign key (`company_id`) references `companies` (`id`) on delete cascade
            ) default character set utf8mb4 collate utf8mb4_unicode_ci');
        }

        if (! Schema::hasTable('contractors')) {
            DB::statement('create table `contractors` (
                `id` bigint unsigned not null auto_increment,
                `company_id` bigint unsigned not null,
                `name` varchar(255) not null,
                `email` varchar(255) null,
                `phone` varchar(255) null,
                `notes` text null,
                `created_at` timestamp null,
                `updated_at` timestamp null,
                primary key (`id`),
                constraint `contractors_company_id_foreign` foreign key (`company_id`) references `companies` (`id`) on delete cascade
            ) default character set utf8mb4 collate utf8mb4_unicode_ci');
        }

        if (! Schema::hasTable('documents')) {
            DB::statement('create table `documents` (
                `id` bigint unsigned not null auto_increment,
                `company_id` bigint unsigned not null,
                `category_id` bigint unsigned not null,
                `contractor_id` bigint unsigned null,
                `title` varchar(255) not null,
                `expiry_date` date not null,
                `notes` text null,
                `file_path` varchar(255) not null,
                `file_name` varchar(255) not null,
                `created_at` timestamp null,
                `updated_at` timestamp null,
                primary key (`id`),
                constraint `documents_company_id_foreign` foreign key (`company_id`) references `companies` (`id`) on delete cascade,
                constraint `documents_category_id_foreign` foreign key (`category_id`) references `categories` (`id`) on delete restrict,
                constraint `documents_contractor_id_foreign` foreign key (`contractor_id`) references `contractors` (`id`) on delete set null
            ) default character set utf8mb4 collate utf8mb4_unicode_ci');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
        Schema::dropIfExists('contractors');
        Schema::dropIfExists('categories');
    }
};
