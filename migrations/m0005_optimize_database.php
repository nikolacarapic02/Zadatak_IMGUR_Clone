<?php

use app\core\Application;

class m0005_optimize_database
{
    public function up()
    {
        $db = Application::$app->db;

        $sql1 = "CREATE INDEX image_name_slug ON image (file_name, slug)";
        $statement1 = $db->pdo->prepare($sql1);
        $statement1->execute();

        $sql2 = "CREATE INDEX gallery_name_slug ON gallery (name, slug)";
        $statement2 = $db->pdo->prepare($sql2);
        $statement2->execute();

        $sql3 = "SELECT TABLE_SCHEMA, TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'quant-zadatak' ORDER BY TABLE_SCHEMA, TABLE_NAME";
        $statement3 = $db->pdo->prepare($sql3);
        $statement3->execute();

        if ($statement3->rowCount() > 0) {
            $sql4 = "OPTIMIZE TABLE ";
            $i = 0;
            while ($row = $statement3->fetch(PDO::FETCH_ASSOC)) {
               $sql4 .= '`' . $row['TABLE_SCHEMA'] . '`.`' . $row['TABLE_NAME'] . '`, ';
               $i++;
            }
            $sql4 = substr($sql4, 0, strlen($sql4) - 2);

            $statement4 = $db->pdo->prepare($sql4);
            $statement4->execute();
        }
    }

    public function down()
    {
        $db = Application::$app->db;

        $sql1 = "ALTER TABLE image DROP INDEX image_name_slug;";
        $statement1 = $db->pdo->prepare($sql1);
        $statement1->execute();

        $sql2 = "ALTER TABLE gallery DROP INDEX gallery_name_slug;";
        $statement2 = $db->pdo->prepare($sql2);
        $statement2->execute();
    }
}