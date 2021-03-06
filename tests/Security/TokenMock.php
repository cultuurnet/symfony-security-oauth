<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/09/15
 * Time: 19:32
 */

namespace CultuurNet\SymfonySecurityOAuth\Security;

use CultuurNet\UitidCredentials\Entities\Consumer;
use CultuurNet\UitidCredentials\Entities\User;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class TokenMock extends AbstractToken
{
    /**
     * @var Consumer
     */
    public $consumer;

    /**
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $tokenSecret;

    /**
     * @var User
     */
    public $user;

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
        return '';
    }
}
