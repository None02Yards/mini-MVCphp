<?php
// app/Core/View.php

// class View
// {
//     public static function render(string $view, array $data = []): void
//     {
//         extract($data);
//         require VIEW_PATH . "/$view.php";
//     }
// }




class View
{
    /**
     * Render a view from app/Views
     */
    public static function render(string $view, array $data = [])
    {
        extract($data); // Makes $data keys available as variables
        require VIEW_PATH . '/' . $view . '.php';
    }
}
