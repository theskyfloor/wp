<?php
namespace ElementorExtras;

use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Utils {

	/**
	 * Fetches available post types
	 *
	 * @since 2.0.0
	 */
	public static function get_public_post_types_options( $singular = false, $args = [] ) {
		$post_type_args = [
			'show_in_nav_menus' => true,
		];

		$post_types = [];

		if ( ! function_exists( 'get_post_types' ) )
			return $post_types;

		$_post_types = get_post_types( $post_type_args, 'objects' );

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $singular ? $object->labels->singular_name : $object->label;
		}

		return $post_types;
	}

	/**
	 * Fetches available taxonomies
	 *
	 * @since 2.0.0
	 */
	public static function get_taxonomies_options() {

		$options = [];

		$taxonomies = get_taxonomies( array(
			'show_in_nav_menus' => true
		), 'objects' );

		if ( empty( $taxonomies ) ) {
			$options[ '' ] = __( 'No taxonomies found', 'elementor-extras' );
			return $options;
		}

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	/**
	 * Fetches available pages
	 *
	 * @since 2.0.0
	 */
	public static function get_pages_options() {

		$options = [];

		$pages = get_pages( array(
			'hierarchical' => false,
		) );

		if ( empty( $pages ) ) {
			$options[ '' ] = __( 'No pages found', 'elementor-extras' );
			return $options;
		}

		foreach ( $pages as $page ) {
			$options[ $page->ID ] = $page->post_title;
		}

		return $options;
	}

	/**
	 * Fetches available users
	 *
	 * @since 2.0.0
	 */
	public static function get_users_options() {

		$options = [];

		$users = get_users( array(
			'fields' => [ 'ID', 'display_name' ],
		) );

		if ( empty( $users ) ) {
			$options[ '' ] = __( 'No users found', 'elementor-extras' );
			return $options;
		}

		foreach ( $users as $user ) {
			$options[ $user->ID ] = $user->display_name;
		}

		return $options;
	}

	/**
	 * Get category with highest number of parents
	 * from a given list
	 *
	 * @since 2.0.0
	 */
	public static function get_most_parents_category( $categories = [] ) {

		$counted_cats = [];

		if ( ! is_array( $categories ) )
			return $categories;

		foreach ( $categories as $category ) {
			$category_parents = get_category_parents( $category->term_id, false, ',' );
			$category_parents = explode( ',', $category_parents );
			$counted_cats[ $category->term_id ] = count( $category_parents );
		}

		arsort( $counted_cats );
		reset( $counted_cats );

		return key( $counted_cats );
	}

	/**
	 * Get list of terms for a specific post ID
	 * from a taxonomy with highter number of terms used
	 *
	 * @since 2.0.0
	 */
	public static function get_parent_terms_highest( $post_id ) {

		$taxonomies = get_post_taxonomies( $post_id );
		$tax 		= $taxonomies[0];
		$tax_term_c = 0;

		foreach ( $taxonomies as $taxonomy => $name ) {
			$taxonomy_terms = wp_get_post_terms( $post_id, $name );

			if ( count( $taxonomy_terms ) > $tax_term_c ) {
				$tax_term_c = count( $taxonomy_terms );
				$tax 		= $name;
			}
		}

		$terms = wp_get_post_terms( $post_id, $tax );
		$terms = array_reverse( $terms );

		return $terms;
	}
}
