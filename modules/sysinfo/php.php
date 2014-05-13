<?php
/**
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

ob_start();
phpinfo();
$output = ob_get_contents();
ob_end_clean();
$output = preg_replace( array( '#^.*<body>#s','#</body>.*$#s' ), '', $output );

$tpl->setVariable( 'css', 'php.css' );
$tpl->setVariable( 'info', $output );

?>
