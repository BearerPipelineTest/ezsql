<?php
require_once('ez_sql_loader.php');

require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;

/**
 * Test class for ezSQL_pdo.
 * Generated by PHPUnit on 2012-04-02 at 00:23:22.
 */
/**
 * Test class for ezSQL_pdo.
 * Generated by PHPUnit
 *
 * Needs database tear up to run test, that creates database and a user with
 * appropriate rights.
 * Run database tear down after tests to get rid of the database and the user.
 * The PDO tests where done with a PostgreSQL database, please use the scripts
 * of PostgreSQL
 *
 * @author  Stefanie Janine Stoelting <mail@stefanie-stoelting.de>
 * @name    ezSQL_pdo_pgsqlTest
 * @package ezSQL
 * @subpackage Tests
 * @license FREE / Donation (LGPL - You may do what you like with ezSQL - no exceptions.)
 */
class ezSQL_pdo_pgsqlTest extends TestCase {

    /**
     * constant string user name
     */
    const TEST_DB_USER = 'ez_test';

    /**
     * constant string password
     */
    const TEST_DB_PASSWORD = 'ezTest';

    /**
     * constant string database name
     */
    const TEST_DB_NAME = 'ez_test';

    /**
     * constant string database host
     */
    const TEST_DB_HOST = 'localhost';

    /**
     * constant string database connection charset
     */
    const TEST_DB_CHARSET = 'utf8';

    /**
     * constant string database port
     */
    const TEST_DB_PORT = '5432';
    
    /**
     * constant string path and file name of the SQLite test database
     */
    const TEST_SQLITE_DB = 'ez_test.sqlite';

    /**
     * @var ezSQL_pdo
     */
    protected $object;
    private $errors;
 
