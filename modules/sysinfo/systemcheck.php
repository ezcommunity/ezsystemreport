<?php
/**
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

$warnings = systemChecker::checkSetupRequirements();

$tpl->setVariable( 'warnings', $warnings );

?>