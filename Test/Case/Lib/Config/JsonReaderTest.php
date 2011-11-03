<?php
App::uses('JsonReader', 'JsonReader.Config');
/**
 * JsonReaderTest 
 * 
 * @author Paul Connolley {connrs} <paul.connolley@gmail.com>
 * @license WTFPL
 */
class JsonReaderTest extends CakeTestCase {
    public function setUp() {
        parent::setUp();
        $this->path = App::pluginPath('JsonReader') . 'Test' . DS . 'test_files' .DS . 'Config' . DS;
    }

    /**
     * Test that it actually returns a valid array on read
     * 
     * @access public
     * @return void
     */
    public function testValid() {
        $json = array(0,1,2,3,4);
        $reader = new JsonReader($this->path);
        $config = $reader->read('test_valid');
        $this->assertTrue(array_key_exists('assocKey', $config));
        $this->assertIdentical($config['assocKey'], $json);
    }

    /**
     * Test that an empty Json file returns an empty array
     * 
     * @access public
     * @return void
     */
    public function testEmpty() {
        $reader = new JsonReader($this->path);
        $config = $reader->read('test_empty');
        $this->assertTrue(is_array($config) && empty($config));
    }

    /**
     * Test that an invalid JSON file throws a Configure Exception
     * 
     * @access public
     * @return void
     */
    public function testInvalid() {
        $reader = new JsonReader($this->path);
        $this->expectException('ConfigureException');
        $config = $reader->read('test_invalid');
    }

    /**
     * Test that loading a valid JSON file with a .json extension returns a valid array
     * 
     * @access public
     * @return void
     */
    public function testLoadingWithExtension() {
        $reader = new JsonReader($this->path);
        $config = $reader->read('test_valid.json');
        $this->assertTrue(array_key_exists('assocKey', $config));
    }

    /**
     * Test that JSON booleans are processed as booleans when decoded from JSON
     * 
     * @access public
     * @return void
     */
    public function testReadingBooleanValues() {
        $reader = new JsonReader($this->path);
        $config = $reader->read('test_valid');
        $this->assertTrue($config['levelOne']['aTrueBool']);
        $this->assertFalse($config['levelOne']['aFalseBool']);
    }
}
