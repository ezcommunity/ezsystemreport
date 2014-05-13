<?php
/**
 * A script that gathers the common parts of all views of the sysinfo module
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @todo use a 3-level path, with the name of the group as 2nd element ?
 */

$module = $Params['Module'];
$view = $module->currentView();

// rely on system policy instead of creating our own, but allow also PolicyOmitList
$ini = eZINI::instance();
if ( !in_array( "sysinfo/$view", $ini->variable( 'RoleSettings', 'PolicyOmitList' ) ) )
{
    $user = eZUser::currentUser();
    $access = $user->hasAccessTo( 'setup', 'system_info' );
    if ( $access['accessWord'] != 'yes' )
    {
        return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
    }
}

$tpl = sysInfoTools::eZTemplateFactory();
$tpl->setVariable( 'title', sysinfoModule::viewTitle( $view ) );

$extra_path = '';

include( "extension/ggsysinfo/modules/sysinfo/$view.php" );

$Result = array();
$Result['content'] = $tpl->fetch( "design:sysinfo/$view.tpl" );

$Result['left_menu'] = 'design:parts/sysinfo/menu.tpl';
$url1stlevel = array( array( 'url' => 'sysinfo/index',
                             'text' => sysInfoTools::ezpI18ntr( 'SysInfo', 'System information' ) ) );
if ( $view == 'index' )
{
    $url1stlevel[0]['url'] = false;
    $url2ndlevel = array();
}
else
{
    $url2ndlevel = array( array( 'url' => false,
                                 'text' => sysInfoTools::ezpI18ntr( 'SysInfo', sysinfoModule::viewName( $view ) ) ) );
}
if ( $extra_path != '' )
{
    if ( sysinfoModule::viewActive( $view )  )
    {
        $url2ndlevel[0]['url'] = "sysinfo/$view";
    }

    $url3rdlevel = array( array( 'url' => false,
                                 'text' => $extra_path ) );
}
else
{
    $url3rdlevel = array();
}
$Result['path'] = array_merge( $url1stlevel, $url2ndlevel, $url3rdlevel );

?>