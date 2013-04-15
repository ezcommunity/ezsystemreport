<?php
/**
 * @copyright (C) eZ Systems AS 2010-2013
 * @license Licensed under GNU General Public License v2.0. See file license.txt
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