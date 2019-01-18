<?php

namespace Src\View;

class View
{
    private $view;

    public function __construct($view)
    {
        return $this->view = $view;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {
        return file_get_contents(VIEWS_PATH . '/' . $this->view);
    }
}
