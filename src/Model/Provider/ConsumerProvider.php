<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 10:55
 */

namespace CultuurNet\SymfonySecurityOAuth\Model\Provider;

use CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface;
use CultuurNet\SymfonySecurityOAuth\Util\Random;

class ConsumerProvider implements ConsumerProviderInterface
{
    /**
     * @var string
     */
    private $consumerClass;

    /**
     * Constructor
     *
     * @param string $consumerClass
     */
    public function __construct($consumerClass)
    {
        $this->consumerClass = $consumerClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getConsumerClass()
    {
        return $this->consumerClass;
    }

    /**
     * {@inheritDoc}
     */
    public function createConsumer($name, $callback = null)
    {
        $class = $this->getConsumerClass();

        /** @var \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface $consumer */
        $consumer = new $class;
        $consumer->setName($name);
        $consumer->setConsumerKey(Random::generateToken());
        $consumer->setConsumerSecret(Random::generateToken());
        $consumer->setCallback($callback);

        $this->updateConsumer($consumer);

        return $consumer;
    }

    /**
     * {@inheritDoc}
     */
    public function getConsumerByKey($consumerKey)
    {
        return $this->getConsumerBy(array('consumerKey' => $consumerKey));
    }

    /**
     * @param array $criteria
     * @return \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface
     */
    public function getConsumerBy(array $criteria)
    {
        // TODO: Implement getConsumerBy() method.
    }

    /**
     * Deletes a consumer.
     *
     * @param ConsumerInterface $consumer
     * @return void
     */
    public function deleteConsumer(ConsumerInterface $consumer)
    {
        // TODO: Implement deleteConsumer() method.
    }

    /**
     * Updates a consumer.
     *
     * @param ConsumerInterface $consumer
     * @return void
     */
    public function updateConsumer(ConsumerInterface $consumer)
    {
        // TODO: Implement updateConsumer() method.
    }
}
