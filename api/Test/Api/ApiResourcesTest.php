<?php

namespace Test\Api;

use \PHPUnit_Framework_TestCase;

use \Controller\ContactController as Controller;
use \Model\ContactModel as Contact;

class ApiResourcesTest extends PHPUnit_Framework_TestCase
{
    private $http;

    public function setUp()
    {
        $this->http = new \GuzzleHttp\Client(['base_uri' => 'http://api.phonebook.mmadeira.dev/']);
    }
    
    public function tearDown() {
        $this->http = null;
    } 

    public function testShouldGetAllProducts()
    {
        $response = $this->http->request('GET', 'v1/contacts');
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=uft-8", $contentType);

        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $responseData);
    }

    public function testShouldProductCanBeCreated()
    {
        $productData = [
            'name' => 'Fulano de Tal',
            'phone' => '(99) 99342-3794',
            'email' => 'contato@fulanodetal.com'
        ];

        $response = $this->http->post('v1/contacts', [
            'json' => $productData
        ]);
        
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=uft-8", $contentType);
    }

    public function testShouldProductCanBeUpdated()
    {
        $response = $this->http->put('v1/contacts/110', [
            'json' => [
                'name' => 'Fulano de Tal',
                'phone' => '(99) 99342-3794',
                'email' => 'contato@fulanodetal.com'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=uft-8", $contentType);

        $responseData = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $responseData);
    }
    
    public function testShouldProductCanBeDeleted()
    {
        $response = $this->http->delete('v1/contacts/63');
        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json; charset=uft-8", $contentType);
    }
}