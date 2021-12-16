<?php 

namespace app\core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        session_start();
        $fleshMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach($fleshMessages as $key => &$fleshMessage)
        {
            $fleshMessage['remove'] = true;
        }

        $_SESSION[self::FLASH_KEY] = $fleshMessages;
    }

    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        $fleshMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach($fleshMessages as $key => &$fleshMessage)
        {
            if($fleshMessage['remove'])
            {
                unset($fleshMessages[$key]);
            }
        }

        $_SESSION[self::FLASH_KEY] = $fleshMessages;
    }
}