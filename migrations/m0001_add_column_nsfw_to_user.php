<?php

class m0001_add_column_nsfw_to_user
{
    public function up()
    {
        $db = \app\core\Application::$app->db;
        $SQL = "ALTER TABLE user ADD COLUMN nsfw tinyint(1) NOT NULL DEFAULT '0'";
        $db->pdo->exec($SQL);
    }

    public function down()
    {
        $db = \app\core\Application::$app->db;
        $SQL = "ALTER TABLE user DROP nsfw";
        $db->pdo->exec($SQL);
    }
}