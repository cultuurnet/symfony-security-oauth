<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/09/15
 * Time: 11:15
 */

namespace CultuurNet\SymfonySecurityOAuth\Service;

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
}
