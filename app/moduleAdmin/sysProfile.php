<?php 
class SysProfileRest {
	public static function get() {
		if(!AdmLogin::isLogin())
			\Ig\Web::sendErrorResponse(-1, "You are not authorize to view system profile.");
		
		try {
			return SysProfile::get();
		} catch(Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function post() {
		if(!AdmLogin::isLogin())
			\Ig\Web::sendErrorResponse(-1, "You are not authorize to update system profile.");
		
		try {
			$data = \Ig\Web::getInputData();
			SysProfile::update($data);
		} catch(Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, $ex->getMessage());
		}
	}
}

class SysProfile {
	public static function get() {
		$recipeService = \Starter\Recipe\StaticRecipe::getService();
		
		$recipe = $recipeService->getRecipe();
		
		$profiles = $recipe->getAllProfiles();
		
		$result = array();
		
		foreach($profiles as $key => $profile) {
			$result[$key] = $profile->getAllValues();
		}
		
		return array(
			'items' => $result,
			'defaultKey' => $recipe->get('defaultProfile')
		);
	}
	
	public static function update($input) {
		$recipeService = \Starter\Recipe\StaticRecipe::getService();
		
		$recipe = $recipeService->getRecipe();
		
		
		
		
		$profiles = $recipe->getAllProfiles();
		
		foreach($profiles as $key => $p)
			$recipe->removeProfile($key);
		
		
		
		$items = $input['items'];
		
		foreach($items as $k => $v)
		{
			$pp = new \Starter\Recipe\SimpleProfile($recipe->getRootPath());
			
			foreach($v as $kk => $vv)
				$pp->set($kk, $vv);
			
			$recipe->setProfile($k, $pp);
		}
		
		
		
		$recipe->setDefaultProfile($input['defaultKey']);
		
		$recipeService->updateRecipe();
		
		$recipeService->loadRecipe();
	}
}
?>