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
 * Testing how nested transactions work.
 *
 * @package Database
 * @subpackage Tests
 */
class ezcDatabaseTransactionsTest extends ezcDatabaseTestCase
{
    protected function setUp()
    {
        $this->db = parent::setUp();
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
         return new PHPUnit_Framework_TestSuite( "ezcDatabaseTransactionsTest" );
    }
}
?>
