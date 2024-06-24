<?php

use Aman5537jains\AbnCms\Models\AbnCmsModule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbnCmsModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE TABLE  `abn_cms_modules` (`id` INT NOT NULL AUTO_INCREMENT , `type` ENUM('PLUGIN','THEME','SHORTCODE') NOT NULL , `class_name` VARCHAR(255) NOT NULL , `configs` TEXT NOT NULL , `is_active` ENUM('0','1') NOT NULL , `updated_at` DATETIME NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        \DB::statement("ALTER TABLE `abn_cms_modules` ADD `name` VARCHAR(255) NULL AFTER `type`, ADD `description` TEXT NULL AFTER `name`;");
        \DB::statement("INSERT INTO `abn_cms_modules` (`id`, `type`, `name`, `description`, `class_name`, `configs`, `is_active`, `updated_at`, `created_at`) VALUES
        (1, 'PLUGIN', 'Dynamic Content Generator', 'Dynamic Content Generator', 'Aman5537jains\\AbnDynamicContentPlugin\\AbnDynamicContentPlugin', '', '0', '2024-05-14 10:17:52', '2024-05-14 12:06:53'),
        (2, 'THEME', 'Default Theme', 'Default Theme', 'Aman5537jains\\AbnCmsThemeAbnCms\\Theme', '', '0', '2024-05-14 10:16:00', '2024-05-14 12:07:12');");
        AbnCmsModule::where("name","Dynamic Content Generator")->update(["class_name"=>"Aman5537jains\\AbnDynamicContentPlugin\\AbnDynamicContentPlugin"]);
        AbnCmsModule::where("name","Default Theme")->update(["class_name"=>"Aman5537jains\\AbnCmsThemeAbnCms\\Theme"]);

}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abn_cms_modules');
    }
}
