<?php

/**
 * Test file, used to check whether Util::find() correctly handles
 * callback names in requests.
 *
 * PHP version 5.3
 *
 * @category  Net
 * @package   PEAR2_Net_RouterOS
 * @author    Vasil Rangelov <boen.robot@gmail.com>
 * @copyright 2011 Vasil Rangelov
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL License 2.1
 * @version   1.0.0b6
 * @link      http://pear2.php.net/PEAR2_Net_RouterOS
 */

/**
 * Used at the function.
 */
use PEAR2\Net\RouterOS\Response;
use PEAR2\Net\RouterOS\Test\Util as Test;

/**
 * Checks whether the queue item's target is the HOSTNAME_INVALID constant.
 *
 * @param Response $item The item to check.
 *
 * @return bool TRUE on success, FALSE on failure.
 */
function isHostnameInvalid(Response $item)
{
    $target = $item->getProperty('target');
    if (!is_string($target)) {
        $target = stream_get_contents($target);
    }
    return $target === Test\HOSTNAME_INVALID . '/32';
}
