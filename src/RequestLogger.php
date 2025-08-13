<?php
namespace App;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;

class RequestLogger
{
    private $logger;
    private $requestId;
    private $startTime;


    public function __construct(string $logFile)
    {
        $this->requestId = bin2hex(random_bytes(8)); // 16-char unique ID
        $this->startTime = microtime(true); // track start time for duration

        $handler = new StreamHandler($logFile, Logger::INFO);
        $handler->setFormatter(new JsonFormatter());

        $this->logger = new Logger('request');
        $this->logger->pushHandler($handler);
    }

    public function log()
    {
        $duration = microtime(true) - $this->startTime;
        $data = [
            'request_id' => $this->requestId,
            'timestamp'  => date('c'),
            'method'     => $_SERVER['REQUEST_METHOD'] ?? null,
            'uri'        => $_SERVER['REQUEST_URI'] ?? null,
            'protocol'   => $_SERVER['SERVER_PROTOCOL'] ?? null,
            'remote_ip'  => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'headers'    => function_exists('getallheaders') ? getallheaders() : [],
            'query'      => $_GET,
            'post'       => $_POST,
            'raw_body'   => file_get_contents('php://input'),
            'cookies'    => $_COOKIE,
            'server'     => $_SERVER,
            'memory_usage'  => memory_get_usage(true), // bytes, rounded up to system allocation
            'memory_peak'   => memory_get_peak_usage(true), // peak during request
            'duration_sec'  => round($duration, 6), // how long the request took
        ];

        $this->logger->info('Incoming HTTP request', $data);

        // Also send Request ID as response header
        header("X-Request-ID: {$this->requestId}");
    }
}
