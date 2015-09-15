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
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        $this->oauthServerService = new OAuthServerServiceMock($consumerProvider, $tokenProvider, $nonceProvider);
        $this->signatureService = new OAuthHmacSha1Signature();
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
        $localTimeZone = new DateTimeZone('Europe/Brussels');
        $clock = new SystemClock($localTimeZone);
        $requestParameters['oauth_timestamp'] = $clock->getDateTime()->getTimestamp();
        $consumerSecret = 'kd94hf93k423kf44';
        $tokenSecret = 'pfkkdhi9sl3r4s00';
        $signature = $this->calculateSignature($requestParameters, $consumerSecret, $tokenSecret);
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
        $requestParameters = $this->requestParameters;
        $consumerSecret = 'kd94hf93k423kf44';
        $tokenSecret = 'pfkkdhi9sl3r4s00';
        $signature = $this->calculateSignature($requestParameters, $consumerSecret, $tokenSecret);
        $localTimeZone = new \DateTimeZone('Europe/Brussels');
        $clock = new SystemClock($localTimeZone);

        $requestParameters = array(
            'oauth_consumer_key' => 'testConsumer',
            'oauth_token' => 'testToken',
            'oauth_signature' => $signature,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => $clock->getDateTime()->getTimestamp(),
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
        $now = new \DateTime('now');
        $extendedInterval = $this->oauthServerService->getRequestTokenInterval() * 2;
        $interval= new \DateInterval('PT' . $extendedInterval . 'S');

        $futureTimestamp = $now->add($interval);
        $clock = new FrozenClock($futureTimestamp);
        $requestParameters['oauth_timestamp'] = $clock->getDateTime()->getTimestamp();
        $consumerSecret = 'kd94hf93k423kf44';
        $tokenSecret = 'pfkkdhi9sl3r4s00';
        $signature = $this->calculateSignature($requestParameters, $consumerSecret, $tokenSecret);
        $requestParameters['oauth_signature'] = $signature;
        $this->oauthServerService->addSignatureService($this->signatureService);

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\HttpException', 'timestamp_refused');

        $hasBeenValidated = $this->oauthServerService->validateRequest(
            $requestParameters,
            $this->requestMethod,
            $this->requestUrl
        );
    }

    /**
     * A helper function to calculate a signature. Necessary because we need recent timestamps.
     *
     * @param array $requestParameters
     * @param string $consumerSecret
     * @param string $tokenSecret
     * @return string
     */
    public function calculateSignature($requestParameters, $consumerSecret, $tokenSecret)
    {
        $normalizedParameters = $this->oauthServerService->normalizeRequestParameters($requestParameters);

        $signatureBaseString = $this->oauthServerService->getSignatureBaseString(
            $this->signatureService,
            $this->requestMethod,
            $this->requestUrl,
            $normalizedParameters
        );

        $oauthSignature = $this->signatureService->sign($signatureBaseString, $consumerSecret, $tokenSecret);

        return $oauthSignature;
    }
}
