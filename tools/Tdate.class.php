<?php

class Tdate {

	/**
	 * 获取日期时间格式
	 * @param $time 时间 整型格式
	 * @param $type 获取类型
	 * @return  $type=1获取日期
	  $type=2获取时间
	  $type=3获取日期及时间
	 */
	public static function getDate($time, $type = 3) {
		if (!$time) {
			$time = time();
		}
		switch ((int) $type) {
			case 1: $format = 'Y-m-d';
				break;
			case 2: $format = 'h:i:s';
				break;
			case 3: $format = 'Y-m-d h:i:s';
				break;
		}
		return date($format,$time);
	}

	/**
	 * 获取时间差
	 * @param $begin_time 开始时间
	 * @param $end_time 结束时间
	 * @return 数组
	 */
	public static function timediff($begin_time, $end_time) {
		if ($begin_time > $end_time) {
			return false;
		} else {
			$timediff = $end_time - $begin_time;
			$days = intval($timediff / 86400);
			$remain = $timediff % 86400;
			$hours = intval($remain / 3600);
			$remain = $remain % 3600;
			$mins = intval($remain / 60);
			$secs = $remain % 60;
			$res = array("day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs);
			return $res;
		}
	}

	/**
	 * 时间差转为X天X小时X分X秒等形式
	 * @param $int $intervalTime
	 * @param $accuracy  day精确到天 hour精确到小时 minute精确到分 second精确到秒,max精确在最大一个有数据的值
	 */
	public static function intervalTime2str($intervalTime, $accuracy = "hour") {
		$intervalTime = $intervalTime > 0 ? $intervalTime : 0;

		$day = floor($intervalTime / 86400);
		$hour = floor(($intervalTime - 86400 * $day) / 3600);
		$minute = floor((($intervalTime - 86400 * $day) - 3600 * $hour) / 60);
		$second = floor((($intervalTime - 86400 * $day) - 3600 * $hour) - 60 * $minute);
		$str = "";
		$s_day = ($day > 0) ? $day . "天" : "";
		$s_hour = ($hour > 0) ? $hour . "小时" : "";
		$s_minute = ($minute > 0) ? $minute . "分" : "";
		$s_second = ($second > 0) ? $second . "秒" : "";
		if ($accuracy == "day") {
			return $s_day;
		}
		if ($accuracy == "hour") {
			return $s_day . $s_hour;
		}
		if ($accuracy == "minute") {
			return $s_day . $s_hour . $s_minute;
		}
		if ($accuracy == "second") {
			return $s_day . $s_hour . $s_minute . $s_second;
		}
		if ($accuracy == "max") {
			if ($s_day != "")
				return $s_day;
			if ($s_hour != "")
				return $s_hour;
			if ($s_minute != "")
				return $s_minute;
			if ($s_second != "")
				return $s_second;
		}
	}

	static function microtime_float() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float) $usec + (float) $sec);
	}

}
