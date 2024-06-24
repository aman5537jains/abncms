<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsManagement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE TABLE `cms_management` (
            `id` int(10) UNSIGNED NOT NULL,
            `creater_id` int(10) UNSIGNED NOT NULL,
            `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `video` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
            `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'page',
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
          \DB::statement("ALTER TABLE `cms_management`
          ADD PRIMARY KEY (`id`);");
          \DB::statement("ALTER TABLE `cms_management`
          MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_management');
    }
}
