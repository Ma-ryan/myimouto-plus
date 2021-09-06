<?php
class ChangeTagLength extends Rails\ActiveRecord\Migration\Base
{
    public function up()
    {
        $this->execute("ALTER TABLE `tags` CHANGE `name` `name` varchar(255) NOT NULL");
    }
}
