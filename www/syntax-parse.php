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
			$rval = Rval::parse($a, $variable->end);
			if ($rval === NULL) {
				return new Lval($variable, NULL, $variable->end);
			}
			return new Lval($variable, $rval, $rval->end);
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
	}
	
	class Literal {
		public $literal;
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
			if (!is_operator($operator)) {
				return NULL;
			}
			
			$rval = Rval::parse($a, $n + 1);
			if ($rval === NULL) {
				return NULL;
			}
			
			return new UnaryExpression($operator, $rval, $rval->end);			
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
				case "greator_or_equal":
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
			if (!is_binary_operator($operator)) {
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
	}
	
?>
	