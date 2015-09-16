<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 13:01
 */

namespace CultuurNet\SymfonySecurityOAuth\Service;

use CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface;
use CultuurNet\SymfonySecurityOAuth\Model\TokenInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthServerService extends OAuthAbstractServerService
{
    /**
     * {@inheritdoc}
     * This method has been overrided to add custom exception if error.
     */
    protected function getSignatureService($signatureServiceName)
    {
        $signatureService = parent::getSignatureService($signatureServiceName);

        if (null === $signatureService) {
            // Unsupported signature method
            throw new HttpException(400, self::ERROR_SIGNATURE_METHOD_REJECTED);
        }

        return $signatureService;
    }

    /**
     * Proxy method that handles the error logic.
     * Returns a consumer based on its key.
     *
     * @param  string            $oauth_consumer_key A consumer key.
     * @return ConsumerInterface A consumer or <code>null</code>.
     */
    protected function getConsumerByKey($oauth_consumer_key)
    {
        $consumer = $this->consumerProvider->getConsumerByKey($oauth_consumer_key);

        return $this->checkConsumer($consumer);
    }

    /**
     * Check that the given parameter is a valid consumer.
     *
     * @param  mixed             $consumer Should be a consumer object.
     * @return ConsumerInterface A consumer.
     */
    protected function checkConsumer($consumer)
    {
        if (! $consumer instanceof ConsumerInterface) {
            throw new HttpException(401, self::ERROR_CONSUMER_KEY_UNKNOWN);
        }

        return $consumer;
    }

    /**
     * Handles the logic to validate mandatory parameters.
     *
     * @param array $requestParameters  An array of request parameters.
     * @param array $requiredParameters An array of required parameter names.
     */
    protected function checkRequirements($requestParameters, array $requiredParameters = array())
    {
        if (null === $requestParameters) {
            throw new HttpException(400, self::ERROR_PARAMETER_ABSENT);
        }

        foreach ($requiredParameters as $requiredParameter) {
            if (false === array_key_exists($requiredParameter, $requestParameters)) {
                throw new HttpException(400, self::ERROR_PARAMETER_ABSENT);
            }
        }

        if (false === $this->checkTimestamp($requestParameters['oauth_timestamp'])) {
            throw new HttpException(400, self::ERROR_TIMESTAMP_REFUSED);
        }

        if (isset($requestParameters['oauth_version']) &&
            false === $this->checkVersion($requestParameters['oauth_version'])) {
            // 'oauth_version' is an optional parameter but presents, it must be equal to OAUTH_VERSION.
            throw new HttpException(400, self::ERROR_VERSION_REJECTED);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validateRequest($requestParameters, $requestMethod, $requestUrl)
    {
        $this->checkRequirements($requestParameters, $this->requiredParamsForValidRequest);

        $consumer = $this->getConsumerByKey($requestParameters['oauth_consumer_key']);
        $token    = $this->tokenProvider->getAccessTokenByToken($requestParameters['oauth_token']);

        if (false === $this->nonceProvider->checkNonceAndTimestampUnicity(
            $requestParameters['oauth_nonce'],
            $requestParameters['oauth_timestamp'],
            $consumer
        )) {
            throw new HttpException(400, self::ERROR_NONCE_USED);
        } else {
            $this->nonceProvider->registerNonceAndTimestamp(
                $requestParameters['oauth_nonce'],
                $requestParameters['oauth_timestamp'],
                $consumer
            );
        }

        if (! $token instanceof TokenInterface) {
            throw new HttpException(401, self::ERROR_TOKEN_REJECTED);
        }

        if (true !== $this->approveSignature($consumer, $requestParameters, $requestMethod, $requestUrl, $token)) {
            throw new HttpException(401, self::ERROR_SIGNATURE_INVALID);
        }

        return true;
    }
}
