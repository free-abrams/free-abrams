<?php

namespace FreeAbrams\Functions\Services;

/**
 * 
 */
class SearchService
{
	public function search($array = [], $value)
	{
		$this->BinarySearch($array, 0, count($array) - 1, $value);
	}

	private function BinarySearch($array, $low, $high, $value)
	{
		$mid = intval(($low + $high)/2);

		switch ($value) {
			case $value == $array[$mid]:
				return $mid;
				break;
			case $value > $array[$mid]:
				 return $this->BinarySearch($array, $mid + 1, $high, $value);
				break;
			case $value < $array[$mid]:
				 return $this->BinarySearch($array, $low, $mid - 1, $value);
				break;
			default:
				 return false;
				break;
		}
	}
}