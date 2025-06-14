<?php

class ErrorHandler{
    public static function handleException(Throwable $exception): void {
        http_response_code(500);


        echo json_encode([
           "code" => $exception->getcode(),
           "message" => $exception->getMessage(),
           "file" => $exception->getFile(),
           "line" => $exception-> getLine()
        ], true);
    }

    public static function handleError(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ): bool{

        throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
    }
}