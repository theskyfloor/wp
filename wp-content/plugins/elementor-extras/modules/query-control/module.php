<?php
namespace ElementorExtras\Modules\QueryControl;

use ElementorExtras\Base\Module_Base;
use ElementorExtras\Controls\Control_Query as Query;

use Elementor\Controls_Manager;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public static $displayed_ids = [];

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'query-control';
	}

	protected function add_actions() {
		add_action( 'elementor/ajax/register_actions', [ $this, 'register_ajax_actions' ] );
	}

	public function ajax_call_filter_autocomplete( array $data ) {

		if ( empty( $data['query_type'] ) || empty( $data['q'] ) ) {
			throw new \Exception( 'Bad Request' );
		}

		$results = call_user_func( [ $this, 'get_autocomplete_for_' . $data['query_type'] ], $data );

		return [
			'results' => $results,
		];
	}

	protected function get_autocomplete_for_posts( $data ) {
		$results = [];

		$query_params = [
			'post_type' 		=> $data['object_type'],
			's' 				=> $data['q'],
			'posts_per_page' 	=> -1,
		];

		if ( 'attachment' === $query_params['post_type'] ) {
			$query_params['post_status'] = 'inherit';
		}

		$query = new \WP_Query( $query_params );

		foreach ( $query->posts as $post ) {
			$results[] = [
				'id' 	=> $post->ID,
				'text' 	=> $post->post_title,
			];
		}

		return $results;
	}

	protected function get_autocomplete_for_authors( $data ) {
		$results = [];

		$query_params = [
			'who' 					=> 'authors',
			'has_published_posts' 	=> true,
			'fields' 				=> [
				'ID',
				'display_name',
			],
			'search' 				=> '*' . $data['q'] . '*',
			'search_columns' 		=> [
				'user_login',
				'user_nicename',
			],
		];

		$user_query = new \WP_User_Query( $query_params );

		foreach ( $user_query->get_results() as $author ) {
			$results[] = [
				'id' 	=> $author->ID,
				'text' 	=> $author->display_name,
			];
		}

		return $results;
	}

	public function ajax_call_control_value_titles( $request ) {
		$results = call_user_func( [ $this, 'get_value_titles_for_' . $request['query_type'] ], $request );

		return $results;
	}

	protected function get_value_titles_for_posts( $request ) {
		$ids = (array) $request['id'];
		$results = [];

		$query = new \WP_Query( [
			'post_type' 		=> 'any',
			'post__in' 			=> $ids,
			'posts_per_page' 	=> -1,
		] );

		foreach ( $query->posts as $post ) {
			$results[ $post->ID ] = $post->post_title;
		}

		return $results;
	}

	protected function get_value_titles_for_authors( $request ) {
		$ids = (array) $request['id'];
		$results = [];

		$query_params = [
			'who' 					=> 'authors',
			'has_published_posts' 	=> true,
			'fields' 				=> [
				'ID',
				'display_name',
			],
			'include' 				=> $ids,
		];

		$user_query = new \WP_User_Query( $query_params );

		foreach ( $user_query->get_results() as $author ) {
			$results[ $author->ID ] = $author->display_name;
		}

		return $results;
	}

	public function register_ajax_actions( $ajax_manager ) {
		$ajax_manager->register_ajax_action( 'ee_query_control_value_titles', [ $this, 'ajax_call_control_value_titles' ] );
		$ajax_manager->register_ajax_action( 'ee_query_control_filter_autocomplete', [ $this, 'ajax_call_filter_autocomplete' ] );
	}
}
