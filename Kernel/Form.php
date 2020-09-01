<?php
	class Form
	{
		private static $request;
		
		private $actionn;
		private $method;
		private $name;
		private $attr;
		
		private $part;
		
		public static function init()
		{
			self::$request = $_POST;
		}
		
		public static function getRequest()
		{
			return self::$request;
		}
		
		public function __construct($action, $name, $method = 'post', $attr = [])
		{
			$this->actionn = $action;
			$this->name = $name;
			$this->method = $method;
			$this->attr = $attr;
			
			$this->part = [];
			$this->add($name, 'hidden', null, [], ['readonly', 'hidden']);
		}
		
		final public function add($name, $type, $label = null, $attrib = [])
		{
			$labelText = null;
			$labelAttrib = [];
			
			if($label)
			{
				if(is_array($label))
				{
					if(isset($label['text']))
					{
						$labelText = $label['text'];
						
						unset($label['text']);
						$labelAttrib = $label;
					}
				}
				else
					$labelText = $label;
			}
			
			$this->part[$name] = ['type' => $type, 'labelText' => $labelText, 'labelAttrib' => $labelAttrib, 'attrib' => $attrib];
			return $this;
		}
		
		final public function change($name, $attrib, $value)
		{
			$this->part[$name][$attrib] = $value;
			return $this;
		}
		
		final public function delete($name)
		{
			unset($this->part[$name]);
			return $this;
		}
		
		private static function writeAttrib(&$attrib)
		{
			$ret = '';
			
			foreach($attrib as $attr => $val)
			{
				if(is_int($attr))
					$ret .= " $val";
				else
					$ret .= "$attr='$val'";
			}
			
			return $ret;
		}
		
		final public function start()
		{
			echo "<form id='$this->name' method='$this->method'" . self::writeAttrib($this->attr) . '>';
			
			$this->write($this->name);
			
			return $this;
		}
		
		final public function end()
		{
			echo '</form>';
			
			return $this;
		}
		
		final public function write($name)
		{
			if(!isset($this->part[$name]))
				throw new Exception('No input named: ' . $name);
			
			$in = $this->part[$name];
			$id = $this->name . '-' . $name;
			
			switch($in['type'])
			{
			case 'submit':
				echo "<button id='$id' name='$name' type='submit'" . self::writeAttrib($in['attrib']) . ">{$in['labelText']}</button>";
				break;
			case 'reset':
				echo "<button id='$id' type='reset'" . self::writeAttrib($in['attrib']) . ">{$in['labelText']}</button>";
				break;
			case 'select':
				$options = $in['attrib']['options'];
				unset($in['attrib']['options']);
				
				echo "<select id='$id' name='$name'" . self::writeAttrib($in['attrib']) . '>';
				
				$selected = null;
				
				if($in['labelText'])
				{
					if(array_key_exists($in['labelText'], $options))
						$selected = $in['labelText'];
					else
						echo "<option selected disabled hidden>{$in['labelText']}</option>";
				}
				
				foreach($options as $val => $o)
				{
					echo '<option';
					
					if(!is_int($val))
						echo " value='$val'";
					
					if($val == $selected)
						echo ' selected';
					
					echo ">$o</option>";
				}
				
				echo '</select>';
				break;
			default:
				if($in['labelText'])
					echo "<label id='$id-label' for='$id'" . self::writeAttrib($in['labelAttrib']) . ">{$in['labelText']}</label>";
				
				echo "<input id='$id' name='$name' type='{$in['type']}'" . self::writeAttrib($in['attrib']) . ' />';
				
				break;
			}
			
			return $this;
		}
		
		public function writeForm()
		{
			$this->start();
			
			foreach($this->names() as $name)
				$this->write($name);
			
			$this->end();
		}
		
		final public function html($html)
		{
			echo $html;
			return $this;
		}
		
		final public function action()
		{
			if(array_diff_key($this->part, self::$request))
				return false;
			
			($this->actionn)(self::$request);
			return true;
		}
		
		final public function get($name)
		{
			return $this->part[$name];
		}
		
		final public function name()
		{
			return $this->name;
		}
		
		final public function names()
		{
			return array_keys(array_diff_key($this->part, [$this->name => '']));
		}
		
		final public function method()
		{
			return $this->method;
		}
	}
?>