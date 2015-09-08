<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 02/09/15
 * Time: 16:11
 */

namespace CultuurNet\SymfonySecurityOAuth\EventListener;

use Symfony\Component\HttpFoundation\Request;

class OAuthRequestListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \CultuurNet\SymfonySecurityOAuth\EventListener\OAuthRequestListenerMock
     */
    protected $listener;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    public function setUp()
    {
        $this->listener = new OAuthRequestListenerMock();
        $this->request  = new Request();
    }

    public function testParseAuthorizationHeader()
    {
        $this->request->headers->set('Authorization', 'OAuth foo=bar,baz="foobaz",name=will');

        $headers = $this->listener->parseAuthorizationHeader($this->request);

        $this->assertTrue(is_array($headers), 'Result is an array');
        $this->assertEquals(3, count($headers), 'Result must contains 3 elements');
        $this->assertArrayHasKey('foo', $headers, 'Check keys');
        $this->assertArrayHasKey('baz', $headers, 'Check keys');
        $this->assertArrayHasKey('name', $headers, 'Check keys');
        $this->assertEquals('bar', $headers['foo'], 'Check normal value');
        $this->assertEquals('foobaz', $headers['baz'], 'Check value with quotes');
        $this->assertEquals('will', $headers['name'], 'Check normal value');
    }

    public function testParseAuthorizationHeaderWithoutAuthorization()
    {
        $headers = $this->listener->parseAuthorizationHeader($this->request);
        $this->assertTrue(is_array($headers), 'Result is an array');
        $this->assertEquals(0, count($headers), 'Result should not contain any element');
    }
    public function testParseAuthorizationHeaderWithNullValue()
    {
        $headers = $this->listener->parseAuthorizationHeader($this->request);
        $this->request->headers->set('Authorization', null);
        $this->assertTrue(is_array($headers), 'Result is an array');
        $this->assertEquals(0, count($headers), 'Result should not contain any element');
    }
    public function testParseAuthorizationHeaderWithEmptyValue()
    {
        $headers = $this->listener->parseAuthorizationHeader($this->request);
        $this->request->headers->set('Authorization', '');
        $this->assertTrue(is_array($headers), 'Result is an array');
        $this->assertEquals(0, count($headers), 'Result should not contain any element');
    }

    public function testBuildRequestUrl()
    {
        $request    = Request::create('http://test.com/foo?bar=baz');
        $requestUrl = $this->listener->buildRequestUrl($request);
        $this->assertEquals('http://test.com/foo', $requestUrl, '');
        $request    = Request::create('http://test.com');
        $requestUrl = $this->listener->buildRequestUrl($request);
        $this->assertEquals('http://test.com/', $requestUrl, '');
        $request    = Request::create('http://test.com');
        $requestUrl = $this->listener->buildRequestUrl($request);
        $this->assertEquals('http://test.com/', $requestUrl, '');
        $request    = Request::create('https://test.com');
        $requestUrl = $this->listener->buildRequestUrl($request);
        $this->assertEquals('https://test.com/', $requestUrl, '');
    }
}