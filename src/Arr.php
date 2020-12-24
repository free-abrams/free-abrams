<?php 
namespace FreeAbrams\Functions;

/**
 * 数组操作帮助函数
 */
class Arr
{
	// 数组头添加键值
	public static function AddHeader($arr = [], $key, $value)
	{
		$temp[$key] = $value;

		$arr = array_push($temp, $arr);

		return $arr;
	}

	public static function Add($arr = [], $key, $value)
	{
		$arr = array_push($arr, [$key => $value]);

		return $arr;
	}

	public static function QSort()
	{
		$arr = [1, 3, 5, 6, 4];

		$mid = int(count($arr)/2);
	}

}