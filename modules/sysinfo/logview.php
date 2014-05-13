<?php
/**
 * Dsiplay a table of messages by parsing a log file
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @todo add support for user-selected start and end date (offset/limit?)
 * @todo add support for not showing older (rotated) logs
 */

$errormsg = 'File not found';
$data = array();
$cachedir = eZSys::cacheDirectory() . '/sysinfo';
$logname = '';

// nb: this dir is calculated the same way as ezlog does
$debug = eZDebug::instance();
$logfiles = $debug->logFiles();
foreach( $logfiles as $level => $file )
{
    if ( $file[1] == $Params['logfile'] . '.log' )
    {

        $logfile = $file[0] . $file[1];
        $logname = $Params['logfile'];

        if ( file_exists( $logfile ) )
        {
            $errormsg = '';

            /// @todo add support for if-modified-since, etag headers
            if ( $Params['viewmode'] == 'raw' )
            {
                /// @todo we could be more efficient by echoing files to screen without reading all of them into memory all at once
                $data = '';
                for( $i = eZdebug::maxLogrotateFiles(); $i > 0; $i-- )
                {
                    $archivelog = $logfile.".$i";
                    if ( file_exists( $archivelog ) )
                    {
                        $data .= file_get_contents( $archivelog );
                    }
                }
                $data .= file_get_contents( $logfile );
                $mdate = gmdate( 'D, d M Y H:i:s', filemtime( $logfile ) ) . ' GMT';

                header( 'Content-Type: text/plain' );
                header( "Last-Modified: $mdate" );
                echo $data;
                eZExecution::cleanExit();
            }

            // *** parse rotated log files, if found ***
            for( $i = eZdebug::maxLogrotateFiles(); $i > 0; $i-- )
            {
                $archivelog = $logfile.".$i";
                if ( file_exists( $archivelog ) )
                {
                    $data = array_merge( $data, ezLogsGrapher::splitLog( $archivelog ) );
                    //var_dump( $archivelog );
                }
            }

            // *** Parse log file ***
            $data = array_reverse( array_merge( $data, ezLogsGrapher::splitLog( $logfile ) ) );
            $mdate = gmdate( 'D, d M Y H:i:s', filemtime( $logfile ) ) . ' GMT';
            header( "Last-Modified: $mdate" );
        }
        break;
    }
}

if ( $Params['viewmode'] == 'raw' )
{
    // if we're here it's because desired file was not found
    // @todo return a 404 error?
    //       It can be either a valid filename but no log yet, or bad filename...
}

// *** output ***

$tpl->setVariable( 'log', $data );
$tpl->setVariable( 'errormsg', $errormsg );
$tpl->setVariable( 'title', sysinfoModule::viewTitle( 'logview' ) . ': ' . $Params['logfile'] ); // washed in tpl for safety

$extra_path = $logname;

?>