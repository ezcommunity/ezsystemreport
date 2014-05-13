<?php
/**
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

$extdir =  eZExtension::baseDirectory();
ob_start();
include( $extdir . '/ggsysinfo/modules/sysinfo/lib/control.php' );
$output = ob_get_contents();
ob_end_clean();
$pos = strpos( $output, '<body class="center">' );
$output = substr( $output, $pos + 21 ); // bad day with preg replace. switch to dumb mode...
$output = preg_replace( array( /*'#^.*?body"#s',*/ '#</body>.*$#s' ), '', $output );

$tpl->setVariable( 'css', 'eaccelerator.css' );
$tpl->setVariable( 'info', $output );

?>
