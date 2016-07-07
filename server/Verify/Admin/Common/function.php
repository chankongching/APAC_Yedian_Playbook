<?php
function getxiangshu($count = '') {
	if ($count > 0) {
		return floor($count / 24);
	}
}