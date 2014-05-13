<?php
/**
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @todo add more classes of content that have no stats in main admin interface
 * @todo add support for webshop, ezsurvey, ezflow, eznewsletter contents
 */

class contentStatsGatherer
{
    function gather()
    {
        $contentTypes = array(
            'Objects (including users)' => array( 'table' => 'ezcontentobject' ),
            'Users' => array( 'table' => 'ezuser' ),
            'Content Classes' => array( 'table' => 'ezcontentclass' ),
            'Information Collections' => array( 'table' => 'ezinfocollection' ),
            'Pending notification events' => array( 'table' => 'eznotificationevent', 'wherecondition' => 'status = 0' ),
            'Objects pending indexation' => array( 'table' => 'ezpending_actions', 'wherecondition' => "action = 'index_object'" ),
        );

        $db = eZDB::instance();
        $contentList = array();
        foreach( $contentTypes as $key => $desc )
        {
            $sql = 'SELECT COUNT(*) AS NUM FROM ' .  $desc['table'];
            if ( @$desc['wherecondition'] )
            {
                $sql .= ' WHERE ' . $desc['wherecondition'];
            }
            /*if ( isset($desc['groupby']) )
               {
               $sql. = '';
               }*/
            $count = $db->arrayQuery( $sql );
            $contentList[$key] = $count[0]['NUM'];
        }
        return $contentList;
    }
}

?>
