<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 01/09/15
 * Time: 11:00
 */

namespace CultuurNet\SymfonySecurityOAuth\Security;

use CultuurNet\SymfonySecurityOAuth\Model\Token;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class OAuthToken extends AbstractToken
{
    /**
     * @var array
     */
    protected $requestParameters;

    /**
     * @var string
     */
    protected $requestMethod;

    /**
     * @var string
     */
    protected $requestUrl;

    /**
     * @var Token|null
     */
    protected $accessToken;

    /**
     * @param array $parameters An array of request parameters.
     */
    public function setRequestParameters($parameters)
    {
        $this->requestParameters = $parameters;
    }

    /**
     * @return array An array of request parameters.
     */
    public function getRequestParameters()
    {
        return $this->requestParameters;
    }

    /**
     * @param string $requestMethod A request method.
     */
    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }

    /**
     * @return string The request method.
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @param string $requestUrl A request URL.
     */
    public function setRequestUrl($requestUrl)
    {
        $this->requestUrl = $requestUrl;
    }

    /**
     * @return string The request URL.
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials()
    {
        // @TODO Implement this necessary method.
        return '';
    }

    /**
     * @return Token|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param Token $accessToken
     * @return OAuthToken
     */
    public function authenticated(Token $accessToken)
    {
        $token = clone $this;
        $token->accessToken = $accessToken;
        $token->setUser($accessToken->getUser());
        $token->setAuthenticated(true);

        return $token;
    }
}
