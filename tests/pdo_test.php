<?php
/**
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version //autogentag//
 * @filesource
 * @package Database
 * @subpackage Tests
 */

require_once __DIR__ . '/test_case.php';

/**
 * Test the PDO system.
 *
 * @package Database
 * @subpackage Tests
 */
class ezcPdoTest extends ezcDatabaseTestCase
{
    protected function setUp()
    {
        $db = parent::setUp();

        $this->q = new ezcQueryInsert( $db );
        try
        {
            $db->exec( 'DROP TABLE query_test' );
        }
        catch ( Exception $e ) {} // eat

        // insert some data
        $db->exec( 'CREATE TABLE query_test ( id int, company VARCHAR(255), section VARCHAR(255), employees int )' );

    }

    protected function tearDown()
    {
        $db = ezcDbInstance::get();
        $db->exec( 'DROP TABLE query_test' );
    }


    // This query probably fails when the PDO is linked to the wrong libmysql client.
    // E.g. it must be linked against libmysqlclient12 and not libmysqlclient14 
    // nor libmysqlclient15. 
    public function testIdNotFound()
    {
        $db = ezcDbInstance::get();
        if ( $db->getName() != 'mysql' )
        {
            return;  // no need to test it in RDBMS other than MySQL
        }
        
        $q = $db->prepare("INSERT INTO query_test VALUES( 1, 'name', 'section', 22)" );
        $q->execute();

        $stmt = $db->prepare('select * from `query_test` where `id`=:id');
        $stmt->bindValue(':id', 1);
        $stmt->execute();
        $row = $stmt->fetchAll( PDO::FETCH_ASSOC );

        $this->assertEquals( "1", $row[0]["id"] ); 
        $this->assertEquals( "name", $row[0]["company"] ); 
        $this->assertEquals( "section", $row[0]["section"] ); 
        $stmt->closeCursor();
    }


    // Works in PHP 5.2.1RC2, segfaults in PHP 5.1.4
/*
    public function testSegfaultWrongFunctionCall()
    {
        $db = ezcDbInstance::get();

        $q = $db->prepare("INSERT INTO query_test VALUES( '', 'name', 'section', 22)" ); 
        $q->execute();

        $q->oasdfa(); // Wrong method call.
    }
*/
    // Works in PHP 5.1.4, Fails (hangs) in PHP 5.2.1RC2-dev.
    public function testInsertWithWrongColon()
    {
        $db = ezcDbInstance::get();
        if ( $db->getName() != 'mysql' )
        {
            return;  // no need to test it in RDBMS other than MySQL.
        }

        $q = $db->prepare("INSERT INTO query_test VALUES( ':id', 'name', 'section', 22)" ); // <-- ':id' should be :id (or a string without ":")
        $q->execute();
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcPdoTest" );
    }
}

?>