    function errorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
        $this->errors[] = compact("errno", "errstr", "errfile",
            "errline", "errcontext");
    }

    function assertError($errstr, $errno) {
        foreach ($this->errors as $error) {
            if ($error["errstr"] === $errstr
                && $error["errno"] === $errno) {
                return;
            }
        }
        $this->fail("Error with level " . $errno .
            " and message '" . $errstr . "' not found in ", 
            var_export($this->errors, TRUE));
    }   

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        if (!extension_loaded('pdo_pgsql')) {
            $this->markTestSkipped(
              'The pdo_pgsql Lib is not available.'
            );
        }
        $this->object = new ezSQL_pdo();
		$this->object->hasprepare = false;
    } // setUp

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        $this->object = null;
    } // tearDown
    
    /**
     * @covers ezSQL_pdo::connect
     */
    public function testPosgreSQLConnect() {
        $this->assertTrue($this->object->connect('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));
    } // testPosgreSQLConnect

    /**
     * @covers ezSQL_pdo::quick_connect
     */
    public function testPosgreSQLQuick_connect() {
        $this->assertTrue($this->object->quick_connect('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));
    } // testPosgreSQLQuick_connect

    /**
     * @covers ezSQL_pdo::select
     */
    public function testPosgreSQLSelect() {
        $this->assertTrue($this->object->select('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));
    } // testPosgreSQLSelect

    /**
     * @covers ezSQL_pdo::escape
     */
    public function testPosgreSQLEscape() {
        $this->assertTrue($this->object->connect('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));

        $result = $this->object->escape("This is'nt escaped.");

        $this->assertEquals("This is''nt escaped.", $result);
    } // testPosgreSQLEscape

    /**
     * @covers ezSQL_pdo::sysdate
     */
    public function testPosgreSQLSysdate() {
        $this->assertEquals("datetime('now')", $this->object->sysdate());
    } // testPosgreSQLSysdate

    /**
     * @covers ezSQL_pdo::catch_error
     */
    public function testPosgreSQLCatch_error() {
        $this->assertTrue($this->object->connect('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));

        $this->object->query('DROP TABLE unit_test2');
        $this->assertTrue($this->object->catch_error());
    } // testPosgreSQLCatch_error

    /**
     * @covers ezSQL_pdo::query
     */
    public function testPosgreSQLQuery() {
        $this->assertTrue($this->object->connect('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));

        $this->assertEquals(0, $this->object->query('CREATE TABLE unit_test(id integer, test_key varchar(50), PRIMARY KEY (ID))'));

        $this->assertEquals(0, $this->object->query('DROP TABLE unit_test'));
    } // testPosgreSQLQuery
    
    /**
     * @covers ezSQLcore::insert
     */
    public function testInsert()
    {
        $this->assertTrue($this->object->connect('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));
        $this->object->query('CREATE TABLE unit_test(id serial, test_key varchar(50), PRIMARY KEY (ID))');

        $result = $this->object->insert('unit_test', array('test_key'=>'test 1' ));
        $this->assertEquals(1, $result);
        $this->assertEquals(0, $this->object->query('DROP TABLE unit_test'));
    }
       
    /**
     * @covers ezSQLcore::update
     */
    public function testUpdate()
    {
        $this->assertTrue($this->object->connect('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));
        $this->object->query('CREATE TABLE unit_test(id serial, test_key varchar(50), test_value varchar(50), PRIMARY KEY (ID))');
        $this->object->insert('unit_test', array('test_key'=>'test 1', 'test_value'=>'testing string 1' ));
        $this->object->insert('unit_test', array('test_key'=>'test 2', 'test_value'=>'testing string 2' ));
        $result = $this->object->insert('unit_test', array('test_key'=>'test 3', 'test_value'=>'testing string 3' ));
        $this->assertEquals($result, 3);
        $unit_test['test_key'] = 'the key string';
        $where="test_key  =  test 1";
        $this->assertEquals(1, $this->object->update('unit_test', $unit_test, $where));
        $this->assertEquals(1, $this->object->update('unit_test', $unit_test, eq('test_key','test 3', _AND),
                                                                            eq('test_value','testing string 3')));
        $where=eq('test_value','testing string 4');
        $this->assertEquals(0, $this->object->update('unit_test', $unit_test, $where));
        $this->assertEquals(1, $this->object->update('unit_test', $unit_test, "test_key  =  test 2"));
        $this->assertEquals(0, $this->object->query('DROP TABLE unit_test'));
    }
    
    /**
     * @covers ezSQLcore::delete
     */
    public function testDelete()
    {
        $this->assertTrue($this->object->connect('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));
        $this->object->query('CREATE TABLE unit_test(id serial, test_key varchar(50), test_value varchar(50), PRIMARY KEY (ID))');
        $this->object->insert('unit_test', array('test_key'=>'test 1', 'test_value'=>'testing string 1' ));
        $this->object->insert('unit_test', array('test_key'=>'test 2', 'test_value'=>'testing string 2' ));
        $this->object->insert('unit_test', array('test_key'=>'test 3', 'test_value'=>'testing string 3' ));   

        $where=array('test_key','=','test 1');
        $this->assertEquals($this->object->delete('unit_test', $where), 1);
        
        $this->assertEquals($this->object->delete('unit_test', 
            array('test_key','=','test 3'),
            array('test_value','=','testing string 3')), 1);
        $where=array('test_value','=','testing 2');
        $this->assertEquals(0, $this->object->delete('unit_test', $where));
        $where="test_key  =  test 2";
        $this->assertEquals(1, $this->object->delete('unit_test', $where));
        $this->assertEquals(0, $this->object->query('DROP TABLE unit_test'));
    }  

    /**
     * @covers ezSQLcore::selecting
     */
    public function testSelecting()
    {
        $this->assertTrue($this->object->connect('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));
        $this->object->query('CREATE TABLE unit_test(id serial, test_key varchar(50), test_value varchar(50), PRIMARY KEY (ID))');
        $this->object->insert('unit_test', array('test_key'=>'test 1', 'test_value'=>'testing string 1' ));
        $this->object->insert('unit_test', array('test_key'=>'test 2', 'test_value'=>'testing string 2' ));
        $this->object->insert('unit_test', array('test_key'=>'test 3', 'test_value'=>'testing string 3' ));   
        
        $result = $this->object->selecting('unit_test');        
        $i = 1;
        foreach ($result as $row) {
            $this->assertEquals($i, $row->id);
            $this->assertEquals('testing string ' . $i, $row->test_value);
            $this->assertEquals('test ' . $i, $row->test_key);
            ++$i;
        }
        
        $where = eq('id','2');
        $result = $this->object->selecting('unit_test', 'id', $this->object->where($where));
        foreach ($result as $row) {
            $this->assertEquals(2, $row->id);
        }
        
        $where = [eq('test_value','testing string 3', _AND), eq('id','3')];
        $result = $this->object->selecting('unit_test', 'test_key', $this->object->where($where));
        foreach ($result as $row) {
            $this->assertEquals('test 3', $row->test_key);
        }      
        
        $result = $this->object->selecting('unit_test', 'test_value', $this->object->where(eq( 'test_key','test 1' )));
        foreach ($result as $row) {
            $this->assertEquals('testing string 1', $row->test_value);
        }
    } 
    
    /**
     * @covers ezSQL_pdo::disconnect
     */
    public function testPosgreSQLDisconnect() {
        $this->assertTrue($this->object->connect('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));

        $this->object->disconnect();

        $this->assertFalse($this->object->isConnected());
    } // testPosgreSQLDisconnect

    /**
     * @covers ezSQLcore::get_set
     */
    public function testGet_set() {
        $expected = "test_var1 = '1', test_var2 = 'ezSQL test', test_var3 = 'This is''nt escaped.'";
        
        $params = array(
            'test_var1' => 1,
            'test_var2' => 'ezSQL test',
            'test_var3' => "This is'nt escaped."
        );
        
        $this->assertTrue($this->object->connect('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));

        $this->assertequals($expected, $this->object->get_set($params)); 
        $this->assertContains('NOW()',$this->object->get_set(array('test_var1' => 1,'test_var2'=>'NOW()')));
        $this->assertContains("test_var2 = 0", $this->object->get_set(array('test_var2'=>'false')));
        $this->assertContains("test_var2 = '1'", $this->object->get_set(array('test_var2'=>'true')));
    } // testSQLiteGet_set
    
    /**
     * @covers ezSQL_pdo::__construct
     */
    public function test__Construct() {         
        $this->errors = array();
        set_error_handler(array($this, 'errorHandler'));    
        
        $pdo = $this->getMockBuilder(ezSQL_pdo::class)
        ->setMethods(null)
        ->disableOriginalConstructor()
        ->getMock();
        
        $this->expectOutputRegex('/[constructor:]/');
        $this->assertNull($pdo->__construct('pgsql:host=' . self::TEST_DB_HOST . ';dbname=' . self::TEST_DB_NAME . ';port=' . self::TEST_DB_PORT, self::TEST_DB_USER, self::TEST_DB_PASSWORD));  
    } 
     
} // ezSQL_pdoTest