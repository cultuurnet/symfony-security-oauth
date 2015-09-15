<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/09/15
 * Time: 15:48
 */

namespace CultuurNet\SymfonySecurityOAuth\Model;

use CultuurNet\SymfonySecurityOAuth\Service\UserMock;

class TokenTest extends \PHPUnit_Framework_TestCase
{


    public function testTokenProperties()
    {
        $token = new Token();
        $token->setToken('nnch734d00sl2jdk');
        $token->setSecret('pfkkdhi9sl3r4s00');

        $consumer = new Consumer();
        $consumer->setConsumerKey('dpf43f3p2l4k3l03');
        $consumer->setConsumerSecret('kd94hf93k423kf44');
        $consumer->setName('testConsumer');

        $token->setConsumer($consumer);

        $user = new UserMock('123456789', 'testUser', 'email@email.email');

        $token->setUser($user);
        $expiresAt = time();
        $token->setExpiresAt($expiresAt);

        $this->assertEquals('nnch734d00sl2jdk', $token->getToken(), 'The Token property is properly set');
        $this->assertEquals('pfkkdhi9sl3r4s00', $token->getSecret(), 'The Token Secret property is properly set');
        $this->assertEquals($consumer, $token->getConsumer(), 'The consumer property is properly set');
        $this->assertEquals($user, $token->getUser(), 'The user property is properly set');
        $this->assertEquals($expiresAt, $token->getExpiresAt(), 'The user property is properly set');
    }

    public function testTokenExpireFunctionality()
    {
        $token = new Token();
        $expiresAt = time();

        $this->assertEquals(9223372036854775807, $token->getExpiresIn());

        $token->setExpiresAt($expiresAt);
        $expiresIn = $token->getExpiresIn();
        $expiresInCalculated = $expiresAt - time();
        $this->assertEquals($expiresIn, $expiresInCalculated, 'ExpiresIn gets calculated perfectly');
        $this->assertFalse($token->hasExpired(), 'The token has net yet expired');
    }

    public function testTokenHasExpired()
    {
        $token = new Token();
        $expiresAt = time()-3600;

        $token->setExpiresAt($expiresAt);
        $expiresIn = $token->getExpiresIn();
        $expiresInCalculated = $expiresAt - time();
        $this->assertEquals($expiresIn, $expiresInCalculated, 'ExpiresIn gets calculated perfectly');
        $this->assertTrue($token->hasExpired(), 'The token has expired');
    }
}
