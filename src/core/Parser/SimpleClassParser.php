<?php 
namespace Hx\Parser;

/**
 * Read all Php classes available in targeted file
 * @author chingchetsiang
 *
 */
class SimpleClassParser implements ClassParserInterface {
	
	public function load($sourcePath)
	{
		return $this->loadString(file_get_contents($sourcePath));
	}
	
	public function loadString($content)
	{
		$tokens = token_get_all($content);
		
		$cnt = COUNT($tokens);
		
		$ns = array();
		
		$nsKey = '';
		
		for($i=0; $i < $cnt; $i++)
		{
			if(is_array($tokens[$i]) && $tokens[$i][0] == T_NAMESPACE)
			{
				$xx = '\\';
				for($j=$i+1; $j < $cnt; $j++)
				{
					if($tokens[$j] == ';' || $token[$j] == '}')
					{
						$ns[$xx] = array();
						$nsKey = $xx;
						$i = $j;
						$j = $cnt;
					}
					else
					{
						$xx .= trim($tokens[$j][1]);
					}
				}
			}
			else if (is_array($tokens[$i]) && $tokens[$i][0] == T_ABSTRACT)
			{
				for($j=$i+1; $j < $cnt; $j++)
				{
					if(is_array($tokens[$j]) && $tokens[$j][0] == T_STRING)
					{
						$ns[$nsKey][$tokens[$j][1]] = 'abstract';
						$i = $j;
						$j = $cnt;
					}
				}
			}
			else if (is_array($tokens[$i]) && $tokens[$i][0] == T_INTERFACE)
			{
				for($j=$i+1; $j < $cnt; $j++)
				{
					if(is_array($tokens[$j]) && $tokens[$j][0] == T_STRING)
					{
						$ns[$nsKey][$tokens[$j][1]] = 'interface';
						$i = $j;
						$j = $cnt;
					}
				}
			}
			else if (is_array($tokens[$i]) && $tokens[$i][0] == T_CLASS)
			{
				for($j=$i+1; $j < $cnt; $j++)
				{
					if(is_array($tokens[$j]) && $tokens[$j][0] == T_STRING)
					{
						$ns[$nsKey][$tokens[$j][1]] = 'class';
						$i = $j;
						$j = $cnt;
					}
				}
			}
		}
		
		return $ns;
	}
}
?>