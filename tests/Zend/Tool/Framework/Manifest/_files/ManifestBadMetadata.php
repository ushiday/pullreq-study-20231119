<?php
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
 * @package    Zend_Tool
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

require_once 'Zend/Tool/Framework/Manifest/MetadataManifestable.php';
require_once 'Zend/Tool/Framework/Metadata/Basic.php';

/**
 * @category   Zend
 * @package    Zend_Tool
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Tool_Framework_Manifest_ManifestBadMetadata implements Zend_Tool_Framework_Manifest_MetadataManifestable
{
    public function getMetadata()
    {
        return [
            new Zend_Tool_Framework_Metadata_Basic(['name' => 'FooTwo', 'value' => 'Baz1']),
            new ArrayObject()
            ];
    }
}
