<?php

function priprava_breadcrambs() { ?>
	<div class="breadcrumbs"><div class="wrapper-padding">
	<?php
	$delimiter = ' / '; // разделить между ссылками
	$home = 'Главная'; // текст ссылка "Главная"

	$before = '<span typeof="v:Breadcrumb" property="v:title"  rel="v:url" class="current">';
	$after = '</span>';
	$showCurrent = 1; // 1 - показывать название текущей статьи/страницы, 0 - не показывать
	$nofollow = 1;

	$sRel = '';

	if ( $nofollow === 1 ) {
		$sRel = ' rel="nofollow" ';
	} else {
		$sRel = ' rel="v:url" ';
	}

  	if ( !is_home() && !is_front_page() || is_paged() ) {

    	echo '<div id="crumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';

    	global $post;
    	$homeLink = get_bloginfo('url');
    	echo "<span typeof='v:Breadcrumb'> <a property='v:title' {$sRel} href='{$homeLink}'>{$home}</a></span>{$delimiter}";

	    if ( is_category() ) {
		    global $wp_query;
		    $cat_obj = $wp_query->get_queried_object();
		    $thisCat = $cat_obj->term_id;
		    $thisCat = get_category( $thisCat );
		    $parentCat = get_category( $thisCat->parent );

		    if ( $thisCat->parent != 0 ) {
				$sParents = get_category_parents( $parentCat, TRUE, " {$delimiter} " );

				$sParents = str_replace( '<a', "<span typeof='v:Breadcrumb'> <a property='v:title' {$sRel}", $sParents );
				$sParents = str_replace( '</a>', '</a> </span> ', $sParents );

				echo( $sParents );
			}

		 	$sParents  = single_cat_title('', false);
			$sParents = str_replace('<a', '<a property="v:title" rel="v:url"', $sParents);

		    echo "{$before}{$sParents}{$after}";
	    }

		if ( is_tax() ) {
		    global $wp_query;

		    $cat_obj = $wp_query->get_queried_object();
		    $thisCat = $cat_obj->taxonomy;
			$thisCat_name = $cat_obj->name;

		    $thisCat = get_taxonomy( $thisCat );

		    //$parentCat = get_taxonomy($thisCat->parent);

		    if ( isset( $thisCat->parent ) && $thisCat->parent != 0) {
				$sParents = get_category_parents( $parentCat, TRUE, " {$delimiter} " );
				$sParents = str_replace( '<a', "<span typeof='v:Breadcrumb'> <a property='v:title' {$sRel}", $sParents );
				$sParents = str_replace( '</a>', '</a> </span> ', $sParents );
				echo( $sParents );

			} else {
				$sParents  = single_cat_title( $thisCat->label.'' , false);
			}

			$sParents = str_replace( '<a', '<a property="v:title" rel="v:url"', $sParents );

			echo $before . '' . $sParents . '' . $after;

		} elseif ( is_day() ) {
	      echo '<a property="v:title" ' . $sRel . ' href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
	      echo '<a property="v:title" ' . $sRel . ' href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
	      echo $before . get_the_time('d') . $after;

	    } elseif ( is_month() ) {
	      echo '<a property="v:title" ' . $sRel . ' href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
	      echo $before . get_the_time('F') . $after;

	    } elseif ( is_year() ) {
	      echo $before . get_the_time('Y') . $after;

	    } elseif ( is_single() && ! is_attachment() ) {
	    	if ( get_post_type() != 'post' ) {
		        $post_type = get_post_type_object( get_post_type() );
		        $slug = $post_type->rewrite;
		        printf($link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
		        if ($showCurrent == 0) echo $delimiter . $before . get_the_title() . $after;
		      } else {
		        $cat = get_the_category(); $cat = $cat[0];
		        $cats = get_category_parents( $cat, TRUE, $delimiter );
		        if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
		        // $cats = str_replace('<a', $linkBefore . '<span typeof="v:Breadcrumb"><a property="v:title" '  . $sRel . $linkAttr, $cats);
		        // $cats = str_replace('</a>', '</a></span>' . $linkAfter, $cats);
		        echo $cats;
		        echo get_the_title() . $after;
		        // if ($showCurrent == 0) echo $delimiter . get_the_title() . $after;
		      }

	    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() && ! is_search() ) {
	      $post_type = get_post_type_object(get_post_type());
	      echo $before . $post_type->labels->name . $after;

	    } elseif ( is_attachment() ) {
	      $parent = get_post($post->post_parent);
	      $cat = get_the_category($parent->ID); $cat = $cat[0];
	      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
	      echo '<a property="v:title" ' . $sRel . ' href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
	      echo $before . get_the_title() . $after;

	    } elseif ( is_page() && !$post->post_parent ) {
	      echo $before . get_the_title() . $after;

	    } elseif ( is_page() && $post->post_parent ) {

	      $parent_id  = $post->post_parent;
	      $breadcrumbs = array();
	      while ($parent_id) {
	        $page = get_page($parent_id);
	        $breadcrumbs[] = '<span typeof="v:Breadcrumb"><a property="v:title" ' . $sRel . ' href="' . get_permalink($page->ID) . '">' .''. get_the_title($page->ID) .''. '</a></span>';
	        $parent_id  = $page->post_parent;
	      }
      	$breadcrumbs = array_reverse($breadcrumbs);
      	foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
     	echo $before . '<span class="current">'.get_the_title() .'</span>'. $after;

	    } elseif ( is_search() ) {
	      echo $before . 'Результаты поиска по запросу "' . get_search_query() . '"' . $after;

	    } elseif ( is_tag() ) {
	      echo $before . 'Записи с тегом "' . single_tag_title('', false) . '"' . $after;

	    } elseif ( is_author() ) {
	    	global $author;
	    	$userdata = get_userdata($author);

	    	$author  = ('<span class="current">'.$userdata->display_name.'</span>');
	    	$author  = str_replace('<a', '<span typeof="v:Breadcrumb"> <a property="v:title" '. $sRel . '', $author);
	    	$author = str_replace('</a>', '</a> </span> ', $author);

	    	echo $before . $author . $after;

	    } elseif ( is_404() ) {
	      echo $before . 'Error 404' . $after;
	    }

	    if ( get_query_var('paged') ) {
	      	if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
	      	echo __('Страница') . ' ' . get_query_var('paged');
	      	if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
	    }

    	echo '</div>';

  }
  ?></div></div><?php
} // end dimox_breadcrumbs()
/*=====  End of общие функции  ======*/