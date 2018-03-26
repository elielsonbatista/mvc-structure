<?php

class Autoload
{
    private $paths = [
        '../app',
        '../src'
    ];

    public function __construct()
    {
        spl_autoload_extensions('.php');
        spl_autoload_register([$this, 'register']);
    }

    public function register()
    {
        foreach ($this->paths as $path) {
            $this->load($path);
        }
    }

    private function load($path)
    {
        foreach (scandir($path) as $key => $value) {
            if ($key > 1) {
                if (strlen($value) > 4 && substr($value, -4) == spl_autoload_extensions()) {
                    require "{$path}/{$value}";
                } else {
                    $this->load("{$path}/{$value}");
                }
            }
        }
    }
}

new Autoload();
