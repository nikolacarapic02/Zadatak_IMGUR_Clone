<?php

use app\core\Application;

class m0004_create_table_moderator_logging
{
    public function up()
    {
        $db = Application::$app->db;
        $sql = "CREATE TABLE moderator_logging(
            moderator_id int(11) NOT NULL,
            image_id int(11) DEFAULT NULL,
            gallery_id int(11) DEFAULT NULL,
            action longtext COLLATE utf8mb4_unicode_ci NOT NULL
        )";
        
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db = Application::$app->db;
        $sql = "DROP TABLE IF EXISTS moderator_logging";
        
        $db->pdo->exec($sql);
    }
}