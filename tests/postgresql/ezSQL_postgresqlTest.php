<?php
require_once('ez_sql_loader.php');

require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * Test class for ezSQL_postgresql.
 * Generated by PHPUnit
 *
 * Needs database tear up to run test, that creates database and a user with
 * appropriate rights.
 * Run database tear down after tests to get rid of the database and the user.
 *
 * @author  Stefanie Janine Stoelting <mail@stefanie-stoelting.de>
 * @name    ezSQL_postgresql_tear_up
 * @uses    postgresql_test_db_tear_up.sql
 * @uses    postgresql_test_db_tear_down.sql
 * @package ezSQL
 * @subpackage Tests
 * @license FREE / Donation (LGPL - You may do what you like with ezSQL - no exceptions.)
 */
class ezSQL_postgresqlTest extends TestCase {

    /**
     * constant string user name 
     */
    const TEST_DB_USER = 'ez_test';
    
    /**
     * constant string password 
     */
    const TEST_DB_PASSWORD = 'ezTest';
    
    /**
     * constant database name 
     */
    const TEST_DB_NAME = 'ez_test';
    
    /**
     * constant database host
     */
    const TEST_DB_HOST = 'localhost';
    
    /**
     * constant database port 
     */
    const TEST_DB_PORT = '5432';
    
    /**
     * @var ezSQL_postgresql
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        if (!extension_loaded('pgsql')) {
            $this->markTestSkipped(
              'The PostgreSQL Lib is not available.'
            );
        }
        $this->object = new ezSQL_postgresql;
    } // setUp

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        $this->object = null;
    } // tearDown

    /**
     * @covers ezSQL_postgresql::quick_connect
     */
    public function testQuick_connect() {
        $this->assertTrue($this->object->quick_connect(self::TEST_DB_USER, self::TEST_DB_PASSWORD, self::TEST_DB_NAME, self::TEST_DB_HOST, self::TEST_DB_PORT));
    } // testQuick_connect

    /**
     * @covers ezSQL_postgresql::connect
     * 
     */
    public function testConnect() {
        $this->assertTrue($this->object->connect(self::TEST_DB_USER, self::TEST_DB_PASSWORD, self::TEST_DB_NAME, self::TEST_DB_HOST, self::TEST_DB_PORT));
    } // testConnect

    /**
     * @covers ezSQL_postgresql::select
     */
    public function testSelect() {
        $this->object->quick_connect(self::TEST_DB_USER, self::TEST_DB_PASSWORD, self::TEST_DB_NAME, self::TEST_DB_HOST, self::TEST_DB_PORT);
        
        $this->assertTrue($this->object->select(self::TEST_DB_NAME));
    } // testSelect

    /**
     * @covers ezSQL_postgresql::escape
     */
    public function testEscape() {
        $result = $this->object->escape("This is'nt escaped.");

        $this->assertEquals("This is''nt escaped.", $result);
    } // testEscape

    /**
     * @covers ezSQL_postgresql::sysdate
     */
    public function testSysdate() {
        $this->assertEquals('NOW()', $this->object->sysdate());
    } // testSysdate

    /**
     * @covers ezSQL_postgresql::showTables
     */
    public function testShowTables() {
        $this->assertTrue($this->object->connect(self::TEST_DB_USER, self::TEST_DB_PASSWORD, self::TEST_DB_NAME, self::TEST_DB_HOST, self::TEST_DB_PORT));
        
        $result = $this->object->showTables();
        
        $this->assertEquals('SELECT table_name FROM information_schema.tables WHERE table_schema = \'' . self::TEST_DB_NAME . '\' AND table_type=\'BASE TABLE\'', $result);
    } // testShowTables

    /**
     * @covers ezSQL_postgresql::descTable
     */
    public function testDescTable() {
        $this->assertTrue($this->object->connect(self::TEST_DB_USER, self::TEST_DB_PASSWORD, self::TEST_DB_NAME, self::TEST_DB_HOST, self::TEST_DB_PORT));
        
        $this->assertEquals(0, $this->object->query('CREATE TABLE unit_test(id integer, test_key varchar(50), PRIMARY KEY (ID))'));
        
        $this->assertEquals(
                "SELECT ordinal_position, column_name, data_type, column_default, is_nullable, character_maximum_length, numeric_precision FROM information_schema.columns WHERE table_name = 'unit_test' AND table_schema='" . self::TEST_DB_NAME . "' ORDER BY ordinal_position",
                $this->object->descTable('unit_test')
        );
        
        $this->assertEquals(0, $this->object->query('DROP TABLE unit_test'));
    } // testDescTable

    /**
     * @covers ezSQL_postgresql::showDatabases
     */
    public function testShowDatabases() {
        $this->assertTrue($this->object->connect(self::TEST_DB_USER, self::TEST_DB_PASSWORD, self::TEST_DB_NAME, self::TEST_DB_HOST, self::TEST_DB_PORT));
        
        $this->assertEquals(
                "SELECT datname FROM pg_database WHERE datname NOT IN ('template0', 'template1') ORDER BY 1",
                $this->object->showDatabases()
        );
    } // testShowDatabases

    /**
     * @covers ezSQL_postgresql::query
     */
    public function testQuery() {
        $this->assertTrue($this->object->connect(self::TEST_DB_USER, self::TEST_DB_PASSWORD, self::TEST_DB_NAME, self::TEST_DB_HOST, self::TEST_DB_PORT));
        
        $this->assertEquals(0, $this->object->query('CREATE TABLE unit_test(id integer, test_key varchar(50), PRIMARY KEY (ID))'));
        
        $this->assertEquals(0, $this->object->query('DROP TABLE unit_test'));
    } // testQuery
    
    /**
     * @covers ezSQLcore::insert
     */
    public function testInsert()
    {
        $this->object->connect(self::TEST_DB_USER, self::TEST_DB_PASSWORD, self::TEST_DB_NAME, self::TEST_DB_HOST, self::TEST_DB_PORT);        
        $this->object->query('CREATE TABLE unit_test(id serial, test_key varchar(50), test_value varchar(50), PRIMARY KEY (ID))');
        $result = $this->object->query('INSERT INTO unit_test(test_key, test_value) VALUES( \'test 1\', \'testing string 1\')');
        $this->assertEquals($result, 1);
        $this->assertNotEmpty($this->object->vardump($result));
       // $this->assertEquals($this->object->insert('unit_test', array('id'=>'1', 'test_key'=>'test 2', 'test_value'=>'testing string' )), 1);
        $this->assertEquals(0, $this->object->query('DROP TABLE unit_test'));
    }
     
    /**
     * @covers ezSQL_postgresql::disconnect
     */
    public function testDisconnect() {
        $this->object->disconnect();
        
        $this->assertFalse($this->object->isConnected());
    } // testDisconnect

    /**
     * @covers ezSQL_postgresql::getDBHost
     */
    public function testGetDBHost() {
        $this->assertEquals(self::TEST_DB_HOST, $this->object->getDBHost());
    } // testGetDBHost

    /**
     * @covers ezSQL_postgresql::getPort
     */
    public function testGetPort() {
        $this->object->connect(self::TEST_DB_USER, self::TEST_DB_PASSWORD, self::TEST_DB_NAME, self::TEST_DB_HOST, self::TEST_DB_PORT);
        
        $this->assertEquals(self::TEST_DB_PORT, $this->object->getPort());
    } // testGetPort

} // ezSQL_postgresqlTest