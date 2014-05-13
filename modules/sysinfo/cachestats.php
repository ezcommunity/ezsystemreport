<?php
/**
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @todo add more details, such as dates of first/last files
 * @todo add possibility to zoom in to file list going to cachesearch view
 * @todo add support for db-clustered configs - hard currently, since there is no recursive search in api...
 * @todo in edfs mode allow user to only show local / clustered data
 */

$cacheFilesList = array();

$cacheList = eZCache::fetchList();
foreach ( $cacheList as $cacheItem )
{
    if ( $cacheItem['path'] != false && $cacheItem['enabled'] )
    {
        $cacheFilesList[$cacheItem['name']] = array( 'path' => $cacheItem['path'] );

        // take care: this is hardcoded from knowledge of cache structure...
        if ( $cacheItem['path'] == 'var/cache/ini' )
        {
            $cachedir = eZSys::siteDir() . '/' . $cacheItem['path'];
        }
        else
        {
            $cachedir = eZSys::cacheDirectory() . '/' . $cacheItem['path'];
        }
        $count = sysInfoTools::countFilesInDir( $cachedir );
        $cacheFilesList[$cacheItem['name']]['count'] = $count;
        if ( $count )
        {
            $cacheFilesList[$cacheItem['name']]['size'] = number_format( sysInfoTools::countFilesSizeInDir( $cachedir ) );
        }
        else
        {
            $cacheFilesList[$cacheItem['name']]['size'] = "";
        }
    }
}

$ini = eZINI::instance( 'file.ini' );
if ( $ini->variable( 'ClusteringSettings', 'FileHandler' ) == 'eZDFSFileHandler' )
{
    $storagedir = $ini->variable( 'eZDFSClusteringSettings', 'MountPointPath' );

    foreach ( $cacheList as $cacheItem )
    {
        if ( $cacheItem['path'] != false && $cacheItem['enabled'] )
        {
            $cachename = 'DFS://' . $cacheItem['name'];

            // take care: this is hardcoded from knowledge of cache structure...
            if ( $cacheItem['path'] == 'var/cache/ini' )
            {
                //$cachedir = $storagedir . '/' . eZSys::siteDir() . '/' . $cacheItem['path'];
                // no var/cache/ini in dfs nfs storage
                continue;
            }
            else
            {
                $cachedir = $storagedir . '/' . eZSys::cacheDirectory() . '/' . $cacheItem['path'];
            }
            $cacheFilesList[$cachename] = array( 'path' => $cachedir );
            $count = sysInfoTools::countFilesInDir( $cachedir );
            $cacheFilesList[$cachename]['count'] = $count;
            if ( $count )
            {
                $cacheFilesList[$cachename]['size'] = number_format( sysInfoTools::countFilesSizeInDir( $cachedir ) );
            }
            else
            {
                $cacheFilesList[$cachename]['size'] = "";
            }
        }
    }

}

if ( $Params['viewmode'] == 'json' )
{
    header( 'Content-Type: application/json' );
    //header( "Last-Modified: $mdate" );
    echo json_encode( $cacheFilesList );
    eZExecution::cleanExit();
}

$tpl->setVariable( 'filelist', $cacheFilesList );

?>
