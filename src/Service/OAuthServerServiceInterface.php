<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 12:14
 */

namespace CultuurNet\SymfonySecurityOAuth\Service;

interface OAuthServerServiceInterface
{
    /**
     * Returns the token provider in use.
     *
     * @return \CultuurNet\SymfonySecurityOAuth\Model\Provider\TokenProviderInterface
     */
    public function getTokenProvider();

    /**
     * Returns the clock provider in use.
     *
     * @return \CultuurNet\Clock\Clock
     */
    public function getClock();

    /**
     * Returns the consumer provider in use.
     *
     * @return \CultuurNet\SymfonySecurityOAuth\Model\Provider\ConsumerProviderInterface
     */
    public function getConsumerProvider();

    /**
     * Accessing Protected Resources
     *
     * @see http://oauth.net/core/1.0a/#anchor46
     *
     * @param  array   $requestParameters An array of request parameters.
     * @param  string  $requestMethod     The request method (GET, POST, ...)
     * @param  string  $requestUrl        The request URL (http://oauth.net/core/1.0/#rfc.section.9.1.2)
     * @return boolean
     */
    public function validateRequest($requestParameters, $requestMethod, $requestUrl);
}
