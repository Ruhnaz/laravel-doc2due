<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('plans')) {
            DB::statement('create table `plans` (
                `id` bigint unsigned not null auto_increment,
                `name` varchar(255) not null,
                `slug` varchar(255) not null,
                `price` int unsigned not null,
                `document_limit` int unsigned not null,
                `created_at` timestamp null,
                `updated_at` timestamp null,
                primary key (`id`),
                unique key `plans_slug_unique` (`slug`)
            ) default character set utf8mb4 collate utf8mb4_unicode_ci');
        }

        if (DB::table('plans')->count() === 0) {
            $now = now();

            DB::table('plans')->insert([
                ['name' => 'Starter', 'slug' => 'starter', 'price' => 0, 'document_limit' => 2, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Basic', 'slug' => 'basic', 'price' => 49, 'document_limit' => 50, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Business', 'slug' => 'business', 'price' => 149, 'document_limit' => 200, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Professional', 'slug' => 'professional', 'price' => 399, 'document_limit' => 600, 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        if (! Schema::hasColumn('companies', 'plan_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->foreignId('plan_id')->nullable()->after('name')->constrained();
            });
        }

        $starterId = DB::table('plans')->where('slug', 'starter')->value('id');

        DB::table('companies')->whereNull('plan_id')->update(['plan_id' => $starterId]);
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('plan_id');
        });

        Schema::dropIfExists('plans');
    }
};
