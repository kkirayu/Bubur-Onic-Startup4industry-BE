<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    protected $docsUrl = "";

    function initDocs($testCasename) {
        $this->docsUrl = $testCasename . '.md';

    }

    function appedHeader($text) : void {
        
        $myfile = fopen($this->docsUrl, "a") or die("Unable to create file!");
        $txt = $text;
        fwrite($myfile ,"## " . $txt . "\n\n");
        fclose($myfile);
    }
    function appendContent($text) : void {
        
        $myfile = fopen($this->docsUrl, "a") or die("Unable to create file!");
        $txt = $text;
        fwrite($myfile, $txt . "\n\n");
        fclose($myfile);
    }
    function appendJson($text) : void {
        
        $myfile = fopen($this->docsUrl, "a") or die("Unable to create file!");
        fwrite($myfile, "```\n" . json_encode($text).  "\n```" .  "\n\n");
        fclose($myfile);
    }
}
