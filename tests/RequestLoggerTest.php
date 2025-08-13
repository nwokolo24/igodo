<?php
namespace Tests;

use App\RequestLogger;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(RequestLogger::class)]
class RequestLoggerTest extends TestCase
{
    private $tempLogFile;
    
    protected function setUp(): void
    {
        // Create a temporary log file for testing
        $this->tempLogFile = sys_get_temp_dir() . '/request_logger_test_' . uniqid() . '.log';
        
        // Mock the superglobals for testing
        $_SERVER = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/test-endpoint',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'REMOTE_ADDR' => '192.168.1.1',
            'HTTP_USER_AGENT' => 'PHPUnit Test Browser'
        ];
        
        $_GET = ['param1' => 'value1', 'param2' => 'value2'];
        $_POST = ['field1' => 'data1', 'field2' => 'data2'];
        $_COOKIE = ['session' => 'abc123', 'theme' => 'dark'];
    }
    
    protected function tearDown(): void
    {
        // Clean up the temporary log file after testing
        if (file_exists($this->tempLogFile)) {
            unlink($this->tempLogFile);
        }
        
        // Reset superglobals
        $_SERVER = [];
        $_GET = [];
        $_POST = [];
        $_COOKIE = [];
    }
    
    public function testConstructorCreatesLogger()
    {
        $logger = new RequestLogger($this->tempLogFile);
        
        // Basic assertion to check if the logger was constructed without errors
        $this->assertInstanceOf(RequestLogger::class, $logger);
        
        // The constructor doesn't actually create the log file until log() is called
        // So we just verify the logger was created without errors
    }
    
    public function testLogCreatesJsonLogEntry()
    {
        // Enable output buffering to capture the header() call
        ob_start();
        
        // Create logger and log a request
        $logger = new RequestLogger($this->tempLogFile);
        $logger->log();
        
        // Discard output buffer - we don't need to check the actual header
        // as we can't easily mock the header() function in our test environment
        ob_end_clean();
        
        // Verify log file was created
        $this->assertFileExists($this->tempLogFile);
        
        // Read log content
        $logContent = file_get_contents($this->tempLogFile);
        $this->assertNotEmpty($logContent);
        
        // Verify it contains valid JSON
        $logEntry = json_decode($logContent, true);
        $this->assertIsArray($logEntry, "Log entry should be valid JSON that decodes to an array");
        
        // Verify the log entry contains expected data
        $this->assertEquals('Incoming HTTP request', $logEntry['message']);
        $this->assertArrayHasKey('context', $logEntry);
        
        // Check the context data
        $context = $logEntry['context'];
        $this->assertEquals('GET', $context['method']);
        $this->assertEquals('/test-endpoint', $context['uri']);
        $this->assertEquals('192.168.1.1', $context['remote_ip']);
        $this->assertEquals('PHPUnit Test Browser', $context['user_agent']);
        
        // Check that request_id exists and is 16 characters long (8 bytes in hex)
        $this->assertArrayHasKey('request_id', $context);
        $this->assertEquals(16, strlen($context['request_id']));
    }
}
