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

    //Migrations

    public function applyMigrations()
    {
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

    //End Migrations

    //Galleries

    public function getGaleries($page)
    {
        $limit = 16;
        if(empty($page))
        {
            $page = 1;
        }
        $start = ($page-1) * $limit;

        $statement = $this->pdo->prepare("SELECT * FROM gallery WHERE nsfw = 0 AND hidden = 0 LIMIT $start, $limit");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllGaleries($page)
    {
        $limit = 16;
        if(empty($page))
        {
            $page = 1;
        }
        $start = ($page-1) * $limit;

        $statement = $this->pdo->prepare("SELECT * FROM gallery ORDER BY id LIMIT $start, $limit");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getSingleGallery($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM gallery WHERE id = '$id' AND nsfw = 0 AND hidden = 0");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getSingleGalleryWithoutRule($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM gallery WHERE id = '$id'");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getGalleryByName($gallery_name)
    {
        $statement = $this->pdo->prepare("SELECT id FROM gallery WHERE name = '$gallery_name';");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getImagesFromGallery($id)
    {
        $statement = $this->pdo->prepare("SELECT ig.image_id, ig.gallery_id
        FROM image_gallery ig
        WHERE ig.gallery_id = $id AND ig.image_id IN(SELECT id FROM image WHERE nsfw = 0 AND hidden = 0)");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllImagesFromGallery($id)
    {
        $statement = $this->pdo->prepare("SELECT image_id FROM image_gallery WHERE gallery_id = '$id'");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getNumOfGalleries()
    {
        $statement = $this->pdo->prepare("SELECT COUNT(id) as 'num' FROM gallery WHERE nsfw = 0 AND hidden = 0");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getNumOfAllGalleries()
    {
        $statement = $this->pdo->prepare("SELECT COUNT(id) as 'num' FROM gallery");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getNumOfYourGalleries($user_id)
    {
        $statement = $this->pdo->prepare("SELECT COUNT(id) as 'num' FROM gallery WHERE user_id = '$user_id' AND nsfw = 0 AND hidden = 0");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getNumOfYourAllGalleries($user_id)
    {
        $statement = $this->pdo->prepare("SELECT COUNT(id) as 'num' FROM gallery WHERE user_id = '$user_id'");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function editGalleryByModerator($nsfw, $hidden, $id)
    {
        $statement = $this->pdo->prepare("UPDATE gallery SET nsfw = '$nsfw', hidden = '$hidden' WHERE id = '$id'");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function editGalleryByAdmin($name, $slug, $nsfw, $hidden, $description, $id)
    {
        $statement = $this->pdo->prepare("UPDATE gallery SET name = '$name', description = '$description', slug = '$slug', nsfw = '$nsfw', hidden = '$hidden' WHERE id = '$id'");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getGalleriesForUser($user_id, $page)
    {
        $limit = 8;
        if(empty($page))
        {
            $page = 1;
        }
        $start = ($page-1) * $limit;

        $statement = $this->pdo->prepare("SELECT * FROM gallery WHERE user_id = $user_id AND nsfw = 0 AND hidden = 0 LIMIT $start, $limit");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllGalleriesForUser($user_id, $page)
    {
        $limit = 8;
        if(empty($page))
        {
            $page = 1;
        }
        $start = ($page-1) * $limit;

        $statement = $this->pdo->prepare("SELECT * FROM gallery WHERE user_id = $user_id LIMIT $start, $limit");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function createGallery($name, $slug, $description, $user_id)
    {
        $statement = $this->pdo->prepare("INSERT INTO gallery (user_id, name, description, slug)
        VALUES ('$user_id', '$name', '$description', '$slug');");
        $statement->execute();

    }

    public function editGallery($name, $slug, $description, $id, $user_id)
    {
        $statement = $this->pdo->prepare("UPDATE gallery SET name = '$name', slug = '$slug', description = '$description' WHERE id = '$id' AND user_id = '$user_id';");
        $statement->execute();
    }

    public function deleteGalleryImageKey($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM image_gallery WHERE gallery_id = '$id'");
        $statement->execute();
    }

    public function deleteGalleryCommentKey($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM comment WHERE gallery_id = '$id'");
        $statement->execute();
    }

    public function deleteGallery($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM gallery WHERE id = '$id'");
        $statement->execute();
    }


    //End Galleries

    //Images

    public function getImages($page)
    {
        $limit = 16;
        if(empty($page))
        {
            $page = 1;
        }
        $start = ($page-1) * $limit;

        $statement = $this->pdo->prepare("SELECT * FROM image WHERE nsfw = 0 AND hidden = 0 LIMIT $start, $limit");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllImages($page)
    {
        $limit = 16;
        if(empty($page))
        {
            $page = 1;
        }
        $start = ($page-1) * $limit;

        $statement = $this->pdo->prepare("SELECT * FROM image LIMIT $start, $limit");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getSingleImageById($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM image WHERE id = '$id' AND nsfw = 0 AND hidden = 0");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getSingleImageByIdWithoutRule($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM image WHERE id = '$id'");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getSingleImageBySlugWithoutRule($slug)
    {
        $statement = $this->pdo->prepare("SELECT * FROM image WHERE slug = '$slug'");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getNumOfImages()
    {
        $statement = $this->pdo->prepare("SELECT COUNT(id) as 'num' FROM image WHERE nsfw = 0 AND hidden = 0");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getNumOfAllImages()
    {
        $statement = $this->pdo->prepare("SELECT COUNT(id) as 'num' FROM image");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getNumOfYourImages($user_id)
    {
        $statement = $this->pdo->prepare("SELECT COUNT(id) as 'num' FROM image WHERE user_id = '$user_id' AND nsfw = 0 AND hidden = 0");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getNumOfYourAllImages($user_id)
    {
        $statement = $this->pdo->prepare("SELECT COUNT(id) as 'num' FROM image WHERE user_id = '$user_id'");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function editImageByModerator($nsfw, $hidden, $id)
    {
        $statement = $this->pdo->prepare("UPDATE image SET nsfw = '$nsfw', hidden = '$hidden' WHERE id = '$id'");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function editImageByAdmin($file_name, $newSlug, $nsfw, $hidden, $id)
    {
        $statement = $this->pdo->prepare("UPDATE image SET file_name = '$file_name', slug = '$newSlug', nsfw = '$nsfw', hidden = '$hidden' WHERE id = '$id'");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getImagesForUser($user_id, $page)
    {
        $limit = 8;
        if(empty($page))
        {
            $page = 1;
        }
        $start = ($page-1) * $limit;

        $statement = $this->pdo->prepare("SELECT * FROM image WHERE user_id = $user_id AND nsfw = 0 AND hidden = 0 LIMIT $start, $limit");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllImagesForUser($user_id, $page)
    {
        $limit = 8;
        if(empty($page))
        {
            $page = 1;
        }
        $start = ($page-1) * $limit;

        $statement = $this->pdo->prepare("SELECT * FROM image WHERE user_id = $user_id LIMIT $start, $limit");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createImage($file_name, $slug, $user_id, $gallery_name)
    {
        $statement = $this->pdo->prepare ("INSERT INTO image (user_id, file_name, slug)
        VALUES ('$user_id', '$file_name', '$slug');");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function AddToTableImageGallery($image_id, $gallery_id)
    {
        $statement = $this->pdo->prepare("INSERT INTO image_gallery (image_id, gallery_id)
            VALUES ('$image_id', '$gallery_id')");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function editImage($name, $slug, $id, $user_id)
    {
        $statement = $this->pdo->prepare("UPDATE image SET file_name = '$name', slug = '$slug' WHERE id = '$id' AND user_id = '$user_id';");
        $statement->execute();
    }

    public function deleteImageGalleryKey($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM image_gallery WHERE image_id = '$id'");
        $statement->execute();
    }

    public function deleteImageCommentKey($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM comment WHERE image_id = '$id'");
        $statement->execute();
    }

    public function deleteImage($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM image WHERE id = '$id'");
        $statement->execute();
    }

    //End Images

    //User

    public function getUser($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM user WHERE id = $id");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCommentsForImage($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM comment WHERE image_id = $id");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCommentsForGallery($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM comment WHERE gallery_id = $id");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createCommentForImage($user_id, $image_id, $comment)
    {
        $statement = $this->pdo->prepare("INSERT INTO comment (user_id , image_id, comment)
        VALUES ($user_id, $image_id, '$comment');");
        $statement->execute();
    }

    public function createCommentForGallery($user_id, $gallery_id, $comment)
    {
        $statement = $this->pdo->prepare("INSERT INTO comment (user_id, gallery_id, comment)
        VALUES ($user_id, $gallery_id, '$comment');");
        $statement->execute();
    }

    public function moderatorImageLogging($user_id, $username, $id, $name, $action)
    {
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $statement = $this->pdo->prepare("INSERT INTO moderator_logging (moderator_id, image_id, action)
        VALUES ($user_id, $id, CONCAT('Moderator ', '$username', ' oznacio sliku ','$name', ' - $url', ' da ', '$action'));");
        $statement->execute();
    }

    public function moderatorGalleryLogging($user_id, $username, $id, $name, $action)
    {
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $statement = $this->pdo->prepare("INSERT INTO moderator_logging (moderator_id, gallery_id, action)
        VALUES ($user_id, $id, CONCAT('Moderator ', '$username', ' oznacio galeriju ','$name', ' - $url', ' da ', '$action'));");
        $statement->execute();
    }

    public function changeStatus($id, $status)
    {
        $statement = $this->pdo->prepare("UPDATE user SET status = '$status' WHERE id = '$id'");
        $statement->execute();
    }

    public function changeRole($id, $role)
    {
        $statement = $this->pdo->prepare("UPDATE user SET role = '$role' WHERE id = '$id'");
        $statement->execute();
    }

    //End User

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }


    protected function log($message)
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}