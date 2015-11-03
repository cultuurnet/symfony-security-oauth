<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/09/15
 * Time: 10:30
 */

namespace CultuurNet\SymfonySecurityOAuth\Service;

use CultuurNet\Clock\FrozenClock;
use CultuurNet\Clock\SystemClock;
use CultuurNet\SymfonySecurityOAuth\Model\Consumer;
use CultuurNet\SymfonySecurityOAuth\Model\Token;
use CultuurNet\SymfonySecurityOAuth\Service\Signature\OAuthHmacSha1Signature;
use DateTimeZone;

class OAuthServerServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var  array */
    protected $requestParameters;

    /** @var  string */
    protected $normalizedParameters;

    /** @var  string */
    protected $requestMethod;

    /** @var  string */
    protected $requestUrl;

    /** @var  \CultuurNet\SymfonySecurityOAuth\Service\OAuthServerService */
    protected $oauthServerService;

    /** @var  \CultuurNet\SymfonySecurityOAuth\Service\Signature\OAuthHmacSha1Signature */
    protected $signatureService;

    /**
     * Test build with example data found in oauth example online.
     * @see http://oauth.net/core/1.0a/#sig_base_example
     */
    public function setUp()
    {
        $this->requestParameters = array(
            'oauth_consumer_key' => 'dpf43f3p2l4k3l03',
            'oauth_token' => 'nnch734d00sl2jdk',
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => '1191242096',
            'oauth_nonce' => 'kllo9940pd9333jh',
            'oauth_version' => '1.0',
            'file' => 'vacation.jpg',
            'size' => 'original'
        );
        $this->normalizedParameters = 'file=vacation.jpg&oauth_consumer_key=dpf43f3p2l4k3l03';
        $this->normalizedParameters .= '&oauth_nonce=kllo9940pd9333jh&oauth_signature_method=HMAC-SHA1';
        $this->normalizedParameters .= '&oauth_timestamp=1191242096&oauth_token=nnch734d00sl2jdk';
        $this->normalizedParameters .= '&oauth_version=1.0&size=original';
        $this->requestMethod = 'GET';
        $this->requestUrl = 'http://photos.example.net/photos';
        $consumerProvider = new ConsumerProviderMock();
        $tokenProvider = new TokenProviderMock();
        $nonceProvider = new NonceProviderMock();
        $fixedTimestamp = new \DateTime();
        $fixedTimestamp->setTimestamp(1433160000);
        $clock = new FrozenClock($fixedTimestamp);

        $this->oauthServerService = new OAuthServerServiceMock(
            $consumerProvider,
            $tokenProvider,
            $nonceProvider,
            $clock
        );
        $this->signatureService = new OAuthHmacSha1Signature();
    }

    /**
     * Based on an example scenario by Twitter.
     * @see https://dev.twitter.com/oauth/overview/authorizing-requests
     */
    public function testSignatureForRequestWithPOSTParametersTwitter()
    {
        $requestParameters = array(
            'oauth_consumer_key' => "xvz1evFS4wEEPTGEFPHBog",
            'oauth_nonce' => "kYjzVBB8Y0ZFabxSWbWovY3uYSQ2pTgmZeNu2VS4cg",
            'oauth_signature' => "tnnArxj06cWHq44gCs1OSKk/jLY=",
            'oauth_signature_method' => "HMAC-SHA1",
            'oauth_timestamp' => "1318622958",
            'oauth_token' => "370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb",
            'oauth_version' => "1.0",
            'status' => "Hello Ladies + Gentlemen, a signed OAuth request!",
            'include_entities' => "true"
        );
        $expectedNormalizedParameters = 'include_entities=true&oauth_consumer_key=xvz1evFS4wEEPTGEFPHBog';
        $expectedNormalizedParameters .= '&oauth_nonce=kYjzVBB8Y0ZFabxSWbWovY3uYSQ2pTgmZeNu2VS4cg';
        $expectedNormalizedParameters .= '&oauth_signature_method=HMAC-SHA1';
        $expectedNormalizedParameters .= '&oauth_timestamp=1318622958';
        $expectedNormalizedParameters .= '&oauth_token=370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb';
        $expectedNormalizedParameters .= '&oauth_version=1.0&status=';
        $expectedNormalizedParameters .= 'Hello%20Ladies%20%2B%20Gentlemen%2C%20a%20signed%20OAuth%20request%21';
        $requestMethod = 'POST';
        $requestUrl = 'https://api.twitter.com/1/statuses/update.json';
        $consumerProvider = new ConsumerProviderMock();
        $tokenProvider = new TokenProviderMock();
        $nonceProvider = new NonceProviderMock();
        $fixedTimestamp = new \DateTime();
        $fixedTimestamp->setTimestamp(1446544977);
        $clock = new FrozenClock($fixedTimestamp);

        $oauthServerService = new OAuthServerServiceMock($consumerProvider, $tokenProvider, $nonceProvider, $clock);
        $signatureService = new OAuthHmacSha1Signature();

        // Make sure normalized parameters are ok.
        $normalizedParametersCalculated = $this->oauthServerService->normalizeRequestParameters($requestParameters);

        $this->assertEquals($expectedNormalizedParameters, $normalizedParametersCalculated);

        // Make sure the basestring is ok.
        $signatureBaseString = 'POST&https%3A%2F%2Fapi.twitter.com%2F1%2Fstatuses%2Fupdate.json';
        $signatureBaseString .= '&include_entities%3Dtrue';
        $signatureBaseString .= '%26oauth_consumer_key%3Dxvz1evFS4wEEPTGEFPHBog';
        $signatureBaseString .= '%26oauth_nonce%3DkYjzVBB8Y0ZFabxSWbWovY3uYSQ2pTgmZeNu2VS4cg';
        $signatureBaseString .= '%26oauth_signature_method%3DHMAC-SHA1';
        $signatureBaseString .= '%26oauth_timestamp%3D1318622958';
        $signatureBaseString .= '%26oauth_token%3D370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb';
        $signatureBaseString .= '%26oauth_version%3D1.0';
        $signatureBaseString .= '%26status%3DHello%2520';
        $signatureBaseString .= 'Ladies%2520%252B%2520Gentlemen%252C%2520a%2520signed%2520OAuth%2520request%2521';
        $signatureBaseStringCalculated = $this->oauthServerService->getSignatureBaseString(
            $signatureService,
            $requestMethod,
            $requestUrl,
            $expectedNormalizedParameters
        );

        $this->assertEquals($signatureBaseString, $signatureBaseStringCalculated);

        // Approve signature.
        $consumer = new Consumer();
        $consumer->setConsumerSecret('kAcSOqF21Fu85e7zjz7ZN2U4ZRhfV3WpwPAoE3Z7kBw');

        $token = new Token();
        $token->setSecret('LswwdoUaIvS8ltyTt5jkRh4J50vUPVVHtR2YPi5kE');

        $oauthServerService->addSignatureService($signatureService);

        $requestParameters['oauth_signature'] = 'tnnArxj06cWHq44gCs1OSKk/jLY=';

        $signatureIsApproved = $oauthServerService->approveSignature(
            $consumer,
            $requestParameters,
            $requestMethod,
            $requestUrl,
            $token
        );

        $this->assertTrue($signatureIsApproved, 'Approving signature');

    }

    public function testGetSignatureBaseString()
    {
        $signatureBaseString = 'GET&http%3A%2F%2Fphotos.example.net%2Fphotos&file%3Dvacation.jpg';
        $signatureBaseString .= '%26oauth_consumer_key%3Ddpf43f3p2l4k3l03%26oauth_nonce%3Dkllo9940pd9333jh';
        $signatureBaseString .= '%26oauth_signature_method%3DHMAC-SHA1%26oauth_timestamp%3D1191242096';
        $signatureBaseString .= '%26oauth_token%3Dnnch734d00sl2jdk%26oauth_version%3D1.0%26size%3Doriginal';
        $signatureBaseStringCalculated = $this->oauthServerService->getSignatureBaseString(
            $this->signatureService,
            $this->requestMethod,
            $this->requestUrl,
            $this->normalizedParameters
        );

        $this->assertEquals($signatureBaseString, $signatureBaseStringCalculated);
    }

    public function testNormalizeRequestParameters()
    {
        $requestParameters = $this->requestParameters;
        $normalizedParameters = $this->normalizedParameters;
        $normalizedParametersCalculated = $this->oauthServerService->normalizeRequestParameters($requestParameters);

        $this->assertEquals($normalizedParameters, $normalizedParametersCalculated);
    }

    /**
     * When normalizing the request parameters, oauth_signature has to be discarded.
     */
    public function testNormalizeRequestParametersWithOauthSignatureIncluded()
    {
        $requestParameters = $this->requestParameters;
        $requestParameters['oauth_signature'] = 'testSignature';
        $normalizedParameters = $this->normalizedParameters;
        $normalizedParametersCalculated = $this->oauthServerService->normalizeRequestParameters($requestParameters);

        $this->assertEquals($normalizedParameters, $normalizedParametersCalculated);
    }

    public function testNormalizeRequestParametersWithNullAsParameters()
    {
        $requestParameters = null;

        $normalizedParametersCalculated = $this->oauthServerService->normalizeRequestParameters($requestParameters);

        $this->assertEquals(null, $normalizedParametersCalculated);
    }

    public function testNormalizeRequestParametersWithArrayValue()
    {
        $requestParameters = $this->requestParameters;
        $requestParameters['test'] = array('foo', 'bar');
        $normalizedParameters = $this->normalizedParameters;
        $normalizedParameters .= '&test=bar&test=foo';

        $normalizedParametersCalculated = $this->oauthServerService->normalizeRequestParameters($requestParameters);

        $this->assertEquals($normalizedParameters, $normalizedParametersCalculated);
    }

    public function testApproveSignature()
    {
        $consumer = new Consumer();
        $consumer->setConsumerSecret('kd94hf93k423kf44');

        $token = new Token();
        $token->setSecret('pfkkdhi9sl3r4s00');

        $this->oauthServerService->addSignatureService($this->signatureService);

        $this->requestParameters['oauth_signature'] = 'tR3+Ty81lMeYAr/Fid0kMTYa/WM=';

        $signatureIsApproved = $this->oauthServerService->approveSignature(
            $consumer,
            $this->requestParameters,
            $this->requestMethod,
            $this->requestUrl,
            $token
        );

        $this->assertTrue($signatureIsApproved, 'Signature has been approved');
    }

    public function testApproveSignatureWithFaultyReference()
    {
        $consumer = new Consumer();
        $consumer->setConsumerSecret('kd94hf93k423kf44');

        $token = new Token();
        $token->setSecret('pfkkdhi9sl3r4s00');

        $this->oauthServerService->addSignatureService($this->signatureService);

        $this->requestParameters['oauth_signature'] = 'testSignature';

        $signatureIsApproved = $this->oauthServerService->approveSignature(
            $consumer,
            $this->requestParameters,
            $this->requestMethod,
            $this->requestUrl,
            $token
        );

        $this->assertFalse($signatureIsApproved, 'Signature has not been approved');
    }

    public function testValidateRequest()
    {
        $requestParameters = $this->requestParameters;
        $requestParameters['oauth_timestamp'] = 1433160000;
        $signature = 'dwEfwtMrnGvGbxqXtv0q4BRRmLg=';

        $requestParameters['oauth_signature'] = $signature;
        $this->oauthServerService->addSignatureService($this->signatureService);

        $hasBeenValidated = $this->oauthServerService->validateRequest(
            $requestParameters,
            $this->requestMethod,
            $this->requestUrl
        );

        $this->assertTrue($hasBeenValidated);
    }

    public function testValidateRequestWithBadData()
    {
        $signature = 'tR3+Ty81lMeYAr/Fid0kMTYa/WM=';

        $requestParameters = array(
            'oauth_consumer_key' => 'testConsumer',
            'oauth_token' => 'testToken',
            'oauth_signature' => $signature,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => 1433160000,
            'oauth_nonce' => 'testNonce',
            'oauth_version' => '1.0',
            'file' => 'vacation.jpg',
            'size' => 'original'
        );
        $this->oauthServerService->addSignatureService($this->signatureService);

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'signature_invalid');

        $hasBeenValidated = $this->oauthServerService->validateRequest(
            $requestParameters,
            $this->requestMethod,
            $this->requestUrl
        );
    }

    public function testValidateRequestWithBadTimestamp()
    {
        $requestParameters = $this->requestParameters;
        $fixedTimeInThePast = new \DateTime();
        $fixedTimeInThePast->setTimestamp(1420113600);
        $clock = new FrozenClock($fixedTimeInThePast);

        $requestParameters['oauth_timestamp'] = $clock->getDateTime()->getTimestamp();
        $consumerSecret = 'kd94hf93k423kf44';
        $tokenSecret = 'pfkkdhi9sl3r4s00';
        $signature = 'tR3+Ty81lMeYAr/Fid0kMTYa/WM=';

        $requestParameters['oauth_signature'] = $signature;
        $this->oauthServerService->addSignatureService($this->signatureService);

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'timestamp_refused');

        $hasBeenValidated = $this->oauthServerService->validateRequest(
            $requestParameters,
            $this->requestMethod,
            $this->requestUrl
        );
    }

    public function testValidateRequestWithoutRequestParameters()
    {
        $requestParameters = $this->requestParameters;
        $localTimeZone = new DateTimeZone('Europe/Brussels');
        $clock = new SystemClock($localTimeZone);
        $requestParameters['oauth_timestamp'] = $clock->getDateTime()->getTimestamp();
        $signature = 'KA+hKWjD8ofq67rnNcBJ1gDBToI=';
        $requestParameters['oauth_signature'] = $signature;
        $this->oauthServerService->addSignatureService($this->signatureService);

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'parameter_absent');

        $hasBeenValidated = $this->oauthServerService->validateRequest(
            null,
            $this->requestMethod,
            $this->requestUrl
        );
    }

    public function testValidateRequestWithoutARequiredParameter()
    {
        $requestParameters = $this->requestParameters;
        unset($requestParameters['oauth_nonce']);
        $localTimeZone = new DateTimeZone('Europe/Brussels');
        $clock = new SystemClock($localTimeZone);
        $requestParameters['oauth_timestamp'] = $clock->getDateTime()->getTimestamp();

        $signature = 'JymXgJ+AH5jycjJ60SB7ZsKHeyc=';
        $requestParameters['oauth_signature'] = $signature;
        $this->oauthServerService->addSignatureService($this->signatureService);

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'parameter_absent');

        $hasBeenValidated = $this->oauthServerService->validateRequest(
            $requestParameters,
            $this->requestMethod,
            $this->requestUrl
        );
    }

    public function testValidateRequestWithWrongOAuthVersion()
    {
        $requestParameters = $this->requestParameters;
        $requestParameters['oauth_version'] = '35A';

        $requestParameters['oauth_timestamp'] = 1433160000;
        $consumerSecret = 'kd94hf93k423kf44';
        $tokenSecret = 'pfkkdhi9sl3r4s00';
        $signature = 'gnlHX1gSvz30mAMaV6xWc5Stz78=';
        $requestParameters['oauth_signature'] = $signature;
        $this->oauthServerService->addSignatureService($this->signatureService);

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'version_rejected');

        $hasBeenValidated = $this->oauthServerService->validateRequest(
            $requestParameters,
            $this->requestMethod,
            $this->requestUrl
        );
    }

    public function testValidateRequestWithFaultyNonce()
    {
        $requestParameters = $this->requestParameters;
        $requestParameters['oauth_nonce'] = 'returnFalse';
        $requestParameters['oauth_timestamp'] = 1433160000;

        $signature = '2vT3xOR/Xij2DN1Ns3R8UNQ/N+g=';

        $requestParameters['oauth_signature'] = $signature;
        $this->oauthServerService->addSignatureService($this->signatureService);

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'nonce_used');

        $hasBeenValidated = $this->oauthServerService->validateRequest(
            $requestParameters,
            $this->requestMethod,
            $this->requestUrl
        );
    }

    public function testValidateRequestTokenError()
    {
        $requestParameters = $this->requestParameters;
        $requestParameters['oauth_token'] = 'returnBadToken';


        $requestParameters['oauth_timestamp'] = 1433160000;
        $signature = 'btZnOkU+lCRSAAK5q9riQc4VbZE=';
        $requestParameters['oauth_signature'] = $signature;
        $this->oauthServerService->addSignatureService($this->signatureService);

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'token_rejected');

        $hasBeenValidated = $this->oauthServerService->validateRequest(
            $requestParameters,
            $this->requestMethod,
            $this->requestUrl
        );
    }

    public function testCheckConsumer()
    {
        $consumer = new UserMock('123', 'Jos', 'jos@jos.com');
        $this->setExpectedException(
            'Symfony\Component\HttpKernel\Exception\HttpException',
            'consumer_key_unknown'
        );
        $this->oauthServerService->checkConsumer($consumer);
    }

    public function testGetSignatureService()
    {
        $this->setExpectedException(
            'Symfony\Component\HttpKernel\Exception\HttpException',
            'signature_method_rejected'
        );
        $this->oauthServerService->getSignatureService('FakeSignatureService');
    }

    public function testServerServiceProperties()
    {
        $consumerProvider = $this->oauthServerService->getConsumerProvider();
        $accessTokenLifetime = $this->oauthServerService->getAccessTokenLifetime();

        $expectedConsumerProvider = new ConsumerProviderMock();
        $expectedAccessTokenLifetime = OAuthServerService::DEFAULT_ACCESS_TOKEN_LIFETIME;

        $this->assertEquals($expectedConsumerProvider, $consumerProvider);
        $this->assertEquals($expectedAccessTokenLifetime, $accessTokenLifetime);
    }
}
