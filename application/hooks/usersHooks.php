<?php

class usersHooks {
	public function users1($data = '') {
		echo '<br/> users11 <br/>df'.$data;
	}

	public function users2($data) {
		echo '<br/> user2 <br/>d'.$data;
	}

	public function controller_top() {
		echo 'controller_top432';
	}

	public function controller_bottom() {
		echo 'controller_bottom234';
	}
}