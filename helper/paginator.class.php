<?php
class membership_site
{
	var $items_per_page;
	var $items_total;
	var $current_page;
	var $num_pages;
	var $mid_range;
	var $low;
	var $limit;
	var $return;
	var $default_ipp;
	var $querystring;
	var $ipp_array;
	function Paginator()
	{
		$this->current_page = 1;
		$this->mid_range = 7;
		$this->ipp_array = array(10, 25, 50, 100, 'All');
		$this->ipp = intval($_GET['ipp']);
		$this->items_per_page = ($this->ipp > 0) ? $this->ipp : 25;
	}
	function paginate()
	{
		if (!isset($this->default_ipp)) $this->default_ipp = 25;
		if ($this->ipp == 'All') {
			$this->num_pages = 1;
			//			$this->items_per_page = $this->default_ipp;
		} else {
			if (!is_numeric($this->items_per_page) or $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
			$this->num_pages = ceil($this->items_total / $this->items_per_page);
		}
		if ((isset($_GET['pagen']))) {
			$pagen = sanitize_text_field(trim($_GET['pagen']));
			if ($pagen != 'All') {
				$pagen = intval($_GET['pagen']);
				if ($pagen == 0)
					$pagen = 1;
			}
			$this->current_page = $pagen;
		} else {
			$this->current_page = 1;
		}
		$prev_page = $this->current_page - 1;
		$next_page = $this->current_page + 1;
		if ($_GET) {
			$args = explode("&", $_SERVER['QUERY_STRING']);
			foreach ($args as $arg) {
				$keyval = explode("=", $arg);
				if ($keyval[0] != "pagen" and $keyval[0] != "ipp") $this->querystring .= "&" . $arg;
			}
		}
		$post = isset($_POST) ? (array) $_POST : array();
		foreach ($post as $key => $val) {
			$val = intval($val);
			$key = sanitize_text_field($key);
			if ($key != "pagen" and $key != "ipp") $this->querystring .= "&$key=$val";
		}

		if ($this->num_pages > 10) {
			$this->return = ($this->current_page > 1 and $this->items_total >= 10) ? "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?pagen=$prev_page&ipp=$this->items_per_page$this->querystring\">&laquo; Previous</a> " : "<span class=\"inactive\" href=\"#\">&laquo; Previous</span> ";
			$this->start_range = $this->current_page - floor($this->mid_range / 2);
			$this->end_range = $this->current_page + floor($this->mid_range / 2);
			if ($this->start_range <= 0) {
				$this->end_range += abs($this->start_range) + 1;
				$this->start_range = 1;
			}
			if ($this->end_range > $this->num_pages) {
				$this->start_range -= $this->end_range - $this->num_pages;
				$this->end_range = $this->num_pages;
			}
			$this->range = range($this->start_range, $this->end_range);
			for ($i = 1; $i <= $this->num_pages; $i++) {
				if ($this->range[0] > 2 and $i == $this->range[0]) $this->return .= " ... ";
				// loop through all pages. if first, last, or in range, display
				if ($i == 1 or $i == $this->num_pages or in_array($i, $this->range)) {
					$this->return .= ($i == $this->current_page and $pagen != 'All') ? "<a title=\"Go to page $i of $this->num_pages\" class=\"current\" href=\"#\">$i</a> " : "<a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"$_SERVER[PHP_SELF]?pagen=$i&ipp=$this->items_per_page$this->querystring\">$i</a> ";
				}
				if ($this->range[$this->mid_range - 1] < $this->num_pages - 1 and $i == $this->range[$this->mid_range - 1]) $this->return .= " ... ";
			}
			$this->return .= (($this->current_page < $this->num_pages and $this->items_total >= 10) and ($pagen != 'All') and $this->current_page > 0) ? "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?pagen=$next_page&ipp=$this->items_per_page$this->querystring\">Next &raquo;</a>\n" : "<span class=\"inactive\" href=\"#\">&raquo; Next</span>\n";
			$this->return .= ($pagen == 'All') ? "<a class=\"current\" style=\"margin-left:10px\" href=\"#\">All</a> \n" : "<a class=\"paginate\" style=\"margin-left:10px\" href=\"$_SERVER[PHP_SELF]?pagen=1&ipp=All$this->querystring\">All</a> \n";
		} else {
			for ($i = 1; $i <= $this->num_pages; $i++) {
				$this->return .= ($i == $this->current_page) ? "<a class=\"current\" href=\"#\">$i</a> " : "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?pagen=$i&ipp=$this->items_per_page$this->querystring\">$i</a> ";
			}
			$this->return .= "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?pagen=1&ipp=All$this->querystring\">All</a> \n";
		}
		$this->low = ($this->current_page <= 0) ? 0 : ($this->current_page - 1) * $this->items_per_page;
		if ($this->current_page <= 0) $this->items_per_page = 0;
		$this->limit = ($this->ipp == 'All') ? "" : " LIMIT $this->low,$this->items_per_page";
	}
	function display_items_per_page()
	{
		$items = '';
		if ($this->ipp == 0) $this->items_per_page = $this->default_ipp;
		foreach ($this->ipp_array as $ipp_opt) $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n" : "<option value=\"$ipp_opt\">$ipp_opt</option>\n";
		return "<span class=\"paginate\">Items per page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?pagen=1&ipp='+this[this.selectedIndex].value+'$this->querystring';return false\">$items</select>\n";
	}
	function display_jump_menu()
	{
		$option = '';
		for ($i = 1; $i <= $this->num_pages; $i++) {
			$option .= ($i == $this->current_page) ? "<option value=\"$i\" selected>$i</option>\n" : "<option value=\"$i\">$i</option>\n";
		}
		return "<span class=\"paginate\">Page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?pagen='+this[this.selectedIndex].value+'&ipp=$this->items_per_page$this->querystring';return false\">$option</select>\n";
	}
	function display_pages()
	{
		return $this->return;
	}
}
