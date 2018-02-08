<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists("format_tanggal")) {
	function format_tanggal($strdate=NULL, $format=NULL) 
	{
		$t_format = $strdate ? $strdate : "-";
		if(!empty($strdate)) {
			if(empty($format)) {
				$format = "d-m-Y";
				$t_format = date($format, strtotime($strdate));
			}
		}
		
		return $t_format;
	}
}

if(!function_exists("format_tanggal_waktu")) {
	function format_tanggal_waktu($strdatetime=NULL, $format=NULL) 
	{
		$t_format = $strdatetime ? $strdatetime : "-";
		if(!empty($strdatetime)) {
			if(empty($format)) {
				$format = "d-m-Y H:i";
				$t_format = date($format, strtotime($strdatetime));
			}
		}
		
		return $t_format;
	}
}


/* End of file shorten_number_helper.php */
/* Location: ./application/helpers/shorten_number_helper.php */
