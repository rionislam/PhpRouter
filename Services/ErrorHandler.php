<?php
namespace Services;

class ErrorHandler {
    
    public static function init() {
        // Set a custom error handler
        set_error_handler([__CLASS__, 'handleErrors']);
        set_exception_handler([__CLASS__, 'handleExceptions']);
        register_shutdown_function([__CLASS__, 'handleShutdown']);
    }

    public static function handleErrors($errno, $errstr, $errfile, $errline) {
        // Check if error reporting is turned on and the error is not suppressed with @
        if ((error_reporting() !== 0) && !self::isErrorSuppressed($errfile, $errline)) {

            // Log the error
            self::logError("Error: [$errno] $errstr in $errfile on line $errline");

            // Display a custom error page
            self::displayErrorPage(500); // Internal Server Error by default
        }
    }

    // Add this method to check if error is suppressed with @ symbol
    private static function isErrorSuppressed($file, $line) {
        $contents = file($file);
        $lineContent = $contents[$line - 1];

        // Check if @ symbol is present in the line
        return strpos($lineContent, '@') !== false;
    }

    public static function handleExceptions($exception) {
        // Log the exception
        self::logError("Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());

        // Display a custom error page
        self::displayErrorPage(500); // Internal Server Error by default
    }

    private static function logError($message) {
        // Implement your logging mechanism here
        error_log($message);
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