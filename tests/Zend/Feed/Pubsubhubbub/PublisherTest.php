<?php

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

require_once 'Zend/Feed/Pubsubhubbub/Publisher.php';

/**
 * @category   Zend
 * @package    Zend_Feed
 * @subpackage UnitTests
 * @group      Zend_Feed
 * @group      Zend_Feed_Subsubhubbub
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Feed_Pubsubhubbub_PublisherTest extends TestCase
{
    protected $_publisher = null;

    protected function set_up()
    {
        $client = new Zend_Http_Client();
        Zend_Feed_Pubsubhubbub::setHttpClient($client);
        $this->_publisher = new Zend_Feed_Pubsubhubbub_Publisher();
    }

    public function testAddsHubServerUrl()
    {
        $this->_publisher->addHubUrl('http://www.example.com/hub');
        $this->assertEquals(['http://www.example.com/hub'], $this->_publisher->getHubUrls());
    }

    public function testAddsHubServerUrlsFromArray()
    {
        $this->_publisher->addHubUrls([
            'http://www.example.com/hub', 'http://www.example.com/hub2'
        ]);
        $this->assertEquals([
            'http://www.example.com/hub', 'http://www.example.com/hub2'
        ], $this->_publisher->getHubUrls());
    }

    public function testAddsHubServerUrlsFromArrayUsingSetConfig()
    {
        $this->_publisher->setConfig(['hubUrls' => [
            'http://www.example.com/hub', 'http://www.example.com/hub2'
        ]]);
        $this->assertEquals([
            'http://www.example.com/hub', 'http://www.example.com/hub2'
        ], $this->_publisher->getHubUrls());
    }

    public function testRemovesHubServerUrl()
    {
        $this->_publisher->addHubUrls([
            'http://www.example.com/hub', 'http://www.example.com/hub2'
        ]);
        $this->_publisher->removeHubUrl('http://www.example.com/hub');
        $this->assertEquals([
            1 => 'http://www.example.com/hub2'
        ], $this->_publisher->getHubUrls());
    }

    public function testRetrievesUniqueHubServerUrlsOnly()
    {
        $this->_publisher->addHubUrls([
            'http://www.example.com/hub', 'http://www.example.com/hub2',
            'http://www.example.com/hub'
        ]);
        $this->assertEquals([
            'http://www.example.com/hub', 'http://www.example.com/hub2'
        ], $this->_publisher->getHubUrls());
    }

    public function testThrowsExceptionOnSettingEmptyHubServerUrl()
    {
        $this->expectException(Zend_Feed_Pubsubhubbub_Exception::class);
        $this->_publisher->addHubUrl('');
    }


    public function testThrowsExceptionOnSettingNonStringHubServerUrl()
    {
        $this->expectException(Zend_Feed_Pubsubhubbub_Exception::class);
        $this->_publisher->addHubUrl(123);
    }


    public function testThrowsExceptionOnSettingInvalidHubServerUrl()
    {
        $this->expectException(Zend_Feed_Pubsubhubbub_Exception::class);
        $this->_publisher->addHubUrl('http://');
    }

    public function testAddsUpdatedTopicUrl()
    {
        $this->_publisher->addUpdatedTopicUrl('http://www.example.com/topic');
        $this->assertEquals(['http://www.example.com/topic'], $this->_publisher->getUpdatedTopicUrls());
    }

    public function testAddsUpdatedTopicUrlsFromArray()
    {
        $this->_publisher->addUpdatedTopicUrls([
            'http://www.example.com/topic', 'http://www.example.com/topic2'
        ]);
        $this->assertEquals([
            'http://www.example.com/topic', 'http://www.example.com/topic2'
        ], $this->_publisher->getUpdatedTopicUrls());
    }

    public function testAddsUpdatedTopicUrlsFromArrayUsingSetConfig()
    {
        $this->_publisher->setConfig(['updatedTopicUrls' => [
            'http://www.example.com/topic', 'http://www.example.com/topic2'
        ]]);
        $this->assertEquals([
            'http://www.example.com/topic', 'http://www.example.com/topic2'
        ], $this->_publisher->getUpdatedTopicUrls());
    }

    public function testRemovesUpdatedTopicUrl()
    {
        $this->_publisher->addUpdatedTopicUrls([
            'http://www.example.com/topic', 'http://www.example.com/topic2'
        ]);
        $this->_publisher->removeUpdatedTopicUrl('http://www.example.com/topic');
        $this->assertEquals([
            1 => 'http://www.example.com/topic2'
        ], $this->_publisher->getUpdatedTopicUrls());
    }

    public function testRetrievesUniqueUpdatedTopicUrlsOnly()
    {
        $this->_publisher->addUpdatedTopicUrls([
            'http://www.example.com/topic', 'http://www.example.com/topic2',
            'http://www.example.com/topic'
        ]);
        $this->assertEquals([
            'http://www.example.com/topic', 'http://www.example.com/topic2'
        ], $this->_publisher->getUpdatedTopicUrls());
    }

    public function testThrowsExceptionOnSettingEmptyUpdatedTopicUrl()
    {
        $this->expectException(Zend_Feed_Pubsubhubbub_Exception::class);
        $this->_publisher->addUpdatedTopicUrl('');
    }


    public function testThrowsExceptionOnSettingNonStringUpdatedTopicUrl()
    {
        $this->expectException(Zend_Feed_Pubsubhubbub_Exception::class);
        $this->_publisher->addUpdatedTopicUrl(123);
    }


    public function testThrowsExceptionOnSettingInvalidUpdatedTopicUrl()
    {
        $this->expectException(Zend_Feed_Pubsubhubbub_Exception::class);
        $this->_publisher->addUpdatedTopicUrl('http://');
    }

    public function testAddsParameter()
    {
        $this->_publisher->setParameter('foo', 'bar');
        $this->assertEquals(['foo' => 'bar'], $this->_publisher->getParameters());
    }

    public function testAddsParametersFromArray()
    {
        $this->_publisher->setParameters([
            'foo' => 'bar', 'boo' => 'baz'
        ]);
        $this->assertEquals([
            'foo' => 'bar', 'boo' => 'baz'
        ], $this->_publisher->getParameters());
    }

    public function testAddsParametersFromArrayInSingleMethod()
    {
        $this->_publisher->setParameter([
            'foo' => 'bar', 'boo' => 'baz'
        ]);
        $this->assertEquals([
            'foo' => 'bar', 'boo' => 'baz'
        ], $this->_publisher->getParameters());
    }

    public function testAddsParametersFromArrayUsingSetConfig()
    {
        $this->_publisher->setConfig(['parameters' => [
            'foo' => 'bar', 'boo' => 'baz'
        ]]);
        $this->assertEquals([
            'foo' => 'bar', 'boo' => 'baz'
        ], $this->_publisher->getParameters());
    }

    public function testRemovesParameter()
    {
        $this->_publisher->setParameters([
            'foo' => 'bar', 'boo' => 'baz'
        ]);
        $this->_publisher->removeParameter('boo');
        $this->assertEquals([
            'foo' => 'bar'
        ], $this->_publisher->getParameters());
    }

    public function testRemovesParameterIfSetToNull()
    {
        $this->_publisher->setParameters([
            'foo' => 'bar', 'boo' => 'baz'
        ]);
        $this->_publisher->setParameter('boo', null);
        $this->assertEquals([
            'foo' => 'bar'
        ], $this->_publisher->getParameters());
    }

    public function testNotifiesHubWithCorrectParameters()
    {
        Zend_Feed_Pubsubhubbub::setHttpClient(new Zend_Feed_Pubsubhubbub_PublisherTest_ClientSuccess());
        $client = Zend_Feed_Pubsubhubbub::getHttpClient();
        $this->_publisher->addHubUrl('http://www.example.com/hub');
        $this->_publisher->addUpdatedTopicUrl('http://www.example.com/topic');
        $this->_publisher->setParameter('foo', 'bar');
        $this->_publisher->notifyAll();
        $this->assertEquals('hub.mode=publish&hub.url=http%3A%2F%2Fwww.example.com%2Ftopic&foo=bar', $client->getBody());
    }

    public function testNotifiesHubWithCorrectParametersAndMultipleTopics()
    {
        Zend_Feed_Pubsubhubbub::setHttpClient(new Zend_Feed_Pubsubhubbub_PublisherTest_ClientSuccess());
        $client = Zend_Feed_Pubsubhubbub::getHttpClient();
        $this->_publisher->addHubUrl('http://www.example.com/hub');
        $this->_publisher->addUpdatedTopicUrl('http://www.example.com/topic');
        $this->_publisher->addUpdatedTopicUrl('http://www.example.com/topic2');
        $this->_publisher->notifyAll();
        $this->assertEquals('hub.mode=publish&hub.url=http%3A%2F%2Fwww.example.com%2Ftopic&hub.url=http%3A%2F%2Fwww.example.com%2Ftopic2', $client->getBody());
    }

    public function testNotifiesHubAndReportsSuccess()
    {
        Zend_Feed_Pubsubhubbub::setHttpClient(new Zend_Feed_Pubsubhubbub_PublisherTest_ClientSuccess());
        $client = Zend_Feed_Pubsubhubbub::getHttpClient();
        $this->_publisher->addHubUrl('http://www.example.com/hub');
        $this->_publisher->addUpdatedTopicUrl('http://www.example.com/topic');
        $this->_publisher->setParameter('foo', 'bar');
        $this->_publisher->notifyAll();
        $this->assertTrue($this->_publisher->isSuccess());
    }

    public function testNotifiesHubAndReportsFail()
    {
        Zend_Feed_Pubsubhubbub::setHttpClient(new Zend_Feed_Pubsubhubbub_PublisherTest_ClientFail());
        $client = Zend_Feed_Pubsubhubbub::getHttpClient();
        $this->_publisher->addHubUrl('http://www.example.com/hub');
        $this->_publisher->addUpdatedTopicUrl('http://www.example.com/topic');
        $this->_publisher->setParameter('foo', 'bar');
        $this->_publisher->notifyAll();
        $this->assertFalse($this->_publisher->isSuccess());
    }
}

// Some stubs for what Http_Client would be doing

class Zend_Feed_Pubsubhubbub_PublisherTest_ClientSuccess extends Zend_Http_Client
{
    public function request($method = null)
    {
        $response = new Zend_Feed_Pubsubhubbub_PublisherTest_ResponseSuccess();
        return $response;
    }
    public function getBody()
    {
        return $this->_prepareBody();
    }
}
class Zend_Feed_Pubsubhubbub_PublisherTest_ClientFail extends Zend_Http_Client
{
    public function request($method = null)
    {
        $response = new Zend_Feed_Pubsubhubbub_PublisherTest_ResponseFail();
        return $response;
    }
    public function getBody()
    {
        return $this->_prepareBody();
    }
}
class Zend_Feed_Pubsubhubbub_PublisherTest_ResponseSuccess
{
    public function getStatus()
    {
        return 204;
    }
}
class Zend_Feed_Pubsubhubbub_PublisherTest_ResponseFail
{
    public function getStatus()
    {
        return 404;
    }
}
