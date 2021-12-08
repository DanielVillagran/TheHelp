<?php
function menu_item($label, $url, $active_item)
{
	$active = ($label == $active_item) ? " class='active' " : "";
	return "<li {$active}><a href='{$url}' style=\"color:#000;\">{$label}</a></li>";
}
function left_nav($str)
{
	$leftnav = "";
	$items = explode ( "||", $str );
	for($i = 0; $i < count ( $items ); $i ++)
	{
		$item = $items [$i];
		$subitems = explode ( "|", $item );
		$parent_item = explode ( ",", $subitems [0] );
		$leftnav .= "<li><a href='#'><i class='fa {$parent_item[1]}'></i>{$parent_item[0]}</a><ul class='sub-menu'>";
		for($j = 1; $j < count ( $subitems ); $j ++)
		{
			$subitem = $subitems [$j];
			$elements = explode ( ",", $subitem );
			$leftnav .= "<li><a href='{$elements[2]}'><i class='fa {$elements[1]}'></i>{$elements[0]}</a></li>";
		}
		$leftnav .= "</ul></li>";
	}
	return $leftnav;
}
