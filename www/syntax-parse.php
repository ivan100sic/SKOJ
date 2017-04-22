<?php
	/*
		parsira dati string - izvorni kod, formira sintaksno
		drvo programa i vraca ga
	*/	
	
	class Program {
		public $statement_list;
		public $end;
		
		function __construct($statement_list, $end) {
			$this->statement_list = $statement_list;
			$this->end = $end;
		}
		
		static function parse($a, $n) {
			$statement_list = StatementList::parse($a, $n);
			if ($statement_list === NULL) {
				return NULL;
			}
			return new Program($statement_list, $statement_list->end);
		}
		
		function run($env) {
			try {
				// Sigurno nije null
				$this->statement_list->run($env);
			} catch (Exception $e) {
				if ($e->getMessage() == "TLE") {
					// TLE hendler
				}
				// ostali hendleri
			}
		}
	}
	
	class StatementList {
		public $statement;
		public $statement_list;
		public $end;
		
		function __construct($statement, $statement_list, $end) {
			$this->statement = $statement;
			$this->statement_list = $statement_list;
			$this->end = $end;
		}

		static function parse($a, $n) {
			$statement = Statement::parse($a, $n);
			if ($statement === NULL) {
				return new StatementList(NULL, NULL, $n);
			} else {
				$statement_list = StatementList::parse($a, $statement->end);
				return new StatementList($statement, $statement_list, $statement_list->end);
			}
		}
		
		function run($env) {
			if ($this->statement !== NULL) {
				$this->statement->run($env);
			}
			if ($this->statement_list !== NULL) {
				$this->statement_list->run($env);
			}
		}
	}
	
	class Statement {
		public $if_statement;
		public $while_statement;
		public $assignment_statement;
		public $cookie_statement;
		public $end;
		
		function __construct($if_statement, $while_statement,
				$assignment_statement, $cookie_statement, $end)
		{
			$this->if_statement = $if_statement;
			$this->while_statement = $while_statement;
			$this->assignment_statement = $assignment_statement;
			$this->cookie_statement = $cookie_statement;
			$this->end = $end;
		}
		
		static function parse($a, $n) {
			
			$statement = CookieStatement::parse($a, $n);
			if ($statement !== NULL) {
				return new Statement(NULL, NULL, NULL, $statement, $statement->end);
			}
			
			$statement = IfStatement::parse($a, $n);
			if ($statement !== NULL) {
				return new Statement($statement, NULL, NULL, NULL, $statement->end);
			}
			
			$statement = WhileStatement::parse($a, $n);
			if ($statement !== NULL) {
				return new Statement(NULL, $statement, NULL, NULL, $statement->end);
			}
			
			$statement = AssignmentStatement::parse($a, $n);
			if ($statement !== NULL) {
				return new Statement(NULL, NULL, $statement, NULL, $statement->end);
			}

			return NULL;
		}
		
		function run($env) {
			if ($this->if_statement !== NULL) {
				$this->if_statement->run($env);
			}
			if ($this->while_statement !== NULL) {
				$this->while_statement->run($env);
			}
			if ($this->assignment_statement !== NULL) {
				$this->assignment_statement->run($env);
			}
			if ($this->cookie_statement !== NULL) {
				$this->cookie_statement->run($env);
			}
		}
	}
	
	class IfStatement {
		public $rval;
		public $statement_list;
		public $end;
		
		function __construct($rval, $statement_list, $end) {
			$this->rval = $rval;
			$this->statement_list = $statement_list;
			$this->end = $end;
		}
		
		static function parse($a, $n) {
			$rval = Rval::parse($a, $n);
			if ($rval === NULL) {
				return NULL;
			}
			if ($a->get($rval->end) !== "left_brace") {
				return NULL;
			}
			$statement_list = StatementList::parse($a, $rval->end + 1);
			if ($statement_list === NULL) {
				return NULL;
			}
			if ($a->get($statement_list->end) !== "right_brace") {
				return NULL;
			}
			return new IfStatement($rval, $statement_list, $statement_list->end + 1);
		}
		
		function run($env) {
			$condition = $this->rval->run($env);
			$env->dink(); // odnosi se na branch instrukciju
			if ($condition !== 0) {
				$this->statement_list->run($env);
			}
		}
	}
	
	class WhileStatement {
		public $rval;
		public $statement_list;
		public $end;
		
		function __construct($rval, $statement_list, $end) {
			$this->rval = $rval;
			$this->statement_list = $statement_list;
			$this->end = $end;
		}
		
		static function parse($a, $n) {
			$rval = Rval::parse($a, $n);
			if ($rval === NULL) {
				return NULL;
			}
			if ($a->get($rval->end) !== "left_bracket") {
				return NULL;
			}
			$statement_list = StatementList::parse($a, $rval->end + 1);
			if ($statement_list === NULL) {
				return NULL;
			}
			if ($a->get($statement_list->end) !== "right_bracket") {
				return NULL;
			}
			return new WhileStatement($rval, $statement_list, $statement_list->end + 1);
		}
		
		function run($env) {
			while (true) {
				$condition = $this->rval->run($env);
				$env->dink(); // odnosi se na branch instrkciju
				if ($condition !== 0) {
					$this->statement_list->run($env);
					// bezuslovni skok, ne racuna se kao instrukcija
				} else {
					break;
				}
			}
		}
	}
	
	class AssignmentStatement {
		public $lval;
		public $rval;
		public $end;
		
		function __construct($lval, $rval, $end) {
			$this->lval = $lval;
			$this->rval = $rval;
			$this->end = $end;
		}
		
		static function parse($a, $n) {
			$lval = Lval::parse($a, $n);
			if ($lval === NULL) {
				return NULL;
			}
			if ($a->get($lval->end) !== "assignment") {
				return NULL;
			}
			$rval = Rval::parse($a, $lval->end + 1);
			if ($rval === NULL) {
				return NULL;
			}
			if ($a->get($rval->end) !== "semicolon") {
				return NULL;
			}
			return new AssignmentStatement($lval, $rval, $rval->end + 1);
		}
		
		function run($env) {
			// evaluiramo desnu stranu
			$result = $this->rval->run($env);
			$env->dink(); // instrukcija kopije vrednosti
			
			if ($this->lval->rval === NULL) {
				$env->set_var_value_root($this->lval->variable->run($env), $result);
			} else {
				$index = $this->lval->rval->run($env); // evaluiramo indeks
				$env->dink(); // jos jedna instrukcija za indeksirani pristup
				$ppp = $this->lval->variable->run($env);
				$env->set_var_value($this->lval->variable->run($env), $index, $result);
			}
		}
	}
	
	class CookieStatement {
		public $end;
		
		function __construct($end) {
			$this->end = $end;
		}
		
		static function parse($a, $n) {
			$cookie = $a->get($n);
			if ($cookie !== "cookie") {
				return NULL;
			}
			return new CookieStatement($n + 1);
		}
		
		function run($env) {
			$env->dink(); // specijalna instrukcija
			$env->success();
		}
	}
	
	class Lval {
		public $variable;
		public $rval;
		public $end;
		
		function __construct($variable, $rval, $end) {
			$this->variable = $variable;
			$this->rval = $rval;
			$this->end = $end;
		}
		
		static function parse($a, $n) {
			$variable = Variable::parse($a, $n);
			if ($variable === NULL) {
				return NULL;
			}
			if ($a->get($variable->end) === "dot") {
				$rval = Rval::parse($a, $variable->end + 1);
				if ($rval === NULL) {
					return NULL;
				}
				return new Lval($variable, $rval, $rval->end);
			} else {
				return new Lval($variable, NULL, $variable->end);
			}
		}
		
		function run($env) {
			if ($this->rval === NULL) {
				return $env->get_var_value_root($this->variable->run($env));
			}
			$index = $this->rval->run($env);
			$env->dink(); // citanje indeksirane vrednosti iz memorije
			return $env->get_var_value($this->variable->run($env), $index);
		}
	}
	
	class Variable {
		public $variable;
		public $end;
		
		function __construct($variable, $end) {
			$this->variable = $variable;
			$this->end = $end;
		}
		
		static function parse($a, $n) {
			$variable = $a->get($n);
			if (preg_match('/^[a-z]$/', $variable) === 1) {
				return new Variable($variable, $n + 1);
			}
			return NULL;
		}
		
		function run($env) {
			$env->dink();
			return $this->variable;
		}
	}
	
	class Rval {
		public $lval;
		public $literal;
		public $unary_expression;
		public $binary_expression;
		public $end;
		
		function __construct($lval, $literal, $unary_expression,
				$binary_expression, $end)
		{
			$this->lval = $lval;
			$this->literal = $literal;
			$this->unary_expression = $unary_expression;
			$this->binary_expression = $binary_expression;
			$this->end = $end;
		}
		
		static function parse($a, $n) {
			$lval = Lval::parse($a, $n);
			if ($lval !== NULL) {
				return new Rval($lval, NULL, NULL, NULL, $lval->end);
			}
			
			$literal = Literal::parse($a, $n);
			if ($literal !== NULL) {
				return new Rval(NULL, $literal, NULL, NULL, $literal->end);
			}
			
			$unary_expression = UnaryExpression::parse($a, $n);
			if ($unary_expression !== NULL) {
				return new Rval(NULL, NULL, $unary_expression,
						NULL, $unary_expression->end);
			}
			
			$binary_expression = BinaryExpression::parse($a, $n);
			if ($binary_expression !== NULL) {
				return new Rval(NULL, NULL, NULL,
						$binary_expression, $binary_expression->end);
			}
			
			return NULL;
		}
		
		function run($env) {
			if ($this->lval !== NULL) {
				return $this->lval->run($env);
			}
			if ($this->literal !== NULL) {
				return $this->literal->run($env);
			}
			if ($this->unary_expression !== NULL) {
				return $this->unary_expression->run($env);
			}
			if ($this->binary_expression !== NULL) {
				return $this->binary_expression->run($env);
			}
		}
	}
	
	class Literal {
		public $literal; // integer
		public $end;
		
		function __construct($literal, $end) {
			$this->literal = $literal;
			$this->end = $end;
		}
		
		static function parse($a, $n) {
			$literal = $a->get($n);
			if (preg_match("/^[0-9]+$/", $literal) === 1) {
				return new Literal((int)$literal, $n + 1);
			}
			return NULL;			
		}
		
		function run($env) {
			$env->dink();
			return $this->literal;
		}
	}
	
	class UnaryExpression {
		public $operator;
		public $rval;
		public $end;
		
		function __construct($operator, $rval, $end) {
			$this->operator = $operator;
			$this->rval = $rval;
			$this->end = $end;
		}
		
		static function is_unary_operator($operator) {
			switch($operator) {
				case "unary_minus":
				case "not":
				case "complement":
					return true;
			}
			return false;
		}
		
		static function parse($a, $n) {
			$operator = $a->get($n);
			if (!UnaryExpression::is_unary_operator($operator)) {
				return NULL;
			}
			
			$rval = Rval::parse($a, $n + 1);
			if ($rval === NULL) {
				return NULL;
			}
			
			return new UnaryExpression($operator, $rval, $rval->end);			
		}
		
		function run($env) {
			$result = $this->rval->run($env);
			$env->dink(); // za primenu unarne operacije
			switch ($this->operator) {
				case "unary_minus":
					return -$result;
				case "not":
					if ($result === 0) {
						return 1;
					} else {
						return 0;
					}
				case "complement":
					return ~$result;				
			}
		}
	}
	
	class BinaryExpression {
		public $operator;
		public $rval_left;
		public $rval_right;
		public $end;
		
		function __construct($operator, $rval_left, $rval_right, $end) {
			$this->operator = $operator;
			$this->rval_left = $rval_left;
			$this->rval_right = $rval_right;
			$this->end = $end;
		}
		
		static function is_binary_operator($operator) {
			switch($operator) {
				case "plus":
				case "minus":
				case "times":
				case "divide":
				case "mod":
				
				case "equal":
				case "greater":
				case "less":
				case "not_equal":
				case "greater_or_equal":
				case "less_or_equal":
				
				case "logical_and":
				case "logical_or":
				case "bitwise_and":
				case "bitwise_or":
				case "bitwise_xor":
				
				case "shift_left":
				case "shift_right":
				
					return true;
			}
			return false;
		}
		
		static function parse($a, $n) {
			$operator = $a->get($n);
			if (!BinaryExpression::is_binary_operator($operator)) {
				return NULL;
			}

			$rval_left = Rval::parse($a, $n + 1);
			if ($rval_left === NULL) {
				return NULL;
			}
			
			$rval_right = Rval::parse($a, $rval_left->end);
			if ($rval_right === NULL) {
				return NULL;
			}
			
			return new BinaryExpression($operator, $rval_left, $rval_right, $rval_right->end);
		}
		
		function run($env) {
			$a = $this->rval_left->run($env);
			$b = $this->rval_right->run($env);
			$env->dink(); // za primenu binarne operacije
			
			switch ($this->operator) {
				case "plus":
					return (int)($a + $b);
				case "minus":
					return (int)($a - $b);
				case "times":
					return (int)($a * $b);
				case "divide":
					return (int)(($a - $a % $b) / $b);
				case "mod":
					return (int)($a % $b);
					
				case "equal":
					if ($a === $b) {
						return 1;
					} else {
						return 0;
					}
				case "greater":
					if ($a > $b) {
						return 1;
					} else {
						return 0;
					}
				case "less":
					if ($a < $b) {
						return 1;
					} else {
						return 0;
					}
				case "greater_or_equal":
					if ($a >= $b) {
						return 1;
					} else {
						return 0;
					}
				case "less_or_equal":
					if ($a <= $b) {
						return 1;
					} else {
						return 0;
					}
				case "not_equal":
					if ($a !== $b) {
						return 1;
					} else {
						return 0;
					}
					
				case "logical_and":
					if ($a !== 0 && $b !== 0) {
						return 1;
					} else {
						return 0;
					}
				case "logical_or":
					if ($a !== 0 || $b !== 0) {
						return 1;
					} else {
						return 0;
					}
				case "bitwise_and":
					return (int)($a & $b);
				case "bitwise_or":
					return (int)($a | $b);
				case "bitwise_xor":
					return (int)($a ^ $b);
				
				case "shift_left":
					return (int)($a << $b);
				case "shift_right":
					return (int)($a >> $b);
			}
		}
	}
	
?>
	