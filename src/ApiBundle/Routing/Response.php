<?php
namespace ApiBundle\Routing;


/**
 * Class Response
 */
class Response
{
    /**
     * @var string
     */
    public $body;

    /**
     * @var int
     */
    public $statusCode;

    const HTTP_OK = 200;

    const HTTP_NOT_FOUND = 404;

    const HTTP_CREATED = 201;

    const HTTP_NO_CONTENT = 204;

    /**
     * Response constructor.
     * @param int $statusCode
     * @param string $message
     */
    public function __construct($statusCode = 200, $message = '')
    {
        $this->body = $message;
        $this->statusCode = $statusCode;
    }

    public function sendResponse()
    {
        http_response_code($this->statusCode);
        echo $this->body;
    }
}