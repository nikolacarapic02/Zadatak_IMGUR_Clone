<?php

use app\core\Application;

class m0003_drop_table_doctrine_migration_versions
{
    public function up()
    {
        
    }

    public function down()
    {
        $db = Application::$app->db;
        $sql = "DROP TABLE IF EXISTS doctrine_migration_versions";
        
        $db->pdo->exec($sql);
    }
}

