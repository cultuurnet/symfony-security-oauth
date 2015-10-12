<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 11/09/15
 * Time: 15:20
 */

namespace CultuurNet\SymfonySecurityOAuth\Security;

use CultuurNet\SymfonySecurityOAuth\EventListener\KernelMock;
use CultuurNet\SymfonySecurityOAuth\Service\UserMock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class OAuthListenerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \CultuurNet\SymfonySecurityOAuth\Security\OAuthListener */
    protected $oauthListener;

    public function setUp()
    {
        $tokenStorage = new TokenStorageMock();
        $authenticationProvider = new OAuthAuthenticationProviderMock();
        $authenticationManager = new AuthenticationManagerMock($authenticationProvider);
        $this->oauthListener = new OAuthListener($tokenStorage, $authenticationManager);
    }

    public function testHandle()
    {
        $kernel = new KernelMock();
        $request = new Request();
        $requestParameters = array(
            'oauth_consumer_key' => 'dpf43f3p2l4k3l03',
            'oauth_token' => 'nnch734d00sl2jdk',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => '1191242096',
            'oauth_nonce' => 'kllo9940pd9333jh',
            'oauth_version' => '1.0',
            'file' => 'vacation.jpg',
            'size' => 'original'
        );
        $request->attributes->set('oauth_request_parameters', $requestParameters);
        $request->attributes->set('oauth_request_method', 'GET');
        $request->attributes->set('oauth_request_url', 'http://test.com');

        $responseEvent = new GetResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $return = $this->oauthListener->handle($responseEvent);

        $expectedToken = new OAuthToken();
        $user = new UserMock('123456789', 'testUser', 'email@email.email');
        $expectedToken->setUser($user);
        $expectedToken->setRequestMethod('GET');
        $expectedToken->setRequestParameters($requestParameters);
        $expectedToken->setRequestUrl('http://test.com');

        $this->assertEquals($expectedToken, $return);
    }

    public function testHandleWithWrongToken()
    {
        $kernel = new KernelMock();
        $request = new Request();
        $requestParameters = array(
            'oauth_consumer_key' => 'badConsumer',
            'oauth_token' => 'badToken',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => '1191242096',
            'oauth_nonce' => 'kllo9940pd9333jh',
            'oauth_version' => '1.0',
            'file' => 'vacation.jpg',
            'size' => 'original'
        );
        $request->attributes->set('oauth_request_parameters', $requestParameters);
        $request->attributes->set('oauth_request_method', 'GET');
        $request->attributes->set('oauth_request_url', 'http://test.com');

        $responseEvent = new GetResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $this->setExpectedException(
            'Symfony\Component\Security\Core\Exception\AuthenticationException',
            'OAuth authentication failed'
        );

        $this->oauthListener->handle($responseEvent);
    }

    public function testHandleWithRequestParametersNull()
    {
        $kernel = new KernelMock();
        $request = new Request();

        $request->attributes->set('oauth_request_parameters', false);
        $request->attributes->set('oauth_request_method', 'GET');
        $request->attributes->set('oauth_request_url', 'http://test.com');

        $responseEvent = new GetResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $return = $this->oauthListener->handle($responseEvent);

        $this->assertEquals(null, $return);
    }

    public function testHandleWithAskForResponse()
    {
        $kernel = new KernelMock();
        $request = new Request();
        $requestParameters = array(
            'ask_response' => 'give_response',
            'oauth_consumer_key' => 'badConsumer',
            'oauth_token' => 'badToken',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => '1191242096',
            'oauth_nonce' => 'kllo9940pd9333jh',
            'oauth_version' => '1.0',
            'file' => 'vacation.jpg',
            'size' => 'original'
        );
        $request->attributes->set('oauth_request_parameters', $requestParameters);
        $request->attributes->set('oauth_request_method', 'GET');
        $request->attributes->set('oauth_request_url', 'http://test.com');

        $responseEvent = new GetResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $this->oauthListener->handle($responseEvent);

        $responseContent = $responseEvent->getResponse()->getContent();
        $expectedResponseContent = 'mockedResponseWithAskResponseParameter';

        $this->assertEquals($expectedResponseContent, $responseContent);
    }
}
