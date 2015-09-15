<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 02/09/15
 * Time: 16:11
 */

namespace CultuurNet\SymfonySecurityOAuth\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

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

    public function testOnEarlyKernelRequest()
    {
        $kernel = new KernelMock();
        $this->request->headers->set('Authorization', 'OAuth oauth_token=testtoken,oauth_consumer_key=testconsumerkey');
        $responseEvent = new GetResponseEvent($kernel, $this->request, HttpKernelInterface::MASTER_REQUEST);

        $this->listener->onEarlyKernelRequest($responseEvent);
        $request = $responseEvent->getRequest();

        $expectedRequest = new Request();
        $header = 'OAuth oauth_token=testtoken,oauth_consumer_key=testconsumerkey';
        $expectedRequest->headers->set('Authorization', $header);
        $parameters = $this->listener->parseAuthorizationHeader($expectedRequest);
        $expectedRequest->setMethod('GET');
        $expectedRequest->attributes->set('oauth_request_parameters', $parameters);
        $expectedRequest->attributes->set('oauth_request_method', 'GET');
        $expectedRequest->attributes->set('oauth_request_url', 'http://:/');

        $this->assertEquals(
            $expectedRequest->attributes->get('oauth_request_parameters'),
            $request->attributes->get('oauth_request_parameters')
        );
        $this->assertEquals(
            $expectedRequest->attributes->get('oauth_request_method'),
            $request->attributes->get('oauth_request_method')
        );
        $this->assertEquals(
            $expectedRequest->attributes->get('oauth_request_url'),
            $request->attributes->get('oauth_request_url')
        );
    }

    /**
     * When passing the wrong request type, headers are not parsed, and parameters not added to request.
     */
    public function testOnEarlyKernelAccessWithWrongRequestType()
    {
        $kernel = new KernelMock();
        $this->request->headers->set('Authorization', 'OAuth oauth_token=testtoken,oauth_consumer_key=testconsumerkey');
        $responseEvent = new GetResponseEvent($kernel, $this->request, 1000);

        $this->listener->onEarlyKernelRequest($responseEvent);
        $request = $responseEvent->getRequest();

        $this->assertEquals($this->request, $request);
    }

    public function testOnEarlyKernelAccessWithoutTokenAndConsumer()
    {
        $kernel = new KernelMock();
        $this->request->headers->set('Authorization', 'OAuth oauth_nonce=nonceke');
        $responseEvent = new GetResponseEvent($kernel, $this->request, 1000);

        $this->listener->onEarlyKernelRequest($responseEvent);
        $request = $responseEvent->getRequest();

        $this->assertEquals($this->request, $request);
    }
}
