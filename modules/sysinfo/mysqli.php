<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
$mysqlnd_available = false;
if ( function_exists( 'mysqli_get_client_stats' ) )
{
    $stats = mysqli_get_client_stats();
    $mysqlnd_available = true;
}

$tpl->setVariable( 'stats', $stats );
$tpl->setVariable( 'mysqlnd_available', $mysqlnd_available );
$tpl->setVariable( 'important_stats', array( 'slow_queries', 'connect_failure' ) );

?>