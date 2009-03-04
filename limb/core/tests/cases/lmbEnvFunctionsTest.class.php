<?php

class lmbEnvFunctionsTest extends UnitTestCase
{
  private $_prev_env = array();
  private $_keys = array();

  function setUp()
  {
    $this->_prev_env = $_ENV;
    $_ENV = array();
    $this->_keys = array();
  }

  function tearDown()
  {
    $_ENV = $this->_prev_env;
  }

  function testGetNullByDefault()
  {
    $this->assertNull(lmb_env_get($this->_('foo')));
  }

  function testGetDefault()
  {
    $this->assertEqual(lmb_env_get($this->_('foo'), 1), 1);
  }

  function testSet()
  {
    lmb_env_set($this->_('foo'), 'bar');
    $this->assertEqual(lmb_env_get($this->_('foo')), 'bar');
  }

  function testSetOr()
  {
    lmb_env_setor($this->_('foo'), 'bar');
    $this->assertEqual(lmb_env_get($this->_('foo')), 'bar');
    
    lmb_env_setor($this->_('foo'), 'baz');
    $this->assertEqual(lmb_env_get($this->_('foo')), 'bar');
  }

  function testHas()
  {
    $this->assertFalse(lmb_env_has($this->_('foo')));
    lmb_env_set($this->_('foo'), 'bar');
    $this->assertTrue(lmb_env_has($this->_('foo')));    
  }

  function testHasWorksForNulls()
  {
    $this->assertFalse(lmb_env_has($this->_('foo')));
    lmb_env_set($this->_('foo'), null);
    $this->assertTrue(lmb_env_has($this->_('foo')));    
  }

  function testSetDefinesConstant()
  {
    $this->assertFalse(defined($this->_('foo')));
    lmb_env_set($this->_('foo'), 'bar');
    $this->assertEqual(constant($this->_('foo')), 'bar');
  }

  function testHasAndGetFallbackToConstant()
  {
    $name = $this->_('LIMB_TEST_FOO');
    
    $this->assertFalse(lmb_env_has($name));
    $this->assertNull(lmb_env_get($name, null));
    
    define($name, 'bar');
    $this->assertTrue(lmb_env_has($name));
    $this->assertEqual(lmb_env_get($name), 'bar');        
  }

  function testTrace()
  { 
    lmb_env_trace($this->_('foo'));
    
    ob_start();
    lmb_env_setor($key = $this->_('foo'), $value = 'bar');
    $call_line = strval(__LINE__ - 1);    
    $trace_info = ob_get_clean();
    
    $this->assertTrue(strstr($trace_info, __FILE__));
    $this->assertTrue(strstr($trace_info, $call_line)); 
    $this->assertTrue(strstr($trace_info, $method_name = 'setor')); 
    $this->assertTrue(strstr($trace_info, $key));
    $this->assertTrue(strstr($trace_info, $value));     
        
    ob_start();
    lmb_env_set($key, $value = 'baz');
    $call_line = strval(__LINE__ - 1);
    $trace_info = ob_get_clean();
    
    $this->assertTrue(strstr($trace_info, __FILE__));
    $this->assertTrue(strstr($trace_info, $call_line));
    $this->assertTrue(strstr($trace_info, $method_name = 'set')); 
    $this->assertTrue(strstr($trace_info, $key));
    $this->assertTrue(strstr($trace_info, $value));
  }
  
  //used for convenient tracking of the random keys
  private function _($name)
  {
    if(!isset($this->_keys[$name]))
      $this->_keys[$name] = $name . mt_rand() . time();
    return $this->_keys[$name];
  }
}