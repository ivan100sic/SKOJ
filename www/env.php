<?php

	class Env {
		
		private $session;
		private $get;
		private $post;
		private $files;
		
		function __construct() {
			$this->session = NULL;
			$this->get = NULL;
			$this->post = NULL;
			$this->files = NULL;
		}
		
		function set_session($session) {
			$this->session = $session;
			return $this;
		}
		
		function set_get($get) {
			$this->get = $get;
			return $this;
		}
		
		function set_post($post) {
			$this->post = $post;
			return $this;
		}
		
		function set_files($files) {
			$this->files = $files;
			return $this;
		}
		
		function get_session_user_id() {
			// session promenljiva je trusted, nema potrebe za proverom
			if ($session) {
				if (isset($session['user_id'])) {
					return $session['user_id'];
				}
			}
			return -1;
		}		
		
	}