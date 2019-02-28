<?php

if (! function_exists('view')) {
    /**
     * Create a view instance
     *
     * @param  string $view
     * @return \Src\View\View
     */
    function view(string $view): \Src\View\View
    {
        return new \Src\View\View($view);
    }
}
