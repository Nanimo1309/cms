<?php
	class Debug
	{
		public static function dir()
		{
			$a = get_included_files();
			return substr(dirname(end($a)), strlen(getcwd()) + 1);
		}
		
		public static function desc($var, $private = false, $prefix = '', $prev = [])
		{
			$arrays = function() use (&$var, $private, &$prefix, $prev)
			{
				$prefix .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|';
				
				$i = 0;
				$n = count($var) - 1;
				foreach($var as $key => $value)
				{
					echo $prefix . "_&nbsp;[$key] = ";
					
					if($i == $n)
						$prefix = substr($prefix, 0, -1) . '&nbsp;';
					
					if(!in_array($var, $prev, true))
					{
						$prev[] = $var;
						self::desc($value, $private, $prefix, $prev);
						array_pop($prev);
					}
					else
					{
						switch(gettype($value))
						{
						case 'array':
							echo 'Loop(Array(' . count($value) . '))<br />';
							break;
						case 'object':
							echo 'Loop(' . get_class($value) . '(' . count($private ? (array)$value : get_object_vars($value)) . '))<br />';
							break;
						default:
							self::dump($value, $private, $prefix, $prev);
							break;
						}
					}
					
					++$i;
				}
			};
			
			switch(gettype($var))
			{
			case 'array':
				echo 'Array(' . count($var) . ')<br>';
				$arrays();
				break;
			case 'integer':
				echo "Int($var)<br />";
				break;
			case 'double':
				echo "Double($var)<br />";
				break;
			case 'string':
				echo "String($var)<br />";
				break;
			case 'boolean':
				echo 'Boolean(' . ($var ? 'true' : 'false') . ')<br />';
				break;
			case 'null':
				echo 'NULL<br />';
				break;
			case 'object':
				if(!$prev && $var instanceof Throwable)
				{
					self::exceptToHTML($var);
					break;
				}
				
				echo get_class($var) . '(' . count($var = $private ? (array)$var : get_object_vars($var)) . ')<br />';
				$arrays();
				break;
			default:
				echo gettype($var) . '<br />';
				break;
			}
		}
		
		private static function exceptToHTML($e)
		{
			echo '<table border="solid" style="width:100%;box-sizing:border-box;border-spacing:0;"><tr><th colspan="3">' . get_class($e) . ' - ' . $e->getMessage() . '&emsp;Code: ' . $e->getCode() . '</th></tr>';
			
			if(count($trace = $e->getTrace()))
			{
				echo '<tr><th>File</th><th>Function</th><th>Line</th></tr>';
				
				foreach(array_reverse($trace) as &$t)
				{
					echo '<tr><td>' . $t['file'] . '</td><td>' . (isset($t['class']) ? $t['class'] . $t['type'] : '') . $t['function'] . '(';
					
					if(!empty($t['args']))
					{
						$args = '';
						
						foreach($t['args'] as $a)
							$args .= self::exceptDesc($a) . ', ';
						
						echo substr($args, 0, -2);
					}
					
					echo ')</td><td>' . $t['line'] . '</td></tr>';
				}
			}
			else
				echo '<tr><th colspan="2">File</th><th>Line</th></tr>';
			
			echo "<tr><td colspan='2'>{$e->getFile()}</td><td>{$e->getLine()}</td></tr></table>";
		}
		
		private static function exceptDesc($arg, $prev = [])
		{
			switch(gettype($arg))
			{
			case 'array':
				$array = '[';
				
				foreach($arg as $a)
				{
					if(!in_array($a, $prev, true))
					{
						$prev[] = $a;
						$array .= self::exceptDesc($a) . ', ';
						array_pop($prev);
					}
					else
						return 'Array(Loop)';
				}
				
				$array = substr($array, 0, -2);
				return $array . ']';
				break;
			case 'object':
				$object = get_class($arg) . '(';
				
				foreach((array)$arg as $a)
				{
					if(!in_array($var, $prev, true))
					{
						$prev[] = $a;
						$object .= self::exceptDesc($a) . ', ';
						array_pop($prev);
					}
					else
						return get_class($arg) . '(Loop)';
				}
				
				$object = substr($object, 0, -2);
				return $object . ')';
				break;
			default:
				return $arg;
				break;
			}
		}
	}
?>