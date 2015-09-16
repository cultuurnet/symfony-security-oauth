<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 10/09/15
 * Time: 19:15
 */

namespace CultuurNet\SymfonySecurityOAuth\Model;

class ConsumerTest extends \PHPUnit_Framework_TestCase
{
    public function testConsumerProperties()
    {
        $consumer = new Consumer();
        $consumerKey = 'testConsumerKey';
        $consumer->setConsumerKey($consumerKey);
        $consumerSecret = 'testConsumerSecret';
        $consumer->setConsumerSecret($consumerSecret);
        $consumerName = 'testConsumer';
        $consumer->setName($consumerName);

        $this->assertEquals($consumerKey, $consumer->getConsumerKey());
        $this->assertEquals($consumerSecret, $consumer->getConsumerSecret());
        $this->assertEquals($consumerName, $consumer->getName());
    }
}
