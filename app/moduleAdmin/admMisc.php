<?php 
/**
 * Admin miscellaneous setting REST interface
 * @author chingchetsiang
 *
 */
class AdmMiscREST {
	
	/**
	 * Get admin micealanous setting for GET
	 */
	public static function get() {
		if(!AdmLogin::isLogin())
			\Ig\Web::sendErrorResponse(-1, "You are not authorize to view system admin setting.");
		
		try {
			return AdmMisc::get();
		} catch(Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, $ex->getMessage());
		}
	}
	
	public static function post() {
		if(!AdmLogin::isLogin())
			\Ig\Web::sendErrorResponse(-1, "You are not authorize to update system admin setting.");
		
		try {
			$data = \Ig\Web::getInputData();
			AdmMisc::set($data);
		} catch(Exception $ex) {
			\Ig\Web::sendErrorResponse(-1, $ex->getMessage());
		}
	}
}
		
/**
 * Admin miscellaneous setting
 * @author chingchetsiang
 *
 */
class AdmMisc {
	
	/**
	 * Get admin micealanous setting
	 * @param unknown $data
	 */
	public static function get() {
		$recipeService = \Starter\Recipe\StaticRecipe::getService();
		
		$recipe = $recipeService->getRecipe();
		
		$result = array();
		
		$result['serverurl'] = $recipe->getServerUrl();
		$result['maintenance'] = $recipe->getIsMaintenance();
		$result['deploy'] = false;
		$result['debugEmail'] = $recipe->getIsDebugEmail();
		$result['debugEmailAddress'] = $recipe->getDebugEmailAddress();
		
		return $result;
	}
	
	public static function set($input) {
		$recipeService = \Starter\Recipe\StaticRecipe::getService();
		
		$recipe = $recipeService->getRecipe();
		
		$recipe->setServerUrl($input['serverurl']);
		
		$recipe->setIsMaintenance($input['maintenance']);
		
		$recipe->setIsDebugEmail($input['debugEmail']);
		
		$recipe->setDebugEmailAddress($input['debugEmailAddress']);
		
		$recipeService->updateRecipe();
		
		$recipeService->loadRecipe();
	}
}
?>