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

/**
 * Testing the SQL expression abstraction layer for INSERT queries.
 *
 * @package Database
 * @subpackage Tests
 */
class ezcQueryDeleteTest extends ezcTestCase
{
    private $q;

    protected function setUp() : void
    {
        try
        {
            $db = ezcDbInstance::get();
        }
        catch ( Exception $e )
        {
            $this->markTestSkipped();
        }

        $this->q = new ezcQueryDelete( $db );
        try
        {
            $db->exec( 'DROP TABLE query_test' );
        }
        catch ( Exception $e ) {} // eat

        // insert some data
        $db->exec( 'CREATE TABLE query_test ( id int, company VARCHAR(255), section VARCHAR(255), employees int )' );

    }

    protected function tearDown() : void
    {
        $db = ezcDbInstance::get();
        $db->exec( 'DROP TABLE query_test' );
    }

    public function testNoWhere()
    {
        $reference = "DELETE FROM legends";
        $this->q->deleteFrom( 'legends' );
        $this->assertEquals( $reference, $this->q->getQuery() );
    }

    public function testWithWhere()
    {
        $reference = "DELETE FROM legends WHERE id = 1";
        $this->q->deleteFrom( 'legends' )
            ->where( $this->q->expr->eq( 'id', 1 ) );
        $this->assertEquals( $reference, $this->q->getQuery() );
    }

    public function testWithSeveralWhere()
    {
        $reference = "DELETE FROM legends WHERE Gretzky = Lindros AND 1 = 1";

        $this->q->deleteFrom( 'legends' )
            ->where( $this->q->expr->eq( 'Gretzky', 'Lindros' ) )
            ->where( $this->q->expr->eq( 1, 1 ) );
        $this->assertEquals( $reference, $this->q->getQuery() );
    }

    public function testInvalidWhereCall()
    {
        try
        {
            $this->q->deleteFrom( 'legends' )
                ->where();
        }
        catch ( ezcQueryException $e )
        {
            return;
        }
        $this->fail( "Got no exception when an exception was expected" );
    }


    public function testNoDeleteFrom()
    {
        try
        {
        $this->q->where( $this->q->expr->eq( 'id', 1 ) );
        $this->q->getQuery();
        }
        catch ( Exception $e )
        {
            return;
        }
        $this->fail( "Delete query with no table did not fail!" );
    }

    // test on a real database.
    public function testOnDatabaseWithoutWhere()
    {
        // fill database with some dummy data
        $q = new ezcQueryInsert( ezcDbInstance::get() );
        // insert some data we can update
        $company = 'eZ systems';
        $section = 'Norway';
        $q->insertInto( 'query_test' )
            ->set( 'id', 1 )
            ->set( 'company', $q->bindParam( $company ) )
            ->set( 'section', $q->bindParam( $section ) )
            ->set( 'employees', 20 );
        $stmt = $q->prepare();
        $stmt->execute();

        $q->insertInto( 'query_test' );
        $q->set( 'id', 2 );
        $q->set( 'employees', 70 );
        $company = 'trolltech';
        $section = 'Norway';
        $stmt = $q->prepare();
        $stmt->execute();

        // delete all
        $this->q->deleteFrom( 'query_test' );
        $stmt = $this->q->prepare();
        $stmt->execute();

        // test that table is empty
        $db = ezcDbInstance::get();
        $q = $db->createSelectQuery(); // get select query
        $q->select( '*' )->from( 'query_test' );
        $stmt = $q->prepare();
        $stmt->execute();
        $result = $stmt->fetchAll();
        $this->assertEquals( 0, count( $result ) );
    }

    public function testOnDatabaseWithWhere()
    {
        // fill database with some dummy data
        $q = new ezcQueryInsert( ezcDbInstance::get() );

        // insert some data we can update
        $company = 'eZ systems';
        $section = 'Norway';
        $q->insertInto( 'query_test' )
            ->set( 'id', 1 )
            ->set( 'company', $q->bindParam( $company ) )
            ->set( 'section', $q->bindParam( $section ) )
            ->set( 'employees', 20 );
        $stmt = $q->prepare();
        $stmt->execute();

        $q->insertInto( 'query_test' );
        $q->set( 'id', 2 );
        $q->set( 'employees', 70 );
        $company = 'trolltech';
        $section = 'Norway';
        $stmt = $q->prepare();
        $stmt->execute();

        // delete one
        $this->q->deleteFrom( 'query_test' )
            ->where( $this->q->expr->eq( 'id', 1 ) );
        $stmt = $this->q->prepare();
        $stmt->execute();

        // test that table has one row
        $db = ezcDbInstance::get();
        $q = $db->createSelectQuery(); // get select query
        $q->select( '*' )->from( 'query_test' );
        $stmt = $q->prepare();
        $stmt->execute();
        $result = $stmt->fetchAll();
        $this->assertEquals( 1, count( $result ) );
    }

    public static function suite()
    {
        return new PHPUnit\Framework\TestSuite( 'ezcQueryDeleteTest' );
    }
}
?>
