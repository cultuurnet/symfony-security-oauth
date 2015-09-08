<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 08/09/15
 * Time: 10:53
 */

namespace CultuurNet\SymfonySecurityOAuth\Model\Provider;

use CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface;

interface ConsumerProviderInterface
{
    /**
     * Returns the consumer's fully qualified class name.
     *
     * @return string
     */
    public function getConsumerClass();

    /**
     * Create a consumer.
     *
     * @param string      $name
     * @param string|null $callback
     * @return \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface
     */
    public function createConsumer($name, $callback = null);

    /**
     * @param array $criteria
     * @return \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface
     */
    public function getConsumerBy(array $criteria);

    /**
     * @param $consumerKey
     * @return \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface
     */
    public function getConsumerByKey($consumerKey);

    /**
     * Deletes a consumer.
     *
     * @param ConsumerInterface $consumer
     * @return void
     */
    public function deleteConsumer(ConsumerInterface $consumer);

    /**
     * Updates a consumer.
     *
     * @param ConsumerInterface $consumer
     * @return void
     */
    public function updateConsumer(ConsumerInterface $consumer);
}
