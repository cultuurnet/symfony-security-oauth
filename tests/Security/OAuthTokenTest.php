<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 15/09/15
 * Time: 14:44
 */

namespace CultuurNet\SymfonySecurityOAuth\Security;

use CultuurNet\SymfonySecurityOAuth\Service\UserMock;

class OAuthTokenTest extends \PHPUnit_Framework_TestCase
{
    public function testOAuthTokenProperties()
    {
        $oauthToken = new OAuthToken();
        $requestUrl = 'http://test.test';
        $oauthToken->setRequestUrl($requestUrl);
        $oauthToken->setRequestParameters('');
        $oauthToken->setRequestMethod('GET');
        $user = new UserMock('123456789', 'testUser', 'email@email.email');
        $oauthToken->setUser($user);
        $oauthToken->setAuthenticated(true);

        $this->assertEquals($requestUrl, $oauthToken->getRequestUrl());
        $this->assertEquals('', $oauthToken->getCredentials());
        $this->assertEquals('', $oauthToken->getRequestParameters());
        $this->assertEquals('GET', $oauthToken->getRequestMethod());
        $this->assertEquals($user, $oauthToken->getUser());
    }
}
