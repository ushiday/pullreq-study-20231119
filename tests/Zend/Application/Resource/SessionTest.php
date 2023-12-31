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
 * @package    Zend_Application
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

require_once "Zend/Application/Resource/ResourceAbstract.php";
require_once "Zend/Application/Resource/Session.php";
require_once "Zend/Session.php";
require_once "Zend/Session/SaveHandler/Interface.php";

/**
 * @category   Zend
 * @package    Zend_Application
 * @subpackage UnitTests
 * @group      Zend_Application
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Application_Resource_SessionTest extends TestCase
{
    public $resource;

    protected function set_up()
    {
        $this->resource = new Zend_Application_Resource_Session();
    }

    public function testSetSaveHandler()
    {
        $saveHandler = $this->createMock('Zend_Session_SaveHandler_Interface');

        $this->resource->setSaveHandler($saveHandler);
        $this->assertSame($saveHandler, $this->resource->getSaveHandler());
    }

    public function testSetSaveHandlerString()
    {
        $saveHandlerClassName = 'Zend_Application_Resource_SessionTestHandlerMock1';
        $saveHandler = $this->getMockBuilder('Zend_Session_SaveHandler_Interface')->setMockClassName($saveHandlerClassName)->getMock();

        $this->resource->setSaveHandler($saveHandlerClassName);

        $this->assertTrue($this->resource->getSaveHandler() instanceof $saveHandlerClassName);
    }

    public function testSetSaveHandlerArray()
    {
        $saveHandlerClassName = 'Zend_Application_Resource_SessionTestHandlerMock2';
        $saveHandler = $this->getMockBuilder('Zend_Session_SaveHandler_Interface')->setMockClassName($saveHandlerClassName)->getMock();

        $this->resource->setSaveHandler(['class' => $saveHandlerClassName]);

        $this->assertTrue($this->resource->getSaveHandler() instanceof $saveHandlerClassName);
    }

    public function testSetOptions()
    {
        Zend_Session::setOptions([
            'use_only_cookies' => false,
            'remember_me_seconds' => 3600,
        ]);

        $this->resource->setOptions([
             'use_only_cookies' => true,
             'remember_me_seconds' => 7200,
        ]);

        $this->resource->init();

        $this->assertEquals('on', Zend_Session::getOptions('use_only_cookies'));
        $this->assertEquals(7200, Zend_Session::getOptions('remember_me_seconds'));
    }

    public function testInitSetsSaveHandler()
    {
        Zend_Session::$_unitTestEnabled = true;

        $saveHandler = $this->createMock('Zend_Session_SaveHandler_Interface');

        $this->resource->setSaveHandler($saveHandler);

        $this->resource->init();

        $this->assertSame($saveHandler, Zend_Session::getSaveHandler());
    }
}
