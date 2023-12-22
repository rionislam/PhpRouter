<?php
namespace Services;

class ErrorHandler {
    public static function handleErrors($errno, $errstr, $errfile, $errline) {

        // Log the error
        error_log("Error: [$errno] $errstr in $errfile on line $errline");

        
        // Display a custom error page
        self::displayErrorPage(500); // Internal Server Error by default
        
    }

    public static function handleExceptions($exception) {
        // Log the exception
        error_log("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());

        // Display a custom error page
        self::displayErrorPage(500); // Internal Server Error by default
    }

    public static function displayErrorPage($statusCode = 500) {
        // Clean output buffer
        ob_clean();

        // Set the appropriate HTTP header
        http_response_code($statusCode);

        // Display the corresponding error page
        switch ($statusCode) {
            case 400:
                include($_SERVER['DOCUMENT_ROOT'].'/templates/error/400.html'); // Bad Request
                break;
            case 401:
                include($_SERVER['DOCUMENT_ROOT'].'/templates/error/401.html'); // Unauthorized
                break;
            case 403:
                include($_SERVER['DOCUMENT_ROOT'].'/templates/error/403.html'); // Access Denied
                break;
            case 404:
                include($_SERVER['DOCUMENT_ROOT'].'/templates/error/404.html'); // Not Found
                break;
            default:
                include($_SERVER['DOCUMENT_ROOT'].'/templates/error/500.html'); // General Error
        }

        exit;
    }

    public static function handleShutdown() {
        $error = error_get_last();
        if ($error !== null && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
            // Log the fatal error
            error_log("Fatal error: [{$error['type']}] {$error['message']} in {$error['file']} on line {$error['line']}");

            // Display a custom error page
            self::displayErrorPage();
        }
        ob_end_flush();
    }
}