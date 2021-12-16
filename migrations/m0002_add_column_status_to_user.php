<?php

class m0002_add_column_status_to_user
{
    public function up()
    {
        $db = \app\core\Application::$app->db;
        $SQL = "ALTER TABLE user ADD COLUMN status enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active'";
        $db->pdo->exec($SQL);
    }

    public function down()
    {
        $db = \app\core\Application::$app->db;
        $SQL = "ALTER TABLE user DROP status";
        $db->pdo->exec($SQL);
    }
}