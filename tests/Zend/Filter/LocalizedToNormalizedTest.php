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
 * @package    Zend_Filter
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see Zend_Filter_LocalizedToNormalized
 */
require_once 'Zend/Filter/LocalizedToNormalized.php';

/**
 * @category   Zend
 * @package    Zend_Filter
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Filter
 */
class Zend_Filter_LocalizedToNormalizedTest extends TestCase
{
    /**
     * Ensures that the filter follows expected behavior
     *
     * @return void
     */
    public function testNumberNormalization()
    {
        $filter = new Zend_Filter_LocalizedToNormalized(['locale' => 'de']);
        $valuesExpected = [
            '0' => '0',
            '1.234' => '1234',
            '1,234' => '1.234',
            '1.234,56' => '1234.56',
            '-1.234' => '-1234',
            '-1.234,56' => '-1234.56'
        ];

        foreach ($valuesExpected as $input => $output) {
            $this->assertEquals($output, $filter->filter($input), 'failed filter of ' . var_export($input, 1));
        }
    }

    /**
     * Ensures that the filter follows expected behavior
     *
     * @return void
     */
    public function testDateNormalizationWithoutParameters()
    {
        $filter = new Zend_Filter_LocalizedToNormalized(['locale' => 'de']);
        $valuesExpected = [
            '11:22:33' => [
                'date_format' => 'HH:mm:ss',
                'locale' => 'de',
                'hour' => '11',
                'minute' => '22',
                'second' => '33'],
            '20.04.2009' => [
                'date_format' => 'dd.MM.y',
                'locale' => 'de',
                'day' => '20',
                'month' => '04',
                'year' => '2009'],
            // '20.April.2009' => [
            //     'date_format' => 'dd.MM.y',
            //     'locale'      => 'de',
            //     'day'         => '20',
            //     'month'       => '4',
            //     'year'        => '2009'],
            '20.04.09' => [
                'date_format' => 'dd.MM.y',
                'locale' => 'de',
                'day' => '20',
                'month' => '04',
                'year' => '2009'],
            // '20.April.09'   => [
            //     'date_format' => 'dd.MM.y',
            //     'locale'      => 'de',
            //     'day'         => '20',
            //     'month'       => '04',
            //     'year'        => '2009']
        ];

        foreach ($valuesExpected as $input => $output) {
            $this->assertEquals($output, $filter->filter($input), 'failed filter of ' . var_export($input, 1));
        }
    }

    /**
     * Ensures that the filter follows expected behavior
     *
     * @return void
     */
    public function testDateNormalizationWithParameters()
    {
        $filter = new Zend_Filter_LocalizedToNormalized(['locale' => 'de', 'date_format' => 'yyyy.dd.MM']);
        $valuesExpected = [
            // '2009.20.April' => [
            //     'date_format' => 'yyyy.dd.MM',
            //     'locale'      => 'de',
            //     'day'         => '20',
            //     'month'       => '04',
            //     'year'        => '2009'],
            '2009.20.04' => [
                'date_format' => 'yyyy.dd.MM',
                'locale' => 'de',
                'day' => '20',
                'month' => '04',
                'year' => '2009'],
            '09.20.04' => [
                'date_format' => 'yyyy.dd.MM',
                'locale' => 'de',
                'day' => '20',
                'month' => '04',
                'year' => '2009'],
            // '09.20.April'   => [
            //     'date_format' => 'yyyy.dd.MM',
            //     'locale'      => 'de',
            //     'day'         => '20',
            //     'month'       => '04',
            //     'year'        => '2009']
        ];

        foreach ($valuesExpected as $input => $output) {
            $this->assertEquals($output, $filter->filter($input), 'failed filter of ' . var_export($input, 1));
        }
    }

    /**
     * Ensures that the filter follows expected behavior
     *
     * @return void
     */
    public function testNormalizationToInteger()
    {
        $filter = new Zend_Filter_LocalizedToNormalized(['locale' => 'de', 'precision' => 0]);
        $valuesExpected = [
            '1.234,56' => '1234',
            '1,234' => '1',
            // '1234'     => '1234'
        ];

        foreach ($valuesExpected as $input => $output) {
            $this->assertEquals($output, $filter->filter($input), 'failed filter of ' . var_export($input, 1));
        }
    }

    /**
     * Ensures that the filter follows expected behavior
     *
     * @return void
     */
    public function testNormalizationToFloat()
    {
        $filter = new Zend_Filter_LocalizedToNormalized(['locale' => 'de', 'precision' => 2]);
        $valuesExpected = [
            '1.234,5678' => '1234.56',
            '1,234' => '1.23',
            '1.234' => '1234'
        ];

        foreach ($valuesExpected as $input => $output) {
            $this->assertEquals($output, $filter->filter($input), 'failed filter of ' . var_export($input, 1));
        }
    }

    /**
     * ZF-6532
     */
    public function testLongNumbers()
    {
        $filter = new Zend_Filter_LocalizedToNormalized(['locale' => 'de', 'precision' => 0]);
        $this->assertEquals('1000000', $filter->filter('1.000.000,00'));
        // $this->assertEquals('10000', $filter->filter(10000));

        $this->assertEquals([
            'date_format' => 'dd.MM.y',
            'locale' => 'de',
            'day' => '1',
            'month' => '2',
            'year' => '4'], $filter->filter('1,2.4'));
    }
}
