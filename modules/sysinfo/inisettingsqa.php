<?php
/**
 * @copyright (C) eZ Systems AS 2010-2013
 * @license Licensed under GNU General Public License v2.0. See file license.txt
 */

$warnings = iniChecker::checkFileContents();
$ezgeshi_available = sysInfoTools::ezgeshiAvailable();

$tpl->setVariable( 'warnings', $warnings );
$tpl->setVariable( 'ezgeshi_available', $ezgeshi_available );

?>