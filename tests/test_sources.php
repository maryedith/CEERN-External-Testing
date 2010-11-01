<?php

class CEERNUnitTestingSource extends UnitTestCase {
	
  private $ceen_location = 'http://api.resourcecommons.org/services/rest';

  function __construct() {
	 $this->ceenRU = new CEERNResourceUtil();
  }

  function setUp() {
    parent::setUp();
    
    /**
     * Add setup functionality here.
     */
  }
  
  function __destruct() {
    $this->ceenRU->CEERNResourceCall('/reset.php', 'POST', NULL, TRUE);
  }

  function tearDown() {
    /**
     * Delete every resource from this server.
     */
     
    /**
     * Now, delete the users.
     */
  
    parent::tearDown();
  }
  
  function testGetSources() {  
	
    $data = $this->ceenRU->CEERNResourceCall('/source.php', 'GET', NULL, FALSE, 'source_resource.index');
    $this->assertTrue(isset($data['stats']));
  }

  function testGetSource( $uuid='97f26cde-a6fc-11df-8932-4040e8acc39d') {  
	
    $data = $this->ceenRU->CEERNResourceCall('/source'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'source_resource.retrieve');
    $this->assertTrue(isset($data['name']));
  }

} // end class

?>
