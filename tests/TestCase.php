<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    protected $docsUrl = "";

    protected $content = "";


    function appedHeader($text): void
    {

        $this->content = $this->content . "## " . $text . "\n\n";
    }

    function appendContent($text): void
    {


        $this->content = $this->content . $text . "\n\n";
    }

    function appendJson($text): void
    {

        $this->content = $this->content . "```json \n" . json_encode($text, JSON_PRETTY_PRINT) . "\n```" . "\n\n";

    }

    public function postJson($uri, array $data = [], array $headers = [], $options = 0)
    {
        $response = parent::postJson($uri, $data, $headers, $options);
        $this->appedHeader("Request");
        $this->appendContent("POST " . $this->docsUrl . $uri);
        $this->appendJson($data);
        $this->appedHeader("Response");
        $this->appendJson($response->json());

        echo($this->content);
        return $response;
    }
    public function putJson($uri, array $data = [], array $headers = [], $options = 0)
    {
        $response = parent::postJson($uri, $data, $headers, $options);
        $this->appedHeader("Request");
        $this->appendContent("POST " . $this->docsUrl . $uri);
        $this->appedHeader("Payload");
        $this->appendJson($data);
        $this->appedHeader("Response");
        $this->appendJson($response->json());

        echo($this->content);
        return $response;
    }
    public function getJson($uri, array $headers = [], $options = 0)
    {
        $response = parent::getJson($uri, $headers, $options);
        $this->appedHeader("Request");
        $this->appendContent("GET " . $this->docsUrl . $uri);
        $this->appedHeader("Response");
        $this->appendJson($response->json());

        echo($this->content);
        return $response;
    }
    public function deleteJson($uri, array $data = [], array $headers = [], $options = 0)
    {
        $response = parent::deleteJson($uri, $data , $headers, $options);
        $this->appedHeader("Request");
        $this->appendContent("DELETE " . $this->docsUrl . $uri);
        $this->appedHeader("Response");
        $this->appendJson($response->json());

        echo($this->content);
        return $response;
    }
}
