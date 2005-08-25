<?php
/*
Plugin Name: Just One Category
Version: 0.3
Plugin URI: http://www.blogwaffe.com:8000/2005/05/31/294/
Description: On a category's archive page do not show posts from that category's children categories unless those posts are also a member of the original category in question.  Based on Front Page Categories by Ryan Boren
Author: Michael D. Adams
Author URI: http://www.blogwaffe.com:8000/
*/ 

/*
Released under the GPL license
http://www.gnu.org/licenses/gpl.txt
*/

function joc_where($where) {
	if ( !is_category() )
		return $where;

	if ( $cat_array = rtrim(get_query_var('category_name'), ' /') ) : // trim a trailing slash if necessary.
		$cat_array = explode('/', $cat_array);
		$cat = array_pop($cat_array);
		$where .= " AND category_nicename = '$cat'";
	elseif ( $cat = rtrim(get_query_var('cat'), ' /') ) :
		$where .= " AND category_id = $cat";
	endif;
	return $where;
}

add_filter('posts_where', 'joc_where');

?>
