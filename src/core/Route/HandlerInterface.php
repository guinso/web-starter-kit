<?php 
namespace Hx\Route;

interface HandlerInterface {
	
	/**
	 * Execute content based on \Hx\Route\Match instance
	 * @param MatchInterface $match
	 */
	public function handle(MatchInterface $match, Array $Data);
}
?>