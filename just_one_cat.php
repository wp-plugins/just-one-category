<?php
/*
Plugin Name: Just One Category
Version: 1.0
Plugin URI: http://www.blogwaffe.com:8000/2005/05/31/294/
Description: On a category's archive page do not show posts from that category's children categories unless those posts are also a member of the original category in question.  Based on Front Page Categories by Ryan Boren.
Author: Michael D Adams
Author URI: http://www.blogwaffe.com:8000/

Released under the GPL license
http://www.gnu.org/licenses/gpl.txt
*/

function joc_where($where) {
	global $wpdb;
	if ( !is_category() )
		return $where;

	$cat = rtrim(get_query_var('cat'), ' /');

	if ( !is_numeric($cat) || $cat < 1 )
		return $where;

	$field = preg_quote( "$wpdb->term_taxonomy.term_id", '#' );

	$just_one .= $wpdb->prepare( " AND $wpdb->term_taxonomy.term_id = %d ", $cat );
	if ( preg_match( "#AND\s+$field\s+IN\s*\(\s*(?:['\"]?\d+['\"]?\s*,\s*)*['\"]?\d+['\"]?\s*\)#", $where, $matches ) )
		$where = str_replace( $matches[0], $just_one, $where );
	else
		$where .= $just_one;

	return $where;
}

add_filter('posts_where', 'joc_where');
