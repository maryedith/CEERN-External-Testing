<?php
/*	test_resource_index.php
	started 10/17/10 by Mary Edith Ingraham
	test SEARCH/INDEX function of api.resourcecommons.org
*/

/*  DESCRIPTION OF THE SEARCH/INDEXING FUNCTION FROM THE DOC:
* Title (title) - Name of resource.
* Language (language)- Language available for resource.
* Source (source) - UUID for source site.
* Resource Type (resource_type) - Resource Type.
	Classroom Resources, Places To Go, Online Resources
* Zip Code (zip) - Zip code search.
* State (state) - State search.
* Education Continuum (education_continuum) - Where on the education continuum does this fit?
* Education Standard (edu_standard) - A single education standard
* Update (update) - Filter out resources that were created or updated before this time. Useful to keep track of new posts.

* Count (count) - # of items to display in a single query. Defaults to 20, maxes out at 100.
* Page (page) - What page # do we want to list?
*/


class CEERNUnitTesting extends UnitTestCase {
	
  private $ceen_location = 'http://api.resourcecommons.org/services/rest';
  private $ceenRU;
  private $temp_uuid;
  private $test_data_array = array( 
				'title' => 'TodaysTitle',
				'zip' => '95000',
//				'state' => 'NV',							// state not saved 11/1
//				'resource_type' => 'Classroom Resources',	// type not saved 11/1
//				'lang' => 'de',								// search lang returns all
//				'education_continuum' => 'K_MD_1',			// search edu_cont returns all
			);
  
  private $test_resource_array = array();
  
  function __construct() {
	 $this->ceenRU = new CEERNResourceUtil();
	
	$user = array(
      'first_name' => 'Resource'.rand(),
      'last_name' => 'Tester',
      'bio' => 'testing...testing...',
      'contact' => array(
        'mail' => 'test@test.com',
        'alternate_email' => 'example2@example.com',
        'website' => 'http://example.com',
        'street' => '444 Fourth St',
        'alternate' => 'Apartment 4',
        'city' => 'San Francisco',
        'state' => 'CA',
        'zip' => '93939',
        'county' => 'United States',
      ),
    );
    
    $test_user = $this->ceenRU->CEERNResourceCall('/user.php', 'POST', $user, TRUE, 'user_resource.create', FALSE);
    $this->user_uuid = $test_user->uuid;

	print'Created this test user <br>';
	krumo( $this->user_uuid );

  }

  function __destruct() {
    $this->ceenRU->CEERNResourceCall('/reset.php', 'POST', NULL, TRUE);
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

  function testCreateResources( ) {
	
	foreach( $this->test_data_array as $field=>$value ) {
		print 'Make a resource with '.$field.' = '. $value.'<br>';
		$r = $this->ceenMakeResource( $field, $value );
		print 'Create this resource'.'<br>';
		$data = $this->ceenCreateResource( $r );
		$this->assertTrue(isset($data->uuid));

		// $data is not a complete resource.. only a few fields.
		// so, we Get it and save it in our array
		$test_resource_array[$field] = $this->ceenGetResource($data->uuid);
		
		//update our value to test for to the randomized value
		$value = $this->ceenGetResourceFieldValue($test_resource_array[$field], $field);   

		print 'Get a resource with '.$field.' = '. $value.'<br>';
		$matches = $this->ceenGetResourceField( $field, $value );

		$this->assertTrue($matches['stats']['total']>0);
	
		$compare= $test_resource_array[$field];
		print' this is compare:::::::::::';
		krumo( $compare );
			
		print' this is get ::::::::::: ' ;
		$get = $this->ceenGetResource( $matches['resources'][0]['uuid'] );
		print ' IDENTICAL says:';
		$this->assertIdentical( $compare, $get );
		print ' CLONE says: ';
		$this->assertClone( $compare, $get);
		
  }
}

  function ceenMakeResource( $fname, $fvalue ) {
	$resource = array(
	  'title' => 'NewResource',
	  'description' => 'resource description.',
	  'type' => array(
		'Classroom Resources',
      ),
	  'time' => array(
	    'start' => '09/08/10 - 12:00 pm',
		'end' => '12/31/10 - 12 pm'
	  ),
	  'prerequisites' => 'My prerequisites',
	  'location' => array(
		'name' => 'location name',
		'street' => 'location street',
		'additional' => 'location street2',
		'city' => 'location town',
		'state' => 'CA',
		'zip' => '55555',
		'country' => 'USA',
	  ),
	  'language' => array( 'en' ),
	  'contact' => array( 
		'name' => 'contact name',
		'email' => 'contact@email.com',
		'url' => 'http://contacturl.com',
		'phone' => '800-777-2222',
	  ),
	  'grade_levels' => array(),
	  'education_continuum' => array(),
//	  'education_standards' => array(),  // including this caused failure 11/1
	  'participant_type' => array(),
	  'links' => array(),
	  'photos' => array(),
	  'user' =>  $this->user_uuid,
	  'fair_usage' => TRUE,
	);

	switch( $fname ) {
		case 'title':
		case 'description':
		case 'prerequisites':	
			$resource[$fname] = $fvalue.rand();
			break;
		case 'city':
		case 'zip':
		case 'country':
			$resource['location'][$fname] = $fvalue.rand();
			break;
		case 'state':
			$resource['location'][$fname] = $fvalue; // api may reject a randomized state name?
			break;
		case 'lang':
			$resource['language'][0] = $fvalue;  
			break;
		case 'resource_type':
			$resource['type'][0] = $fvalue.rand();
			break;
		case 'education_continuum':
			$resource['education_continuum'][0] = $fvalue;
			break;
		case 'edu_standard':	
			$resource['education_standards'][0] = $fvalue;
			break;
		case 'update':
			// dates after the data, only.  format?
			break;
	}
	return ( $resource );
  }

  function ceenGetResourceFieldValue( $resource, $fname ) {
	
		switch( $fname ) {
			case 'title':
			case 'description':
			case 'prerequisites':	
				$value = $resource[$fname];
				break;
			case 'city':
			case 'zip':
			case 'state':
			case 'country':
				$value = $resource['location'][$fname];
				break;
			case 'lang':
				$value = $resource['language']; //[0]; // 10/29 not saved as an array
				break;
			case 'type':
			case 'resource_type':
				$value = $resource['type'][0];
				break;
			case 'education_continuum':
				$value = $resource['education_continuum'][0];
				break;
			case 'edu_standard':	
				$value = $resource['education_standards'][0];
				break;
			case 'update':
				// dates after the data, only.  format?
				break;
		}
		return ( $value );
  }

  function ceenCreateResource( $resource ) {
	
    $resource = (object) $resource;
    $data = $this->ceenRU->CEERNResourceCall('/resource.php', 'POST', $resource, TRUE, 'resource_resource.create');

    return( $data );
  }

  function ceenGetResourceField( $fname, $value ) {  
    
    $data = $this->ceenRU->CEERNResourceCall('/resource.php'.'?'.$fname.'='.$value, 'GET', NULL, FALSE, 'resource_resource.index');

  	return( $data );
  }

  function ceenGetResource( $uuid ) {  

    $data = $this->ceenRU->CEERNResourceCall('/resource'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'resource_resource.retrieve');

    return( $data );
  }

} // end class

?>
