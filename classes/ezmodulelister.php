<?php
/**
 * Class that scans all active module definitions
 * Copied here from ezwebservicesapi and renamed to avoid clashes
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

class eZModuleLister
{

    /**
    * Finds all available modules in the system
    * @return array $modulename => $path
    */
    static function getModuleList()
    {
        $out = array();
        foreach ( eZModule::globalPathList() as $path )
        {
            foreach ( scandir( $path ) as $subpath )
            {
                if ( $subpath != '.' && $subpath != '..' && is_dir( $path . '/' . $subpath ) && file_exists( $path . '/' . $subpath . '/module.php' ) )
                {
                    $out[$subpath] = $path . '/' . $subpath . '/module.php';
                }
            }
        }
        return $out;
    }

    /**
    * @return array
    */
    /*static function analyzeModule( $path )
    {

    }*/
}

?>