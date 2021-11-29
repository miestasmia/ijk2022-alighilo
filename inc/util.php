<?php
	function coal (...$arr) {
		foreach ($arr as $x) {
			if ($x) {
				return $x;
			}
		}
		return null;
	};
