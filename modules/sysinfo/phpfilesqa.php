<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

$warnings = phpChecker::checkFileContents();
$ezgeshi_available = sysInfoTools::ezgeshiAvailable();

if ( $Params['viewmode'] == 'json' )
{
    header( 'Content-Type: application/json' );
    //header( "Last-Modified: $mdate" );
    echo json_encode( $warnings );
    eZExecution::cleanExit();
}

$tpl->setVariable( 'warnings', $warnings );
$tpl->setVariable( 'ezgeshi_available', $ezgeshi_available );

?>