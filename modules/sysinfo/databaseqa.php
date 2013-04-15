<?php
/**
 * @copyright (C) eZ Systems AS 2012-2013
 * @license Licensed under GNU General Public License v2.0. See file license.txt
 */

$warnings = dbChecker::checkDatabase();

$tpl->setVariable( 'warnings', $warnings );

?>