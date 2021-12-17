<?php

use app\core\Application;

class m0001_add_column_nsfw_to_user
{
    public function up()
    {
        $db = Application::$app->db;
        $sql = "ALTER TABLE user ADD COLUMN nsfw tinyint(1) NOT NULL DEFAULT '0'";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = Application::$app->db;
        $sql = "ALTER TABLE user DROP COLUMN nsfw";
        $db->pdo->exec($sql);
    }
}