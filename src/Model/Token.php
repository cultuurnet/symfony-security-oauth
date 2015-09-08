<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 11:04
 */

namespace CultuurNet\SymfonySecurityOAuth\Model;

class Token implements TokenInterface
{
    protected $id;
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var int
     */
    protected $expiresAt;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * @var string
     */
    protected $consumer;

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * {@inheritDoc}
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * {@inheritDoc}
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * {@inheritDoc}
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExpiresIn()
    {
        if ($this->expiresAt) {
            return $this->expiresAt - time();
        }
        return PHP_INT_MAX;
    }

    /**
     * {@inheritDoc}
     */
    public function hasExpired()
    {
        if ($this->expiresAt) {
            return time() > $this->expiresAt;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * {@inheritDoc}
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setConsumer(ConsumerInterface $consumer)
    {
        $this->consumer = $consumer;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getConsumer()
    {
        return $this->consumer;
    }
}
