<?php


class Response
{
    public $responseBody;

    public $responseStatusCode;

    public function __construct($responseMessage, $responseStatusCode)
    {
        $this->responseBody = $responseMessage;

        $this->responseStatusCode = $responseStatusCode;
    }

    private function setResponseStatusCode()
    {
        return  http_response_code($this->responseStatusCode);
    }

    private function dispalyBody()
    {
        echo $this->responseBody . $this->responseStatusCode;
    }

    public function sendResponse()
    {
        $this->setResponseStatusCode();

        $this->dispalyBody();
    }

}