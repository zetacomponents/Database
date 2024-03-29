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
 * A factory to create a database connections
 * using previously set parameters.
 *
 * We use MyDB::create() to create a database connection from any place
 * without passing connection parameters every time.
 * (this is not the same as singleton since the connection is not stored in
 * a static member)

 * @package Database
 * @subpackage Tests
 */
class MyDB
{
    static private $instance = null;
    static private $dbParams = null;

    static public function setParams( $dbParams )
    {
        self::$dbParams = $dbParams;
    }

    static public function create()
    {
        // create instance
        if ( self::$dbParams === null )
            throw new Exception( "Missing database " .
                                 "connection parameteters." );

        return ezcDbFactory::create( self::$dbParams );
    }
}

/**
 * Testing how nested transactions work.
 *
 * @package Database
 * @subpackage Tests
 */
class ezcDatabaseTransactionsTest extends ezcTestCase
{
    protected $db;

    protected function setUp() : void
    {
        try
        {
            $this->db = ezcDbInstance::get();
        }
        catch ( Exception $e )
        {
            $this->markTestSkipped();
        }
    }

    // normal: test nested transactions
    public function test1()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->commit();
        }
        catch ( ezcDbTransactionException $e )
        {
            $this->fail( "Exception (" . get_class( $e ) . ") caught: " . $e->getMessage() );
        }
    }

    public function test2()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->commit();
        }
        catch ( Exception $e )
        {
            $this->fail( "Should not throw exception here since the action doesn't have to be user initiated" );
        }

    }

    // error: more COMMITs than BEGINs
    public function test3()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->commit();
            $this->db->commit();
            $this->db->commit();
            $this->db->commit();
        }
        catch ( ezcDbTransactionException $e )
        {
            return;
        }

        $this->fail( "The case when there were more COMMITs than BEGINs did not fail.\n" );
    }

    // normal: BEGIN, BEGIN, COMMIT, then ROLLBACK
    public function test4()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->rollback();
        }
        catch ( ezcDbException $e )
        {
            $this->fail( "Exception (" . get_class( $e ) . ") caught: " . $e->getMessage() );
        }
    }

    // normal: BEGIN, BEGIN, ROLLBACK, then COMMIT
    public function test5()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            $this->db->rollback();
            $this->db->commit();
        }
        catch ( ezcDbException $e )
        {
            $this->fail( "Exception (" . get_class( $e ) . ") caught: " . $e->getMessage() );
        }
    }

    // error: BEGIN, ROLLBACK, COMMIT
    public function test6()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->rollback();
            $this->db->commit();
        }
        catch ( ezcDbTransactionException $e )
        {
            return;
        }

        $this->fail( "The case with consequent BEGIN, ROLLBACK, COMMIT did not fail.\n" );
    }

    // error: BEGIN, COMMIT, ROLLBACK
    public function test7()
    {
        try
        {
            $this->db->beginTransaction();
            $this->db->commit();
            $this->db->rollback();
        }
        catch ( ezcDbTransactionException $e )
        {
            return;
        }

        $this->fail( "The case with consequent BEGIN, COMMIT, ROLLBACK did not fail.\n" );
    }

    public static function suite()
    {
         return new PHPUnit\Framework\TestSuite( "ezcDatabaseTransactionsTest" );
    }
}
?>
