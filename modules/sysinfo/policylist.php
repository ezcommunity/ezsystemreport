<?php
/**
 * List all existing policy functions (optionally, in a given module)
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 */

// generic info for all views: module name, extension name, ...
$policyList = array();
$modules = eZModuleLister::getModuleList();
if ( $Params['modulename'] != '' && !array_key_exists( $Params['modulename'], $modules) )
{
    /// @todo
}
else
{

    foreach( $modules as $modulename => $path )
    {
        if ( $Params['modulename'] == '' || $Params['modulename'] == $modulename )
        {
            $module = eZModule::exists( $modulename );
            if ( $module instanceof eZModule )
            {
                $extension = '';
                if ( preg_match( '#extension/([^/]+)/modules/#', $path, $matches ) )
                {
                    $extension = $matches[1];
                }
                foreach( $module->attribute( 'available_functions' ) as $policyname => $policy )
                {
                    // merge empty array to facilitate life of templates
                    $policy = array( 'name' => $policyname, 'limitations' => $policy );
                    $policyList[$policyname . '_' . $modulename] = $policy;
                    $policyList[$policyname . '_' . $modulename]['module'] = $modulename;
                    $policyList[$policyname . '_' . $modulename]['extension'] = $extension;
                }
            }
        }
    }
    ksort( $policyList );
}

$title = 'List of available policy functions';
if ( $Params['modulename'] != '' )
{
    $title .= ' in module "' . $Params['modulename'] . '"';
    $extra_path = $Params['modulename'];
}

$tpl->setVariable( 'title', $title );
$tpl->setVariable( 'policylist', $policyList );

?>
