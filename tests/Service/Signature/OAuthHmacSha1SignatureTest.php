<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 02/09/15
 * Time: 16:52
 */

namespace CultuurNet\SymfonySecurityOAuth\Service\Signature;

class OAuthHmacSha1SignatureTest extends \PHPUnit_Framework_TestCase
{
    private $method;

    public function setUp()
    {
        $this->method = new OAuthHmacSha1Signature();
    }

    public function testGetName()
    {
        $this->assertEquals('HMAC-SHA1', $this->method->getName());
    }

    public function testSign()
    {
        // Tests taken from http://wiki.oauth.net/TestCases section 9.2 ("HMAC-SHA1")
        $baseString     = 'bs';
        $consumerSecret = 'cs';
        $tokenSecret    = null;
        $this->assertEquals(
            'egQqG5AJep5sJ7anhXju1unge2I=',
            $this->method->sign($baseString, $consumerSecret, $tokenSecret),
            'token secret is null'
        );
        $tokenSecret    = 'ts';
        $this->assertEquals(
            'VZVjXceV7JgPq/dOTnNmEfO0Fv8=',
            $this->method->sign($baseString, $consumerSecret, $tokenSecret),
            'token secret is not null'
        );
    }
}
