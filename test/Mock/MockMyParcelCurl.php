<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Test\Mock;

/**
 * Mock implementation of MyParcelCurl for testing
 * Simple queue-based system where tests can define their own responses
 * If no responses are queued, returns empty success responses
 */
class MockMyParcelCurl extends \MyParcelNL\Sdk\Helper\MyParcelCurl
{
    /**
     * @var self|null Static instance for test access
     */
    private static ?self $lastInstance = null;
    
    /**
     * @var array Static queue of responses shared by all instances  
     */
    public static array $responseQueue = [];
    
    /**
     * @var array History of requests made (for assertions)
     */
    private array $requestHistory = [];
    
    /**
     * @var array Current request info
     */
    private array $currentRequest = [];
    
    /**
     * @var array|null Response data (override private parent property to make it accessible)
     */
    protected $response = null;
    
    /**
     * Constructor - save instance for test access
     */
    public function __construct()
    {
        // MyParcelCurl has no constructor, so no parent call
        self::$lastInstance = $this;
    }
    
    /**
     * Get the last created instance (for test access)
     * 
     * @return self|null
     */
    public static function getInstance(): ?self
    {
        return self::$lastInstance;
    }
    
    /**
     * Static method to add a response to the queue (no instance needed)
     * 
     * @param array $response Response array with 'response', and optionally 'headers' and 'code'
     * @return void
     */
    public static function addResponse(array $response): void
    {
        // Ensure response has all required fields
        self::$responseQueue[] = array_merge(
            [
                'code' => 200,
                'headers' => [],
                'response' => '{}'
            ],
            $response
        );
    }
    
    /**
     * Add a response to the queue (instance method for backwards compatibility)
     * 
     * @param array $response Response array with 'response', and optionally 'headers' and 'code'
     * @return self
     */
    public function enqueueResponse(array $response): self
    {
        self::addResponse($response);
        return $this;
    }
    
    /**
     * Add multiple responses at once
     * 
     * @param array $responses Array of response arrays
     * @return self
     */
    public function enqueueResponses(array $responses): self
    {
        foreach ($responses as $response) {
            $this->enqueueResponse($response);
        }
        return $this;
    }
    
    /**
     * Override getResponse to return queued responses
     * 
     * @return array
     */
    public function getResponse(): array
    {
        // If we already have a response set (from a previous call), return it
        if ($this->response !== null) {
            return $this->response;
        }
        
        // Get next response from queue
        if (!empty(self::$responseQueue)) {
            $this->response = array_shift(self::$responseQueue);
        } else {
            // Default empty success response if queue is empty
            $this->response = [
                'response' => json_encode(['data' => []]),
                'code' => 200,
                'headers' => []
            ];
        }
        
        return $this->response;
    }
    
    /**
     * Get the last request made
     * 
     * @return array|null
     */
    public function getLastRequest(): ?array
    {
        return end($this->requestHistory) ?: null;
    }
    
    /**
     * Get all requests made
     * 
     * @return array
     */
    public function getRequestHistory(): array
    {
        return $this->requestHistory;
    }
    
    /**
     * Check if a specific URL was called
     * 
     * @param string $url
     * @return bool
     */
    public function wasUrlCalled(string $url): bool
    {
        foreach ($this->requestHistory as $request) {
            if (strpos($request['url'] ?? '', $url) !== false) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Reset the mock to initial state
     * 
     * @return self
     */
    public function reset(): self
    {
        self::$responseQueue = [];
        $this->requestHistory = [];
        $this->response = null;
        $this->currentRequest = [];
        $this->_resource = null;
        return $this;
    }
    
    /**
     * Override write to prevent actual HTTP calls
     * 
     * @param string $method
     * @param string $url
     * @param array $headers
     * @param string $body
     * @return string
     */
    public function write($method, $url, $headers = [], $body = '')
    {
        // Store request info but don't make actual HTTP call
        $this->currentRequest = [
            'method' => strtoupper($method),
            'url' => $url,
            'headers' => $headers,
            'body' => $body,
            'timestamp' => time()
        ];
        $this->requestHistory[] = $this->currentRequest;
        
        // Return the body as expected by MyParcelCurl
        return $body;
    }
    
    /**
     * Helper to get request method from CURL options
     * 
     * @return string
     */
    private function getRequestMethod(): string
    {
        // Use the method from the current request if available
        return $this->currentRequest['method'] ?? 'POST';
    }
    
    /**
     * Clear all responses from queue
     * 
     * @return void
     */
    public static function clearQueue(): void
    {
        self::$responseQueue = [];
    }
}
