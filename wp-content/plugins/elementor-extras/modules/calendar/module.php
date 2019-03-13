<?php
namespace ElementorExtras\Modules\Calendar;

use ElementorExtras\Base\Module_Base;
use ElementorExtras\Modules\CustomFields;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	/**
	 * Get name of the module
	 *
	 * @since 2.0.0
	 */
	public function get_name() {
		return 'calendar';
	}

	/**
	 * Return available widgets
	 *
	 * @since 2.0.0
	 */
	public function get_widgets() {
		return [
			'Calendar',
		];
	}

	/**
	 * Fetches available custom fields types
	 *
	 * @since 2.0.0
	 */
	public static function get_field_types() {
		$available_field_types = [];

		// ACF 5 and up
		if ( class_exists( '\acf' ) && function_exists( 'acf_get_field_groups' ) ) {
			$available_field_types['acf'] = __( 'ACF', 'elementor-extras' );
		}

		if ( function_exists( 'wpcf_admin_fields_get_groups' ) ) {
			$available_field_types['toolset'] = __( 'Toolset', 'elementor-extras' );
		}

		if ( function_exists( 'pods' ) ) {
			$available_field_types['pods'] = __( 'Pods', 'elementor-extras' );
		}

		return $available_field_types;
	}

	/**
	 * Loops through all posts of post type
	 * and calls the appropriate method to fetch
	 * all available custom fields
	 *
	 * @since 2.0.0
	 */
	public static function get_post_type_fields( $post_type = 'post', $method = 'acf' ) {

		// Return the fields for this cpt
		$meta_fields = [];

		// Fetch all posts of this type
		$the_query = new \WP_Query( [
			'post_type' => $post_type,
		] );

		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) { $the_query->the_post();
				
				$fields = call_user_func( 'self::get_' . $method . '_fields', get_the_ID() );

				if ( $fields ) {
					foreach( $fields as $name => $value ) {
						$meta_fields[ $name ] = $value;
					}
				}
			}
			wp_reset_postdata();
		}

		return $meta_fields;
	}

	/**
	 * Returns ACF fields for a post
	 *
	 * @since 2.0.0
	 */
	protected static function get_acf_fields( $post_id ) {
		if ( ! $post_id || ! function_exists( 'get_field_objects' ) )
			return;

		$_fields = [];
		$fields = get_field_objects( $post_id );

		if ( ! $fields )
			return; 

		foreach ( $fields as $name => $object ) {
			if ( 'date_picker' === $object['type'] || 'date_time_picker' === $object['type'] ) {
				$_fields[ $object['key'] ] = $object['label'];
			}
		}

		if ( $_fields )
			return $_fields;

		return false;
	}

	/**
	 * Returns Toolset fields for a post
	 *
	 * @since 2.0.0
	 */
	protected static function get_toolset_fields( $post_id ) {
		// Fallback to current post
		if ( ! $post_id )
			$post_id = get_the_ID();

		// Double check for key and toolset functions
		if ( ! function_exists( 'wpcf_admin_fields_get_groups' ) || ! function_exists( 'wpcf_admin_fields_get_fields_by_group' ) )
			return;

		$toolset_groups = wpcf_admin_fields_get_groups();

		$_fields = [];

		foreach ( $toolset_groups as $group ) {

			$options = [];

			$fields = wpcf_admin_fields_get_fields_by_group( $group['id'] );

			if ( ! is_array( $fields ) ) {
				continue;
			}

			foreach ( $fields as $field_key => $field ) {
				if ( ! is_array( $field ) || empty( $field['type'] ) || 'date' !== $field['type'] ) {
					continue;
				}

				// Use group ID for unique keys
				$key = $group['slug'] . ':' . $field_key;
				$options[ $key ] = $field['name'];
			}

			if ( empty( $options ) ) {
				continue;
			}

			foreach ( $options as $key => $value ) {
				$_fields[ $key ] = $value;
			}
		}

		return $_fields;
	}

	/**
	 * Returns Pods fields for a post
	 *
	 * @since 2.0.0
	 */
	protected static function get_pods_fields( $post_id ) {
		// Fallback to current post
		if ( ! $post_id )
			$post_id = get_the_ID();

		// Double check for key and pods function
		if ( ! $post_id || ! function_exists( 'pods_api' ) )
			return;

		$all_pods = pods_api()->load_pods( [
			'table_info' => true,
			'fields' => true,
		] );

		$_fields = [];

		foreach ( $all_pods as $group ) {
			$options = [];

			foreach ( $group['fields'] as $field ) {
				if ( ! in_array( $field['type'], [ 'date', 'datetime' ] ) ) {
					continue;
				}

				// Use pods ID for unique keys
				$key = $group['name'] . ':' . $field['pod_id'] . ':' . $field['name'];
				$options[ $key ] = $field['label'];
			}

			if ( empty( $options ) ) {
				continue;
			}

			$groups[] = [
				'label' => $group['name'],
				'options' => $options,
			];

			foreach ( $options as $key => $value ) {
				$_fields[ $key ] = $value;
			}
		}

		return $_fields;
	}

	/**
	 * Returns ACF field value given a key and a post
	 *
	 * @since 2.0.0
	 */
	public static function get_acf_field_value( $post_id, $key ) {

		// Fallback to current post
		if ( ! $post_id )
			$post_id = get_the_ID();

		// Double check for key and acf function
		if ( ! $key || ! function_exists( 'get_field_object' ) )
			return;

		// Get field object
		$field_object = get_field_object( $key, $post_id );

		// Check for valid value
		if ( ! $field_object['value'] )
			return;

		// Return the date in the appropriate format
		$date = \DateTime::createFromFormat( $field_object['display_format'], $field_object['value'] );

		if ( false !== $date )
			return $date->format( 'Y-m-d' );

		return false;
	}

	/**
	 * Returns Toolset field value given a key and a post
	 *
	 * @since 2.0.0
	 */
	public static function get_toolset_field_value( $post_id, $key ) {

		// Fallback to current post
		if ( ! $post_id )
			$post_id = get_the_ID();

		// Double check for key and toolset function
		if ( ! $key || ! function_exists( 'types_render_field' ) )
			return;

		list( $field_group, $field_key ) = explode( ':', $key );

		$field = wpcf_admin_fields_get_field( $field_key );
		$value = '';

		if ( $field && ! empty( $field['type'] ) && 'date' === $field['type'] ) {
			$timestamp = types_render_field( $field_key, [
				'post_id' 	=> $post_id,
				'output' 	=> 'raw',
				'style' 	=> 'text',
			] );

			$timestamp = (int)$timestamp;

			$value = date( 'Y-m-d', $timestamp );
		}

		return wp_kses_post( $value );
	}

	/**
	 * Returns Pods field value given a key and a post
	 *
	 * @since 2.0.0
	 */
	public static function get_pods_field_value( $post_id, $key ) {

		// Fallback to current post
		if ( ! $post_id )
			$post_id = get_the_ID();

		// Double check for key and pods function
		if ( ! $key || ! function_exists( 'pods' ) )
			return;

		list( $pod_name, $pod_id, $meta_key ) = explode( ':', $key );

		$pod = pods( $pod_name, $post_id );
		$field_data = [
			'field' => $pod->fields[ $meta_key ],
			'value' => $pod->field( $meta_key ),
			'display' => $pod->display( $meta_key ),
			'pod' => $pod,
			'key' => $meta_key,
		];
		$field = $field_data['field'];
		$value = empty( $field_data['value'] ) ? '' : $field_data['value'];

		if ( $field && ! empty( $field['type'] ) && in_array( $field['type'], [ 'date', 'datetime' ] ) ) {

			$timestamp = strtotime( $value );

			$value = date( 'Y-m-d', $timestamp );
		}

		return wp_kses_post( $value );
	}
}
