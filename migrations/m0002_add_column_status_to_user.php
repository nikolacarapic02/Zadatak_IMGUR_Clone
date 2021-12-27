<?php

use app\core\Application;

class m0002_add_column_status_to_user
{
    public function up()
    {
        $db = Application::$app->db;
        $sql = "ALTER TABLE user ADD COLUMN status enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active'";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = Application::$app->db;
        $sql = "ALTER TABLE user DROP COLUMN status";

        $db->pdo->exec($sql);
    }
}