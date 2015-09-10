<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/09/15
 * Time: 11:04
 */

namespace CultuurNet\SymfonySecurityOAuth\Service;

use CultuurNet\SymfonySecurityOAuth\Model\Consumer;
use CultuurNet\SymfonySecurityOAuth\Model\Provider\ConsumerProviderInterface;

class ConsumerProviderMock implements ConsumerProviderInterface
{

    /**
     * @param $consumerKey
     * @return \CultuurNet\SymfonySecurityOAuth\Model\ConsumerInterface
     */
    public function getConsumerByKey($consumerKey)
    {
        $consumer = new Consumer();
        $consumer->setConsumerKey('dpf43f3p2l4k3l03');
        $consumer->setConsumerSecret('kd94hf93k423kf44');
        $consumer->setName('testConsumer');

        return $consumer;
    }
}
