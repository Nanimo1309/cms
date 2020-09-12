<?php
	class Stmt
	{
		private $stmt;

		public function __construct($stmt)
		{
			if(!$stmt instanceof mysqli_stmt)
				throw new SQLException('Invalid initializer type');

			$this->stmt = $stmt;
		}

		public function execute(...$attr)
		{
			$types = '';
			foreach($attr as $a)
			{
				switch(gettype($a))
				{
				case 'string':
				case 'NULL':
					$types .= 's';
					break;
				case 'integer':
					$types .= 'i';
					break;
				case 'double':
					$types .= 'd';
					break;
				default:
					throw new SQLException('Illegal data type: ' . gettype($a));
					break;
				}
			}

			if(!empty($types))
				$this->stmt->bind_param($types, ...$attr);

			$this->stmt->execute();

			$res = $this->stmt->get_result();

			if($res)
			{
				$ret = $res->fetch_all(MYSQLI_ASSOC);

				$res->free();

				if(empty($ret))
					return false;

				return $ret;
			}
			else
			{
				switch($this->stmt->errno)
				{
				case 0: // No error
					return $this->stmt->affected_rows;
				case 1062: // Duplicate insert
					return false;
				default:
					throw new SQLException($this->stmt->error, $this->stmt->errno);
				}
			}
		}
	}
?>