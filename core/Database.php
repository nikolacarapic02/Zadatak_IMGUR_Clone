<?php

namespace app\core;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigration = [];
        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach($toApplyMigrations as $migration)
        {
            if($migration === '.' || $migration === '..')
            {
                continue;
            }

            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();

            $this->log("Applying migration $migration" . PHP_EOL);
            $instance->up();
            $this->log("Applied migration $migration" . PHP_EOL);

            $newMigration[] = $migration;
        }

        if(!empty($newMigration))
        {
            $this->saveMigrations($newMigration);
        }

        $this->log("All migrations are applied");
        
    }

    public function dropMigrations()
    {
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $toDropMigrations = array_intersect($appliedMigrations, $files);

        foreach($toDropMigrations as $migration)
        {
            
            if($migration === '.' || $migration === '..')
            {
                continue;
            }

            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();

            $this->log("Deleting migration $migration" . PHP_EOL);
            $instance->down();
            $this->log("Deleted migration $migration" . PHP_EOL);
        }

        $this->clearMigrationsTable();

        $this->log("All migrations are deleted" . PHP_EOL);
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }

    public function clearMigrationsTable()
    {
        $this->pdo->exec("DELETE FROM migrations; ALTER TABLE migrations AUTO_INCREMENT =1");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {
        $str = implode(',', array_map(fn($m) => "('$m')", $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $statement->execute();
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    protected function log($message)
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}