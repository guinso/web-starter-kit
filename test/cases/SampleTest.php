<?php 
class SampleTest extends PHPUnit_Framework_TestCase {
	
	public function testAsd() {
		
		$a = 3;
		
		$b = 2;
		
		$this->assertEquals($a, $b, "A not same as b!");
	}
}
?>