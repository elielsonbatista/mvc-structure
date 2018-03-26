<?php

namespace Src\View;

class View
{
    private $views_path = '../resource/views/';
    private $view;

    public function __construct($view)
    {
        return $this->view = $view;
    }

    public function render()
    {
        $content = file_get_contents($this->views_path . $this->view);
        return $content;
    }
}
