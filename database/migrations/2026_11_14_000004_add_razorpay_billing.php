<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('plans', 'price_inr')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->unsignedInteger('price_inr')->default(0)->after('price');
            });
        }

        foreach (['starter' => 0, 'basic' => 3999, 'business' => 11999, 'professional' => 31999] as $slug => $price) {
            DB::table('plans')->where('slug', $slug)->update(['price_inr' => $price]);
        }

        if (! Schema::hasColumn('companies', 'plan_expires_at')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->date('plan_expires_at')->nullable()->after('plan_id');
            });
        }

        if (! Schema::hasTable('payments')) {
            DB::statement("create table `payments` (
                `id` bigint unsigned not null auto_increment,
                `company_id` bigint unsigned not null,
                `plan_id` bigint unsigned not null,
                `razorpay_order_id` varchar(255) not null,
                `razorpay_payment_id` varchar(255) null,
                `amount` int unsigned not null,
                `currency` varchar(3) not null default 'INR',
                `status` varchar(255) not null,
                `paid_at` timestamp null,
                `created_at` timestamp null,
                `updated_at` timestamp null,
                primary key (`id`),
                unique key `payments_razorpay_order_id_unique` (`razorpay_order_id`),
                constraint `payments_company_id_foreign` foreign key (`company_id`) references `companies` (`id`) on delete cascade,
                constraint `payments_plan_id_foreign` foreign key (`plan_id`) references `plans` (`id`) on delete restrict
            ) default character set utf8mb4 collate utf8mb4_unicode_ci");
        }

        if (! Schema::hasTable('plan_reminder_logs')) {
            DB::statement('create table `plan_reminder_logs` (
                `id` bigint unsigned not null auto_increment,
                `company_id` bigint unsigned not null,
                `days_before` smallint unsigned not null,
                `expires_on` date not null,
                `sent_at` timestamp not null,
                `created_at` timestamp null,
                `updated_at` timestamp null,
                primary key (`id`),
                unique key `plan_reminder_logs_company_id_days_before_expires_on_unique` (`company_id`, `days_before`, `expires_on`),
                constraint `plan_reminder_logs_company_id_foreign` foreign key (`company_id`) references `companies` (`id`) on delete cascade
            ) default character set utf8mb4 collate utf8mb4_unicode_ci');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_reminder_logs');
        Schema::dropIfExists('payments');

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('plan_expires_at');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('price_inr');
        });
    }
};
