<?php
/**
 *
 * @copyright (C) eZ Systems AS 2008-2013
 * @license Licensed under GNU General Public License v2.0. See file license.txt
 */

$tpl->setVariable( 'contentlist', contentStatsGatherer::gather() );

?>
