<?php
/**
 * A helper class for generating reports
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

class reportGenerator
{
    protected static $ezpClasses = null;

    public function getCSV( $report )
    {
        $out = '';
        foreach ( $report as $test )
        {
            $out .= "----------\n";
            $out .= $test['title'] . "\n";
            $out .= "----------\n";

            if ( @$test['byrow'] )
            {
                foreach( $test['data'] as $key => $val )
                {
                    $out .= "$key, $val\n";
                }
            }
            else
            {
                foreach( $test['data'] as $row )
                {
                    {
                        $out .= implode( ',', $row ) . "\n";
                    }
                }
            }
        }
        return $out;
    }

    /*function writePlaintext( $report )
    {

    }*/

    public function formatBytes( $size, $precision = 2 )
    {
        $base = log( $size ) / log( 1024 );
        $suffixes = array( 'Bytes', 'kB', 'MB', 'GB', 'TB' );

        return round( pow( 1024, $base - floor( $base ) ), $precision ) . $suffixes[floor( $base )];
    }
}

?>