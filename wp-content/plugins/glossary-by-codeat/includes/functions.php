<?php

/**
 * Generate the list of terms
 * 
 * @param string $order
 * @param int $num
 * @param string $tax
 * @return string
 */
function get_glossary_terms_list( $order, $num, $tax = '' ) {
	if ( $order === 'asc' ) {
		$order = 'ASC';
	}

	$args = array( 'post_type' => 'glossary', 'order' => $order, 'orderby' => 'title', 'posts_per_page' => $num, 'update_post_meta_cache' => false, 'fields' => 'ids' );

	if ( !empty( $tax ) && $tax !== 'any' ) {
		$args[ 'tax_query' ] = array(
			array(
				'taxonomy' => 'glossary-cat',
				'terms' => $tax,
				'field' => 'slug',
			),
		);
	}

	$glossary = new WP_Query( $args );
	if ( $glossary->have_posts() ) {
		$out = '<dl class="glossary-terms-list">';
		while ( $glossary->have_posts() ) : $glossary->the_post();
			$out .= '<dt><a href="' . get_glossary_term_url( get_the_ID() ) . '">' . get_the_title() . '</a></dt>';
		endwhile;
		$out .= '</dl>';
		wp_reset_query();

		return $out;
	}
}

/**
 * Get the url of the term attached
 * 
 * @param int $id
 * @return string
 */
function get_glossary_term_url( $id = '' ) {
	if ( empty( $id ) ) {
		$id = get_the_ID();
	}
	$type = get_post_meta( $id, GT_SETTINGS . '_link_type', true );
	$link = get_post_meta( $id, GT_SETTINGS . '_url', true );
	$cpt = get_post_meta( $id, GT_SETTINGS . '_cpt', true );
	if ( empty( $link ) && empty( $cpt ) ) {
		return get_the_permalink( $id );
	}
	if ( $type === 'external' || empty( $type ) ) {
		return $link;
	}
	if ( $type === 'internal' ) {
		return get_the_permalink( $cpt );
	}
}

/**
 * Generate a list of category terms
 * 
 * @param string $order
 * @param int $num
 * @return string
 */
function get_glossary_cats_list( $order = 'DESC', $num = '0' ) {
	$taxs = get_terms( 'glossary-cat', array(
		'hide_empty' => false,
		'order' => $order,
		'number' => $num,
		'orderby' => 'name'
			) );

	$out = '<dl class="glossary-terms-list">';
	if ( !empty( $taxs ) && !is_wp_error( $taxs ) ) {
		foreach ( $taxs as $tax ) {
			$out .= '<dt><a href="' . esc_url( get_term_link( $tax ) ) . '">' . $tax->name . '</a></dt>';
		}
		$out .= '</dl>';
		return $out;
	}
}

/**
 * Check if text is RTL
 * 
 * @param string $string
 * 
 * @return bool
 */
function gl_text_is_rtl( $string ) {
	$rtl_chars_pattern = '/[\x{0590}-\x{05ff}\x{0600}-\x{06ff}]/u';
	return preg_match( $rtl_chars_pattern, $string );
}

/**
 * Return the cached value of terms count
 * 
 * @return number
 */
function gl_get_terms_count() {
	return get_option( GT_SETTINGS . 'count_terms', true );
}

/**
 * Return the cached value of related terms count
 * 
 * @return number
 */
function gl_get_related_terms_count() {
	return get_option( GT_SETTINGS . 'count_related_terms', true );
}

/**
 * Update the database with cached value for count of terms and related terms 
 * 
 * @return void
 */
function gl_update_counter() {
	$args = array(
		'post_type' => 'glossary',
		'posts_per_page' => -1,
		'order' => 'asc',
		'post_status' => 'publish'
	);
	$query = new WP_Query( $args );

	$count = 0;
	$count_related = 0;
	$gl_search_engine = Glossary_Search_Engine::get_instance();

	foreach ( $query->posts as $post ) {
		$count++;
		$related = $gl_search_engine->related_post_meta( get_post_meta( $post->ID, GT_SETTINGS . '_tag', true ) );
		if ( is_array( $related ) ) {
			$count_related += count( $related );
		}
	}

	update_option( GT_SETTINGS . 'count_terms', $count );
	update_option( GT_SETTINGS . 'count_related_terms', $count_related );
}
