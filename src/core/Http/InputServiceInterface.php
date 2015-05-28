<?php 
namespace Hx\Http;

interface InputServiceInterface {
	
	public function getInput();
	
	public function registerPlugin(InputInterface $plugin);
	
	public function removePlugin($contentType);
	
	public function getPlugin($contentType);
}
?>