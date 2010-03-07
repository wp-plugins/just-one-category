<?php

/*
Plugin Name: Just One Category
Version: 1.1
Plugin URI: http://wordpress.org/extend/plugins/just-one-category/
Description: On a category's archive page do not show posts from that category's children categories unless those posts are also a member of the original category in question.  Based on Front Page Categories by Ryan Boren.
Author: Michael D Adams
Author URI: http://www.blogwaffe.com/

Released under the GPL license
http://www.gnu.org/licenses/gpl.txt
*/

class Just_One_Category {
	var $q;

	function get_posts( &$q ) {
		$this->q =& $q;

		if ( !$q->is_category )
			return false;

		add_action( 'posts_where', array( &$this, 'where' ) );
	}

	function where( $where ) {
		global $wpdb;

		remove_action( 'posts_where', array( &$this, 'where' ) );

		$cat = rtrim( $this->q->get( 'cat' ), ' /' );

		if ( !is_numeric( $cat ) || $cat < 1 )
			return $where;

		$field = preg_quote( "$wpdb->term_taxonomy.term_id", '#' );

		$just_one = $wpdb->prepare( " AND $wpdb->term_taxonomy.term_id = %d ", $cat );
		if ( preg_match( "#AND\s+$field\s+IN\s*\(\s*(?:['\"]?\d+['\"]?\s*,\s*)*['\"]?\d+['\"]?\s*\)#", $where, $matches ) )
			$where = str_replace( $matches[0], $just_one, $where );
		else
			$where .= $just_one;

		return $where;
	}
}

function joc_init() {
	$just_one_category = new Just_One_Category;
	add_action( 'pre_get_posts', array( &$just_one_category, 'get_posts' ) );
}

add_action( 'init', 'joc_init' );
