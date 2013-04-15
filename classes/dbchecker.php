<?php
/**
 * @copyright (C) eZ Systems AS 2013
 * @license Licensed under GNU General Public License v2.0. See file license.txt
 */

class dbChecker
{

    /**
    * Executes checks for db configuration errors that are not exposed by the
    * default db upgrade check test
    */
    static function checkDatabase()
    {
        $db = eZDB::instance();
        $type = $db->databaseName();
        switch( $type )
        {
            case 'mysql':
                $ini = eZINI::instance();
                $dbname = $ini->variable( 'DatabaseSettings', 'Database' );
                $warnings = array();
                foreach( $db->arrayquery( "SELECT table_name, table_collation, engine FROM information_schema.tables WHERE table_schema = '" . $db->escapeString( $dbname ) . "'" ) as $row )
                {
                    if ( $row['engine'] != 'InnoDB' )
                    {
                        $warnings[] = "Table " .  $row['table_name'] . " does not use the InnoDB storage engine: " . $row['engine'];
                    }
                    if ( substr( $row['table_collation'], 0, 5 ) != 'utf8_' )
                    {
                        $warnings[] = "Table " .  $row['table_name'] . " does not use an utf8 character set: ". $row['table_collation'];
                    }
                }
                return $warnings;

            //case 'oracle':
                /// @todo check for stored procs which are not compiled; tables with max id bigger than their associtaed sequence; double triggers on tables

            default:
                return array( 'Database type ' . $db->databaseName() . ' can currently not be checked for problems' );
        }


    }


}
?>