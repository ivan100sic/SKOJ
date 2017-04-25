<?php

	class SQL {
		
		static private function conn() {
			$conn = new mysqli('localhost', 'root', '', 'skoj');
			if ($conn->connect_error) {
				throw new Exception("SQL");
			}
			return $conn;
		}
		
		static private function do_it($query, $params, $is_select) {
			$conn = SQL::conn();
			$st = $conn->prepare($query);
			if ($st) {
				$types = '';
				for ($i = 0; $i < count($params); $i++) {
					$types .= 's';
					$params[i] = (string)$params[i];
				}
				// sve je string
				$st->bind_param($types, ...$params);
				if (!$is_select) {
					$retval = $st->execute();
					$st->close();
					$conn->close();
					return $retval;
				} else {
					$st->execute();
				}
				$result = $st->get_result();
				$st->close();
				$conn->close();
				return $result->fetch_assoc();
			}
			throw new Exception("SQL");	
		}
		
		static function get($query, $params) {
			return do_it($query, $params, true);
		}
		
		static function run($query, $params) {
			return do_it($query, $params, false);
		}
	}
	
?>