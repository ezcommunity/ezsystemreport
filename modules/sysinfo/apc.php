<?php
/**
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

$extdir =  eZExtension::baseDirectory();
// patch variable used by apc.php to build urls
$self = $_SERVER['PHP_SELF'];
$url = 'sysinfo/apc';
eZURI::transformURI( $url );
$_SERVER['PHP_SELF'] = $url;
ob_start();
include( $extdir . '/ggsysinfo/modules/sysinfo/lib/apc.php' );
$output = ob_get_contents();
ob_end_clean();

$_SERVER['PHP_SELF'] = $self;

// the included file is also used to generate images (links to self)
if ( isset( $_GET['IMG'] ) )
{
    echo $output;
    eZExecution::cleanExit();
}

$output = preg_replace( array( '#^.*<body>#s','#</body>.*$#s' ), '', $output );

$tpl->setVariable( 'css', 'apc.css' );
$tpl->setVariable( 'info', $output );

?>
