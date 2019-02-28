<?php

class Autoload
{
    /**
     * The paths that files in will be registered
     *
     * @var array
     */
    private $paths = [
        '../app',
        '../src'
    ];

    /**
     * Set the extensions to be registered and the function to do this
     *
     * @return void
     */
    public function __construct()
    {
        spl_autoload_extensions('.php');
        spl_autoload_register([$this, 'register']);
    }

    /**
     * Register the files
     *
     * @return void
     */
    public function register(): void
    {
        foreach ($this->paths as $path) {
            $this->load($path);
        }
    }

    /**
     * Get all files inside the path list
     *
     * @param  string $path
     * @return void
     */
    private function load(string $path): void
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
