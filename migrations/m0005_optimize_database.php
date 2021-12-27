<?php

use app\core\Application;

class m0005_optimize_database
{
    public function up()
    {
        $db = Application::$app->db;
        $sql1 = "SELECT TABLE_SCHEMA, TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'quant-zadatak' ORDER BY TABLE_SCHEMA, TABLE_NAME";
        $statement = $db->pdo->prepare($sql1);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            $sql2 = "OPTIMIZE TABLE ";
            $i = 0;
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
               $sql2 .= '`' . $row['TABLE_SCHEMA'] . '`.`' . $row['TABLE_NAME'] . '`, ';
               $i++;
            }
            $sql2 = substr($sql2, 0, strlen($sql2) - 2);

            $statement1 = $db->pdo->prepare($sql2);
            $statement1->execute();
         }
    }

    public function down()
    {
        
    }
}