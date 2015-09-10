<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 10:59
 */

namespace CultuurNet\SymfonySecurityOAuth\Model;

interface ConsumerInterface
{
    /**
     * Returns the consumer name.
     * @return string The consumer name.
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return self
     */
    public function setName($name);

    /**
     * Returns the consumer key.
     * @return string The consumer key.
     */
    public function getConsumerKey();

    /**
     * Set consumerKey
     * @param string $consumerKey
     * @return self
     */
    public function setConsumerKey($consumerKey);

    /**
     * Returns the consumer secret.
     * @return string The consumer secret.
     */
    public function getConsumerSecret();

    /**
     * Set consumerSecret
     * @param string $consumerSecret
     * @return self
     */
    public function setConsumerSecret($consumerSecret);
}
