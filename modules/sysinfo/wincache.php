<?php
/**
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

$extdir =  eZExtension::baseDirectory();
ob_start();
include( $extdir . '/ggsysinfo/modules/sysinfo/lib/wincache.php' );
$output = ob_get_contents();
ob_end_clean();
$output = preg_replace( array( '#^.*<body>#s','#</body>.*$#s' ), '', $output );

$tpl->setVariable( 'css', 'wincache.css' );
$tpl->setVariable( 'info', $output );

?>
