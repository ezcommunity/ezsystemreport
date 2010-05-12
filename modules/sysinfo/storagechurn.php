<?php
/**
 * Create a graph of files-per-minute by analyzing storage.log
 *
 * @author G. Giunta
 * @version $Id$
 * @copyright (C) G. Giunta 2008-2010
 * @license Licensed under GNU General Public License v2.0. See file license.txt
 *
 * @todo add support for user-selected start and end date
 * @todo support coarser intervals than 60 secs
 * @todo
 */

$module = $Params['Module'];

// rely on system policy instead of creating our own, but allow also PolicyOmitList
$ini = eZINI::instance();
if ( !in_array( 'sysinfo/storagechurn', $ini->variable( 'RoleSettings', 'PolicyOmitList' ) ) )
{
    $user = eZUser::currentUser();
    $access = $user->hasAccessTo( 'setup', 'system_info' );
    if ( $access['accessWord'] != 'yes' )
    {
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

$errormsg = "";
// nb: this dir is calculated the same way as ezlog does
$logfile = eZSys::varDirectory() . '/' . $ini->variable( 'FileSettings', 'LogDir' ) . '/storage.log';
// but storage log also is in var/log (created I think before siteaccess settings are loaded)
$logfile2 = 'var/log/storage.log';

$cachedir = eZSys::cacheDirectory() . '/sysinfo';
$cachefile = $cachedir . '/storagechurn.jpg';

// *** Check if cached image file exists and is younger than storage log
$cachefound = false;
$clusterfile = eZClusterFileHandler::instance( $cachefile );
if ( $clusterfile->exists() )
{
    $logdate = filemtime( $logfile );
    $logdate2 = filemtime( $logfile2 );
    $cachedate = $clusterfile->mtime();
    if ( $cachedate >= $logdate && $cachedate >= $logdate2 )
    {
        $cachefound = true;
        $clusterfile->fetch();
    }
}

if ( !$cachefound )
{

    $scale = 60;
    $scalenames = array( 60 => 'minute', 60*60 => 'hour', 60*60*24 => 'day' );

    // *** Parse storage.log files ***
    $data = ezLogsGrapher::asum( ezLogsGrapher::parseLog( $logfile, $scale ), ezLogsGrapher::parseLog( $logfile2, $scale ) );
    ksort( $data );

    // *** build graph and store it ***
    $graphname = ezi18n( 'SysInfo', 'files per '.$scalenames[$scale] );
    $graph = ezLogsGrapher::graph( $data, $graphname, $scale );
    if ( $graph != false )
    {
        $clusterfile->fileStoreContents( $cachefile, $graph );
    }
}

// *** output ***

require_once( "kernel/common/template.php" );
$tpl = templateInit();
$tpl->setVariable( 'title', 'Storage churn' );
$tpl->setVariable( 'graphsource', $cachefile );
$tpl->setVariable( 'errormsg', $errormsg );

$Result = array();
$Result['content'] = $tpl->fetch( "design:sysinfo/storagechurn.tpl" ); //var_dump($cacheFilesList);

$Result['left_menu'] = 'design:parts/sysinfo/menu.tpl';
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'SysInfo', 'Storage churn' ) ) );
?>