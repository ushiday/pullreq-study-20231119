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
 * @package    Zend_Test
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

require_once "Zend/Test/PHPUnit/Db/DataSet/DbTable.php";
require_once "Zend/Db/Table.php";

/**
 * @category   Zend
 * @package    Zend_Test
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Test
 */
class Zend_Test_PHPUnit_Db_DataSet_DbTableTest extends TestCase
{
    public function testLoadDataSetDelegatesWhereLimitOrderBy()
    {
        $fixtureWhere = "where";
        $fixtureLimit = "limit";
        $fixtureOffset = "offset";
        $fixtureOrderBy = "order";

        $table = $this->createMock('Zend_Db_Table');
        $table->expects($this->once())
              ->method('fetchAll')
              ->with($fixtureWhere, $fixtureOrderBy, $fixtureLimit, $fixtureOffset)
              ->will($this->returnValue([]));

        $dataSet = new Zend_Test_PHPUnit_Db_DataSet_DbTable($table, $fixtureWhere, $fixtureOrderBy, $fixtureLimit, $fixtureOffset);
        $count = $dataSet->getRowCount();
    }

    public function testGetTableMetadata()
    {
        $fixtureTableName = "foo";

        $table = $this->createMock('Zend_Db_Table');
        $table->expects($this->at(0))
              ->method('info')
              ->with($this->equalTo('name'))
              ->will($this->returnValue($fixtureTableName));
        $table->expects($this->at(1))
              ->method('info')
              ->with($this->equalTo('cols'))
              ->will($this->returnValue(["foo", "bar"]));
        $table->expects($this->once())
              ->method('fetchAll')
              ->will($this->returnValue([ ["foo" => 1, "bar" => 2] ]));

        $dataSet = new Zend_Test_PHPUnit_Db_DataSet_DbTable($table);

        $this->assertEquals($fixtureTableName, $dataSet->getTableMetaData()->getTableName());
        $this->assertEquals(["foo", "bar"], $dataSet->getTableMetaData()->getColumns());
    }

    public function testLoadDataOnlyCalledOnce()
    {
        $table = $this->createMock('Zend_Db_Table');
        $table->expects($this->once())
              ->method('fetchAll')
              ->will($this->returnValue([ ["foo" => 1, "bar" => 2] ]));

        $dataSet = new Zend_Test_PHPUnit_Db_DataSet_DbTable($table);
        $dataSet->getRow(0);
        $dataSet->getRow(0);
    }
}
