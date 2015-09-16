<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/09/15
 * Time: 11:15
 */

namespace CultuurNet\SymfonySecurityOAuth\Service;

use CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface;
use CultuurNet\SymfonySecurityOAuth\Model\TokenInterface;
use CultuurNet\SymfonySecurityOAuth\Service\Signature\OAuthSignatureInterface;

class OAuthServerServiceMock extends OAuthServerService
{
    public function getSignatureBaseString(
        OAuthSignatureInterface $signatureService,
        $requestMethod,
        $requestUrl,
        $normalizedParameters
    ) {
        return parent::getSignatureBaseString(
            $signatureService,
            $requestMethod,
            $requestUrl,
            $normalizedParameters
        );
    }

    public function normalizeRequestParameters($requestParameters)
    {
        return parent::normalizeRequestParameters($requestParameters);
    }

    public function approveSignature(
        ConsumerInterface $consumer,
        $requestParameters,
        $requestMethod,
        $requestUrl,
        TokenInterface $token = null
    ) {
        return parent::approveSignature(
            $consumer,
            $requestParameters,
            $requestMethod,
            $requestUrl,
            $token
        );
    }

    public function checkConsumer($consumer)
    {
        return parent::checkConsumer($consumer);
    }

    public function getSignatureService($signatureServiceName)
    {
        return parent::getSignatureService($signatureServiceName);
    }
}
