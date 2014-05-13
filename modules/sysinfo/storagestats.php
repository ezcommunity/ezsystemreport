<?php
/**
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @todo add more details, such as dates of first/last files
 * @todo add support for db-clustered configs - hard currently, since there is no recursive search in api...
 * @todo in edfs mode allow user to only show local / clustered data
 */

$cacheFilesList = array();

$storagedir = eZSys::storageDirectory();
$files = @scandir( $storagedir );
foreach( $files as $file )
{
    if ( $file != '.' && $file != '..' && is_dir( $storagedir . '/' . $file ) )
    {
        $cacheFilesList[$file] = array(
            'path' => $storagedir . '/' . $file,
            'count' => number_format( sysInfoTools::countFilesInDir( $storagedir . '/' . $file ) ),
            'size' => number_format( sysInfoTools::countFilesSizeInDir( $storagedir . '/' . $file ) ) );
    }
}

$ini = eZINI::instance( 'file.ini' );
if ( $ini->variable( 'ClusteringSettings', 'FileHandler' ) == 'eZDFSFileHandler' )
{
    $storagedir = $ini->variable( 'eZDFSClusteringSettings', 'MountPointPath' ) . '/' . $storagedir;
    $files = @scandir( $storagedir );
    foreach( $files as $file )
    {
        if ( $file != '.' && $file != '..' && is_dir( $storagedir . '/' . $file ) )
        {
            $cacheFilesList["DFS://$file"] = array(
                'path' => $storagedir . '/' . $file,
                'count' => number_format( sysInfoTools::countFilesInDir( $storagedir . '/' . $file ) ),
                'size' => number_format( sysInfoTools::countFilesSizeInDir( $storagedir . '/' . $file ) ) );
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
