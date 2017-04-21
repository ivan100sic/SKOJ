<?php
	/*
		parsira dati string - izvorni kod, formira sintaksno\
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
		public $end;
		
		function __construct($if_statement, $while_statement, $assignment_statement, $end) {
			$this->if_statement = $if_statement;
			$this->while_statement = $while_statement;
			$this->assignment_statement = $assignment_statement;
			$this->end = $end;
		}
		
		static function parse($a, $n) {
			$statement = IfStatement::parse($a, $n);
			if ($statement !== NULL) {
				return new Statement($statement, NULL, NULL, $statement->end);
			}
			
			$statement = WhileStatement::parse($a, $n);
			if ($statement !== NULL) {
				return new Statement(NULL, $statement, NULL, $statement->end);
			}
			
			$statement = AssignmentStatement::parse($a, $n);
			if ($statement !== NULL) {
				return new Statement(NULL, NULL, $statement, $statement->end);
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
			if (preg_match('^[a-z]$', $variable)) {
				return new Variable($variable, $n + 1);
			}
			return NULL;
		}
	}
	
	class Rval {
		
		
	}
	
	
	
?>
	