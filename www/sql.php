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
					$params[$i] = (string)$params[$i];
				}
				// sve je string
				if (count($params) > 0) {
					$st->bind_param($types, ...$params);
				}
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
				$all_results = [];
				while ($row = $result->fetch_assoc()) {
					$all_results[] = $row;
				}
				return $all_results;
			}
			throw new Exception("SQL");	
		}
		
		static function get($query, $params) {
			return SQL::do_it($query, $params, true);
		}
		
		static function run($query, $params) {
			return SQL::do_it($query, $params, false);
		}
	}
	
?>