<?php

declare(strict_types=1);



namespace MyParcelNL\Sdk\Test\Mock;

class MockMyParcelCurl extends \MyParcelNL\Sdk\Helper\MyParcelCurl
{
    public function __construct()
    {
        echo "[MockCurl] Mock instantiated!\n";
        // MyParcelCurl has no constructor, so no parent call needed
    }
    
    public function write($method,$url,$headers=[],$body='') {
        echo "[MockCurl] $method $url\n";
        // stel een dummy­response
        $this->response = ['status'=>200,'response'=>'{}','headers'=>[]];
        return $body;
    }

    public function getResponse(): array {
        return $this->response ?? ['response'=>'{}','headers'=>[],'code'=>200];
    }
}
