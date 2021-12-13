<?php
class AddDownloadSettings extends Rails\ActiveRecord\Migration\Base
{
    public function up()
    {
        $sql = Rails\ActiveRecord\ActiveRecord::connection();
        $sql->executeSql("ALTER TABLE `users` ADD COLUMN `download_mode` tinyint(1) NOT NULL DEFAULT 0");
        $sql->executeSql("ALTER TABLE `users` ADD COLUMN `download_name` varchar(255) DEFAULT NULL");
    }
}
