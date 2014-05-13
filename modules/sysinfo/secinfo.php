<?php
/**
 * Tests security of eZ Publish install. Based on phpsecinfo tests
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
*/

$psi = new PhpSecInfo();
$psi->loadAndRun();
$results = $psi->getResultsAsArray();

// suppress unwanted results
unset( $results['test_results']['Suhosin'] );
unset( $results['test_results']['Core']['file_uploads'] );

$tpl->setVariable( 'results', $results );

?>