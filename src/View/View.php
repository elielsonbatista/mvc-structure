<?php

namespace Src\View;

class View
{
    /**
     * The view path
     *
     * @var string
     */
    private $view;

    /**
     * Create the view instance
     *
     * @param string $view
     * @return void
     */
    public function __construct(string $view)
    {
        return $this->view = $view;
    }

    /**
     * Render the view
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Get the view content
     *
     * @return string
     */
    public function render(): string
    {
        return file_get_contents(VIEWS_PATH . '/' . $this->view);
    }
}
