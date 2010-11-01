<?php

class CEERNUnitTestingEducationStandards extends UnitTestCase {
	
  private $ceenRU;

  function __construct() {
	 $this->ceenRU = new CEERNResourceUtil();
  }

  function setUp() {
    parent::setUp();
    
    /**
     * Add setup functionality here.
     */
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
  
  function testGetEducationStandards() {  
	
    $data = $this->ceenRU->CEERNResourceCall('/education_standards', 'GET', NULL, FALSE, 'educations_standards_resource.index');
    $this->assertTrue(isset($data['stats']));
  }

  function testGetEducationStandard( $uuid='K_MD_1') {  
	
    $data = $this->ceenRU->CEERNResourceCall('/education_standards'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'education_standards_resource.retrieve');
    $this->assertTrue(isset($data['document']));
  }

} // end class

?>
