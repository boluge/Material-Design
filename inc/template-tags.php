<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Material Design
 */

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation posts-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'materialdesign' ); ?></h2>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( esc_html__( 'Older posts', 'materialdesign' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( esc_html__( 'Newer posts', 'materialdesign' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'materialdesign_posts_pagination' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function materialdesign_posts_pagination() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	global $wp_query;
	$big = 999999999; // need an unlikely integer
	$pages = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages,
		'prev_next' => false,
		'type'  => 'array',
		'prev_next'   => TRUE,
		'prev_text'    => '<i class="mdi-navigation-chevron-left"></i>',
		'next_text'    => '<i class="mdi-navigation-chevron-right"></i>',
	) );
	if( is_array( $pages ) ) {
		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
		echo '<div class="pagination-content">';
		echo '<ul class="pagination secondary-pagination">';
		foreach ( $pages as $page ) {
			$page = (!strpos($page, 'current')) ? "<li class='waves-effect'>$page</li>" : "<li class='active'>$page</li>" ;
			echo $page;
		}
		echo '</ul>';
		echo '</div>';
	}
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'materialdesign' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'materialdesign_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function materialdesign_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'materialdesign' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'materialdesign' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'materialdesign_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function materialdesign_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'materialdesign' ) );
		if ( $categories_list && materialdesign_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'materialdesign' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'materialdesign' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'materialdesign' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'materialdesign' ), esc_html__( '1 Comment', 'materialdesign' ), esc_html__( '% Comments', 'materialdesign' ) );
		echo '</span>';
	}

	edit_post_link( esc_html__( 'Edit', 'materialdesign' ), '<span class="edit-link secondary-color">', '</span>' );
}
endif;

if ( ! function_exists( 'materialdesign_card_date' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function materialdesign_card_date() {
	$time_string = '<span class="card-date-day published updated" datetime="%1$s">%2$s</span><span class="card-date-month">%3$s</span>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<span class="card-date-day published" datetime="%1$s">%2$s</span><span class="card-date-month">%3$s</span>';
	}

	$date = get_the_date( 'U' );

	$monthtranslation = array( __('Jan','materialdesign'), __('Feb','materialdesign'), __('Mar','materialdesign'), __('Apr','materialdesign'), __('May','materialdesign'), __('June','materialdesign'), __('July','materialdesign'), __('Aug','materialdesign'), __('Sept','materialdesign'), __('Oct','materialdesign'), __('Nov','materialdesign'), __('Dec','materialdesign') );

	$day = date("d",$date);

	$month = date("n",$date);
	$month = $monthtranslation[$month-1];

	$time_string = sprintf( $time_string, 
		get_the_date( 'c' ),
		$day,
		$month
	);

	$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'materialdesign' ),
		$time_string
	);

	echo $posted_on; // WPCS: XSS OK.

}
endif;


if ( ! function_exists( 'materialdesign_card_footer' ) ) :
function reading_time($content){
	$wpm = get_option('materialdesign_reading_word_per_min','200');
	$format = get_option('materialdesign_reading_format','min');

	$nb_words = str_word_count($content);

	$minutes = floor( $nb_words / $wpm );
	$seconds = floor( $nb_words % $wpm / ($wpm / 60) );

	if($format == 'min'){
		$minutes = ($seconds > 30) ? ++$minutes: $minutes;
		if($minutes < 1) {
			$time = __('Less than a minute', 'materialdesign' );
		} else {
			$time = $minutes.__(' min', 'materialdesign' );
		}
	} else {
		$seconds = ($seconds < 10) ? '0'.$seconds: $seconds;
		$time = $minutes.__(':', 'materialdesign' ).$seconds.__(' sec', 'materialdesign' );
	}
	echo $time;
}
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function materialdesign_card_footer() {
	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="readingtime icon icon-time">';
		reading_time(get_the_content());
		echo '</span>';
		echo '<span class="comments-link icon icon-comment">';
		comments_popup_link( esc_html__( 'Leave a comment', 'materialdesign' ), esc_html__( '1 Comment', 'materialdesign' ), esc_html__( '% Comments', 'materialdesign' ) );
		echo '</span>';
	}

	edit_post_link( esc_html__( 'Edit', 'materialdesign' ), '<span class="edit-link icon icon-edit secondary-color">', '</span>' );
}
endif;

if ( ! function_exists( 'materialdesign_card_categories' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function materialdesign_card_categories() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'materialdesign' ) );
		if ( $categories_list && materialdesign_categorized_blog() ) {
			printf( '<span class="cat-links secondary-color">' . esc_html__( '%1$s', 'materialdesign' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}
	}
}
endif;

if ( ! function_exists( 'materialdesign_card_type' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function materialdesign_card_type() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$format = get_post_format();
		if ( $format ) {
			echo '<div class="card-category secondary">' . $format . '</div>'; // WPCS: XSS OK.
		}
	}
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( esc_html__( 'Category: %s', 'materialdesign' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( esc_html__( 'Tag: %s', 'materialdesign' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( esc_html__( 'Author: %s', 'materialdesign' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( esc_html__( 'Year: %s', 'materialdesign' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'materialdesign' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( esc_html__( 'Month: %s', 'materialdesign' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'materialdesign' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( esc_html__( 'Day: %s', 'materialdesign' ), get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'materialdesign' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = esc_html_x( 'Asides', 'post format archive title', 'materialdesign' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = esc_html_x( 'Galleries', 'post format archive title', 'materialdesign' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = esc_html_x( 'Images', 'post format archive title', 'materialdesign' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = esc_html_x( 'Videos', 'post format archive title', 'materialdesign' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = esc_html_x( 'Quotes', 'post format archive title', 'materialdesign' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = esc_html_x( 'Links', 'post format archive title', 'materialdesign' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = esc_html_x( 'Statuses', 'post format archive title', 'materialdesign' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = esc_html_x( 'Audio', 'post format archive title', 'materialdesign' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = esc_html_x( 'Chats', 'post format archive title', 'materialdesign' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( esc_html__( 'Archives: %s', 'materialdesign' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( esc_html__( '%1$s: %2$s', 'materialdesign' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'materialdesign' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;  // WPCS: XSS OK.
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;  // WPCS: XSS OK.
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function materialdesign_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'materialdesign_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'materialdesign_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so materialdesign_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so materialdesign_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in materialdesign_categorized_blog.
 */
function materialdesign_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'materialdesign_categories' );
}
add_action( 'edit_category', 'materialdesign_category_transient_flusher' );
add_action( 'save_post',     'materialdesign_category_transient_flusher' );
