<?php

namespace app\core\cache;

use app\core\Application;
use app\core\page\PageGallery;
use app\core\page\PageImage;
use Predis\Client;

class Cache extends Client
{
    protected Client $redis;

    public function __construct()
    {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host'   => 'localhost',
            'port'   => 6379,
        ]);
    }

    protected function isCached($key)
    {
        if(!empty($this->redis->exists($key)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    protected function clearAll()
    {
        $this->redis->flushall();
    }

    // Images

    protected function cacheImages($page)
    {
        $instance = new PageImage();

        if($instance->checkContentToLoad())
        {
            $images = Application::$app->db->getAllImagesForPage($page);

            for($i = 0; $i < count($images); $i++)
            {
                $this->redis->zadd('images_all_page_'.$page, $i, json_encode($images[$i]));
                $this->redis->expire('images_all_page_'.$page, 30);
            }
        }
        else
        {
            $images = Application::$app->db->getImagesForPage($page);

            for($i = 0; $i < count($images); $i++)
            {
                $this->redis->zadd('images_page_'.$page, $i, json_encode($images[$i]));
                $this->redis->expire('images_page_'.$page, 30);
            }
        }
    
    }

    protected function getCachedImages($page)
    {
        $start = 0;
        $limit = 16;

        $instance = new PageImage();

        if($instance->checkContentToLoad())
        {
            $cachedImages = $this->redis->zrange('images_all_page_'.$page, $start, $limit);
        }
        else
        {
            $cachedImages = $this->redis->zrange('images_page_'.$page, $start, $limit);
        }

        for($i = 0; $i < count($cachedImages); $i++)
        {
            $images[$i] = json_decode($cachedImages[$i], true);
        }

        return $images;         
    }

    // Galleries

    protected function cacheGalleries($page)
    {
        $instance = new PageGallery();

        if($instance->checkContentToLoad())
        {
            $galleries = Application::$app->db->getAllGaleriesForPage($page);

            for($i = 0; $i < count($galleries); $i++)
            {
                $this->redis->zadd('galleries_all_page_'.$page, $i, json_encode($galleries[$i]));
                $this->redis->expire('galleries_all_page_'.$page, 30);
            }
        }
        else
        {
            $galleries = Application::$app->db->getGalleriesForPage($page);

            for($i = 0; $i < count($galleries); $i++)
            {
                $this->redis->zadd('galleries_page_'.$page, $i, json_encode($galleries[$i]));
                $this->redis->expire('galleries_page_'.$page, 30);
            }
        }
    
    }

    protected function getCachedGalleries($page)
    {
        $start = 0;
        $limit = 16;

        $instance = new PageGallery();

        if($instance->checkContentToLoad())
        {
            $cachedGalleries = $this->redis->zrange('galleries_all_page_'.$page, $start, $limit);
        }
        else
        {
            $cachedGalleries = $this->redis->zrange('galleries_page_'.$page, $start, $limit);
        }

        for($i = 0; $i < count($cachedGalleries); $i++)
        {
            $galleries[$i] = json_decode($cachedGalleries[$i], true);
        }

        return $galleries;         
    }

}