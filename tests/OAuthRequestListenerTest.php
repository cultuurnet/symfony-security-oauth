<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 02/09/15
 * Time: 16:11
 */

namespace CultuurNet\SymfonySecurityOAuth\EventListener;

use Symfony\Component\HttpFoundation\Request;

class OAuthRequestListenerTest extends \PHPUnit_Framework_TestCase {

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

}

/**
 * Mocked class that allows to change method visibility.
 */
class OAuthRequestListenerMock extends OAuthRequestListener
{
    public function parseAuthorizationHeader(Request $request)
    {
        return parent::parseAuthorizationHeader($request);
    }
    public function buildRequestUrl(Request $request)
    {
        return parent::buildRequestUrl($request);
    }
}
