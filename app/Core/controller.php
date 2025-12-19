<?php
// app/Core/Controller.php

class Controller
{
    /**
     * Helper to send JSON response
     *
     * @param array $data
     * @param int $statusCode
     */
    protected function json(array $data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
