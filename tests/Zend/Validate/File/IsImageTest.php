<?php

use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\TestRunner;

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
 * @package    Zend_Validate_File
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

// Call Zend_Validate_File_IsImageTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "Zend_Validate_File_IsImageTest::main");
}

/**
 * @see Zend_Validate_File_IsImage
 */
require_once 'Zend/Validate/File/IsImage.php';

/**
 * IsImage testbed
 *
 * @category   Zend
 * @package    Zend_Validate_File
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Validate
 */
class Zend_Validate_File_IsImageTest extends TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite = new TestSuite("Zend_Validate_File_IsImageTest");
        $result = (new resources_Runner())->run($suite);
    }

    /**
     * Ensures that the validator follows expected behavior
     *
     * @return void
     */
    public function testBasic()
    {
        $valuesExpected = [
            [null, true],
            ['jpeg', true],
            ['test/notype', false],
            ['image/gif, image/jpeg', true],
            [['image/vasa', 'image/jpeg'], true],
            [['image/jpeg', 'gif'], true],
            [['image/gif', 'gif'], false],
        ];

        $files = [
            'name' => 'picture.jpg',
            'type' => 'image/jpeg',
            'size' => 200,
            'tmp_name' => dirname(__FILE__) . '/_files/picture.jpg',
            'error' => 0
        ];

        foreach ($valuesExpected as $element) {
            $validator = new Zend_Validate_File_IsImage($element[0]);
            $validator->enableHeaderCheck();
            $this->assertEquals(
                $element[1],
                $validator->isValid(dirname(__FILE__) . '/_files/picture.jpg', $files),
                "Tested with " . var_export($element, 1)
            );
        }
    }

    /**
     * Ensures that getMimeType() returns expected value
     *
     * @return void
     */
    public function testGetMimeType()
    {
        $validator = new Zend_Validate_File_IsImage('image/gif');
        $this->assertEquals('image/gif', $validator->getMimeType());

        $validator = new Zend_Validate_File_IsImage(['image/gif', 'video', 'text/test']);
        $this->assertEquals('image/gif,video,text/test', $validator->getMimeType());

        $validator = new Zend_Validate_File_IsImage(['image/gif', 'video', 'text/test']);
        $this->assertEquals(['image/gif', 'video', 'text/test'], $validator->getMimeType(true));
    }

    /**
     * Ensures that setMimeType() returns expected value
     *
     * @return void
     */
    public function testSetMimeType()
    {
        $validator = new Zend_Validate_File_IsImage('image/gif');
        $validator->setMimeType('image/jpeg');
        $this->assertEquals('image/jpeg', $validator->getMimeType());
        $this->assertEquals(['image/jpeg'], $validator->getMimeType(true));

        $validator->setMimeType('image/gif, text/test');
        $this->assertEquals('image/gif,text/test', $validator->getMimeType());
        $this->assertEquals(['image/gif', 'text/test'], $validator->getMimeType(true));

        $validator->setMimeType(['video/mpeg', 'gif']);
        $this->assertEquals('video/mpeg,gif', $validator->getMimeType());
        $this->assertEquals(['video/mpeg', 'gif'], $validator->getMimeType(true));
    }

    /**
     * Ensures that addMimeType() returns expected value
     *
     * @return void
     */
    public function testAddMimeType()
    {
        $validator = new Zend_Validate_File_IsImage('image/gif');
        $validator->addMimeType('text');
        $this->assertEquals('image/gif,text', $validator->getMimeType());
        $this->assertEquals(['image/gif', 'text'], $validator->getMimeType(true));

        $validator->addMimeType('jpg, to');
        $this->assertEquals('image/gif,text,jpg,to', $validator->getMimeType());
        $this->assertEquals(['image/gif', 'text', 'jpg', 'to'], $validator->getMimeType(true));

        $validator->addMimeType(['zip', 'ti']);
        $this->assertEquals('image/gif,text,jpg,to,zip,ti', $validator->getMimeType());
        $this->assertEquals(['image/gif', 'text', 'jpg', 'to', 'zip', 'ti'], $validator->getMimeType(true));

        $validator->addMimeType('');
        $this->assertEquals('image/gif,text,jpg,to,zip,ti', $validator->getMimeType());
        $this->assertEquals(['image/gif', 'text', 'jpg', 'to', 'zip', 'ti'], $validator->getMimeType(true));
    }

    /**
     * @ZF-8111
     */
    public function testErrorMessages()
    {
        $files = [
            'name' => 'picture.jpg',
            'type' => 'image/jpeg',
            'size' => 200,
            'tmp_name' => dirname(__FILE__) . '/_files/picture.jpg',
            'error' => 0
        ];

        $validator = new Zend_Validate_File_IsImage('test/notype');
        $validator->enableHeaderCheck();
        $this->assertFalse($validator->isValid(dirname(__FILE__) . '/_files/picture.jpg', $files));
        $error = $validator->getMessages();
        $this->assertTrue(array_key_exists('fileIsImageFalseType', $error));
    }

    public function testOptionsAtConstructor()
    {
        if (!extension_loaded('fileinfo')) {
            $this->markTestSkipped('This PHP Version has no finfo installed');
        }

        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            $magicFile = dirname(__FILE__) . '/_files/magic-php53.mime';
        } else {
            $magicFile = dirname(__FILE__) . '/_files/magic.mime';
        }

        $validator = new Zend_Validate_File_IsImage([
            'image/gif',
            'image/jpg',
            'magicfile' => $magicFile,
            'headerCheck' => true]);

        $this->assertEquals($magicFile, $validator->getMagicFile());
        $this->assertTrue($validator->getHeaderCheck());
        $this->assertEquals('image/gif,image/jpg', $validator->getMimeType());
    }
}

// Call Zend_Validate_File_IsImage::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD === "Zend_Validate_File_IsImage::main") {
    Zend_Validate_File_IsImage::main();
}
