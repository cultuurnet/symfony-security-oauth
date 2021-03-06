<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/09/15
 * Time: 14:51
 */

namespace CultuurNet\SymfonySecurityOAuth\Security;

use CultuurNet\Clock\FrozenClock;
use CultuurNet\SymfonySecurityOAuth\Model\Consumer;
use CultuurNet\SymfonySecurityOAuth\Model\Token;
use CultuurNet\SymfonySecurityOAuth\Service\ConsumerProviderMock;
use CultuurNet\SymfonySecurityOAuth\Service\NonceProviderMock;
use CultuurNet\SymfonySecurityOAuth\Service\OAuthServerServiceMock;
use CultuurNet\SymfonySecurityOAuth\Service\Signature\OAuthHmacSha1Signature;
use CultuurNet\SymfonySecurityOAuth\Service\TokenProviderMock;
use CultuurNet\SymfonySecurityOAuth\Service\UserMock;
use DateTimeZone;

class OAuthAuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \CultuurNet\SymfonySecurityOAuth\Security\OAuthAuthenticationProvider */
    protected $oauthAuthenticationProvider;

    /** @var  \CultuurNet\SymfonySecurityOAuth\Service\OAuthServerService */
    protected $oauthServerService;

    /** @var  \CultuurNet\SymfonySecurityOAuth\Service\Signature\OAuthHmacSha1Signature */
    protected $signatureService;

    /** @var  \CultuurNet\SymfonySecurityOAuth\Security\OAuthToken */
    protected $token;

    /** @var  string */
    protected $requestMethod;

    /** @var  string */
    protected $requestUrl;

    public function setUp()
    {
        $this->requestMethod = 'GET';
        $this->requestUrl = 'http://photos.example.net/photos';

        $this->token = new OAuthToken();
        $this->token->setRequestMethod($this->requestMethod);
        $this->token->setRequestUrl($this->requestUrl);

        $consumerProvider = new ConsumerProviderMock();
        $tokenProvider = new TokenProviderMock();
        $nonceProvider = new NonceProviderMock();
        $fixedTimestamp = new \DateTime();
        $fixedTimestamp->setTimestamp(1433160000);
        $clock = new FrozenClock($fixedTimestamp);
        $this->oauthServerService = new OAuthServerServiceMock($consumerProvider, $tokenProvider, $nonceProvider, $clock);
        $this->signatureService = new OAuthHmacSha1Signature();
        $this->oauthServerService->addSignatureService($this->signatureService);
        $userProvider = new UserProviderMock();

        $this->oauthAuthenticationProvider = new OAuthAuthenticationProvider($userProvider, $this->oauthServerService);
    }

    public function testAuthenticate()
    {
        $requestParameters = array(
            'oauth_consumer_key' => 'dpf43f3p2l4k3l03',
            'oauth_token' => 'nnch734d00sl2jdk',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => 1433160000,
            'oauth_nonce' => 'kllo9940pd9333jh',
            'oauth_version' => '1.0',
            'file' => 'vacation.jpg',
            'size' => 'original'
        );
        $consumerSecret = 'kd94hf93k423kf44';
        $tokenSecret = 'pfkkdhi9sl3r4s00';

        $signature = 'dwEfwtMrnGvGbxqXtv0q4BRRmLg=';
        $requestParameters['oauth_signature'] = $signature;

        $token = $this->token;
        $token->setRequestParameters($requestParameters);

        $returnedToken = $this->oauthAuthenticationProvider->authenticate($token);

        $expectedToken = new OAuthToken();
        $expectedToken->setRequestMethod($this->requestMethod);
        $expectedToken->setRequestUrl($this->requestUrl);
        $expectedToken->setRequestParameters($requestParameters);
        $user = new UserMock('123456789', 'testUser', 'email@email.email');
        $token = new Token();
        $token->setToken($requestParameters['oauth_token']);
        $token->setSecret('pfkkdhi9sl3r4s00');
        $consumer = new Consumer();
        $consumer->setConsumerKey($requestParameters['oauth_consumer_key']);
        $consumer->setConsumerSecret('kd94hf93k423kf44');
        $consumer->setName('testConsumer');
        $token->setConsumer($consumer);
        $token->setUser($user);
        $expectedToken = $expectedToken->authenticated($token);

        $this->assertEquals($expectedToken, $returnedToken);
    }

    public function testAuthenticateWithWrongTypeOfToken()
    {
        $requestParameters = array(
            'oauth_consumer_key' => 'dpf43f3p2l4k3l03',
            'oauth_token' => 'nnch734d00sl2jdk',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => 1433160000,
            'oauth_nonce' => 'kllo9940pd9333jh',
            'oauth_version' => '1.0',
            'file' => 'vacation.jpg',
            'size' => 'original'
        );
        $consumerSecret = 'kd94hf93k423kf44';
        $tokenSecret = 'pfkkdhi9sl3r4s00';

        $signature = 'dwEfwtMrnGvGbxqXtv0q4BRRmLg=';
        $requestParameters['oauth_signature'] = $signature;

        $token = new TokenMock();

        $returnedToken = $this->oauthAuthenticationProvider->authenticate($token);

        $this->assertEquals(null, $returnedToken, 'Returned token is null');
    }
}
