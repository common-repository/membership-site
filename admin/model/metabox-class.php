<?php
class membership_site_metabox{
	function __construct(){
		 
	}
 

	function ms_search_object($v, $array)
	{
		foreach ($array as $struct) {
			if ($v == $struct->wp_membership_id)
				return $struct;
		}
	}
}

?>