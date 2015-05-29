<?php 
namespace Starter\Recipe;

class SimpleXmlLoader implements \Hx\Recipe\LoaderInterface {
	
	const PROFILE_KEY = 'profiles';
	
	private $rootDir;
	
	public function __construct($rootDir)
	{
		$this->rootDir = $rootDir;		
	}
	
	public function load($sourcePath)
	{
		if (!file_exists($sourcePath))
			Throw new RecipeException("Recipe file $sourcePath not found.");
		else if (!is_readable($sourcePath))
			Throw new RecipeException("Recipe file $sourcePath cannot access.");
		else 
		{
			return $this->parseRecipe(
				simplexml_load_file($sourcePath)
			);
		}
	}
	
	private function parseRecipe(\SimpleXMLElement $xml)
	{
		$nodes = $xml->children();
		
		$recipe = new SimpleRecipe($this->rootDir);
		
		$this->addStringValue($recipe, $xml, 'guid');
		
		$this->addBoolValue($recipe, $xml, 'maintenance');
		
		$this->addBoolValue($recipe, $xml, 'debugEmail');
		
		$this->addStringValue($recipe, $xml, 'serverUrl');
		
		$this->addStringValue($recipe, $xml, 'username');
		
		$this->addStringValue($recipe, $xml, 'password');
		
		$this->addStringValue($recipe, $xml, 'debugEmailAddress');
		
		$this->addStringValue($recipe, $xml, 'defaultProfile');
		
		
		$ps = $xml->xpath('/recipe/profiles/profile');
		
		foreach ($ps as $p)
		{
			$profile = $this->parseProfile($p);
			
			$recipe->setProfile($profile->getName(), $profile);
		}
		
		//check profile key exists or not
		if (empty($recipe->getDefaultProfile()))
			Throw new RecipeException(
				"Cannot parse {$recipe->get('defaultProfile')} as " . 
				"default profile as it does not found.");
			
		return $recipe;
	}
	
	private function parseProfile(\SimpleXMLElement $xml)
	{
		$profile = new SimpleProfile($this->rootDir);
		
		$this->addProfileStringValue($profile, $xml, 'name');
		
		//pdo database
		$this->addProfileStringValue($profile, $xml, 'dbName');
		
		$this->addProfileStringValue($profile, $xml, 'dbHost');
		
		$this->addProfileStringValue($profile, $xml, 'dbUsr');
		
		$this->addProfileStringValue($profile, $xml, 'dbPwd');
		
		$this->addProfileNumericValue($profile, $xml, 'dbLen');
		
		$this->addProfileStringValue($profile, $xml, 'dbInitial');
		

		
		//misc
		$this->addProfileStringValue($profile, $xml, 'uploadPath');
		
		$this->addProfileStringValue($profile, $xml, 'templatePath');
		
		$this->addProfileStringValue($profile, $xml, 'cachePath');
		
		$this->addProfileStringValue($profile, $xml, 'timeZone');
		
		
		
		//smtp
		$this->addProfileStringValue($profile, $xml, 'smtpHost');
		
		$this->addProfileStringValue($profile, $xml, 'smtpUsr');
		
		$this->addProfileStringValue($profile, $xml, 'smtpPwd');
		
		$this->addProfileStringValue($profile, $xml, 'smtpName');
		
		$this->addProfileStringValue($profile, $xml, 'smtpEmail');
		
		$this->addProfileStringValue($profile, $xml, 'smtpSecure');
		
		$this->addProfileNumericValue($profile, $xml, 'smtpPort');
		
		return $profile;
	}
	
	////////////////////////////////// Recipe Value //////////////////////////////////////
	private function addBoolValue(SimpleRecipe $recipe, \SimpleXMLElement $xml, $key)
	{
		if ($xml->$key == null)
			Throw new RecipeException("Node $key not found in recipe file.");
		else 
			$recipe->set($key, $this->parseBool($xml->$key));
	}
	
	private function addNumericValue(SimpleRecipe $recipe, \SimpleXMLElement $xml, $key)
	{
		if ($xml->$key == null)
			Throw new RecipeException("Node $key not found in recipe file.");
		else
			$recipe->set($key, $this->parseNumeric($xml->$key));
	}
	
	private function addStringValue(SimpleRecipe &$recipe, \SimpleXMLElement $xml, $key)
	{
		if ($xml->$key === null)
			Throw new RecipeException("Node $key not found in recipe file.");
		else
			$recipe->set($key, (string) $xml->$key);
	}
	
	/////////////////////////////////// Profile Value //////////////////////////////////////
	private function addProfileBoolValue(SimpleProfile $profile, \SimpleXMLElement $xml, $key)
	{
		if ($xml->$key == null)
			Throw new RecipeException("Node profile $key not found in recipe file.");
		else
			$profile->set($key, $this->parseBool($xml->$key));
	}
	
	private function addProfileNumericValue(SimpleProfile $profile, \SimpleXMLElement $xml, $key)
	{
		if ($xml->$key == null)
			Throw new RecipeException("Node profile $key not found in recipe file.");
		else
			$profile->set($key, $this->parseNumeric($xml->$key));
	}
	
	private function addProfileStringValue(SimpleProfile $profile, \SimpleXMLElement $xml, $key)
	{
		if ($xml->$key == null)
			Throw new RecipeException("Node profile $key not found in recipe file.");
		else
			$profile->set($key, (string) $xml->$key);
	}
	
	///////////////////////////////// Parse Value ////////////////////////////////////
	private function parseNumeric($value)
	{
		if (doubleval($value) == $value)
			return doubleval($value);
		else 
			Throw new RecipeException("Cannot parse $value to numeric");
	}
	
	private function parseBool($value)
	{
		if ($value == 1)
			return true;
		else if ($value == 0)
			return false;
		else 
			Throw new RecipeException("Cannot parse $value to boolean");
	}
	
	private function setHashValue(\SimpleXMLElement $xml, $key, $defaultValue)
	{
		if ($xml->$key !== null)
			return $xml->$key;
		else 
			return $defaultValue;
	}
	
	private function setPathString($value)
	{
		$path = mb_ereg_replace(
			'@',
			$this->rootDir . DIRECTORY_SEPARATOR,
			$value);
		
		if (!file_exists($path))
			Throw new RecipeException(
				"Cannot parse $path to string path, file not exists.");
		else
			return $path;
	}
	
	
	
	/*************************** Write XML ******************************/
	public function save($sourcePath, \Hx\Recipe\RecipeInterface $recipe)
	{
		$values = $recipe->getAll();
		
		$generalBuffer = '';
		
		foreach($values as $key => $value)
			$generalBuffer .= "<$key>$value</$key>\n";
		
		
		
		$profiles = $recipe->getAllProfiles();
		
		$profileBuffer = '';
		
		foreach($profiles as $profile)
			$profileBuffer .= $this->writeProfileXml($profile->getAllValues()) . "\n";
		
		
		
		$buffer = 
			"<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n" .
			"<recipe>\n" . 
				$generalBuffer .
				"<profiles>\n" . 
					$profileBuffer .
				"</profiles>\n" .
			"</recipe>";
		
		file_put_contents($sourcePath, $buffer);
	}
	
	private function writeProfileXml(Array $profile)
	{
		$output = '';
		
		foreach($profile as $key => $value)
		{
			$output .= "<$key>$value</$key>\n";
		}
		
		return "<profile>\n" . $output . "</profile>";
	}
}
?>