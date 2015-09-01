<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 01/09/15
 * Time: 11:00
 */

namespace CultuurNet\SymfonySecurityOAuth;

use CultuurNet\UitidCredentials\Entities\Consumer;
use CultuurNet\UitidCredentials\Entities\User;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class OAuthToken extends AbstractToken
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

    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        // If the user has roles, consider it authenticated
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {

    }
}
