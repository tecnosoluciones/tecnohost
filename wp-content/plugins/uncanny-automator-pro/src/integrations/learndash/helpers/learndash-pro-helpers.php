<?php

namespace Uncanny_Automator_Pro;

use Uncanny_Automator\Learndash_Helpers;

/**
 * Class Learndash_Pro_Helpers
 *
 * @package Uncanny_Automator_Pro
 */
class Learndash_Pro_Helpers extends Learndash_Helpers {

	/**
	 * @var array
	 */
	private $group_hierarchy = array();

	/**
	 * Learndash_Pro_Helpers constructor.
	 */
	public function __construct( $load_action_hook = true ) {

		if ( true === $load_action_hook ) {
			add_action(
				'wp_ajax_select_lesson_from_course_MARKLESSONNOTCOMPLETE',
				array(
					$this,
					'select_lesson_from_course_no_any',
				)
			);
			add_action(
				'wp_ajax_select_lesson_from_course_MARKTOPICNOTCOMPLETE',
				array(
					$this,
					'lesson_from_course_func_no_any',
				),
				15
			);
			add_action(
				'wp_ajax_select_topic_from_lesson_MARKTOPICNOTCOMPLETE',
				array(
					$this,
					'topic_from_lesson_func_no_any',
				),
				15
			);

			add_action(
				'wp_ajax_select_lessontopic_from_course_LD_SUBMITASSIGNMENT',
				array(
					$this,
					'lesson_topic_from_course_func',
				),
				15
			);

			add_action(
				'wp_ajax_select_lessontopic_from_course_LD_ASSIGNMENT_GRADED',
				array(
					$this,
					'lesson_topic_from_course_func',
				),
				15
			);

			add_action(
				'wp_ajax_select_assignments_from_lessontopic_LD_ASSIGNMENT_GRADED',
				array(
					$this,
					'assignments_from_lessontopic_func',
				),
				15
			);

			// add all groups option
			add_filter(
				'uap_option_all_ld_groups',
				array(
					$this,
					'add_all_groups_option',
				),
				99,
				3
			);

			add_action(
				'wp_ajax_ld_select_quiz_essays',
				array(
					$this,
					'select_quiz_essays',
				)
			);

			add_action(
				'wp_ajax_ld_select_quiz_questions',
				array(
					$this,
					'ld_select_quiz_questions',
				)
			);

			add_action(
				'wp_ajax_select_lessontopic_from_course',
				array(
					$this,
					'lesson_topic_from_course_func',
				),
				15
			);

			add_action(
				'wp_ajax_select_quizzes_from_course_lessontopic',
				array(
					$this,
					'select_quizzes_from_course_lessontopic',
				),
				15
			);

			add_action(
				'wp_ajax_select_specific_quiz_from_course_lessontopic',
				array(
					$this,
					'select_specific_quiz_from_course_lessontopic',
				)
			);

			add_action(
				'wp_ajax_select_essay_questions_from_course_lessontopic_quiz',
				array(
					$this,
					'select_essay_questions_from_course_lessontopic_quiz',
				),
				15
			);

		}
	}

	/**
	 * @param Learndash_Pro_Helpers $pro
	 */
	public function setPro( Learndash_Pro_Helpers $pro ) {
		parent::setPro( $pro );
	}

	/**
	 * Return all the specific fields of a form ID provided in ajax call
	 *
	 * @return array
	 */
	public function lesson_topic_from_course_func() {

		// Nonce and post object validation
		Automator()->utilities->ajax_auth_check( $_POST );

		$fields = array();

		if ( ! isset( $_POST ) ) {
			echo wp_json_encode( $fields );
			die();
		}

		$ld_post_value = sanitize_text_field( automator_filter_input( 'value', INPUT_POST ) );

		$ld_course_id = sanitize_text_field( automator_filter_input( 'value', INPUT_POST ) );

		if ( 'automator_custom_value' === $ld_post_value && '-1' !== absint( $ld_post_value ) ) {
			if ( 'automator_custom_value' === (string) $ld_course_id ) {
				$ld_course_id = isset( $_POST['values']['LDCOURSE_custom'] ) ? absint( $_POST['values']['LDCOURSE_custom'] ) : 0;
			} else {
				$ld_course_id = absint( $ld_course_id );
			}
		}

		// Any is selected.
		if ( $ld_course_id < 0 ) {
			$fields = $this->get_all_lessons_and_topic_options();
			echo wp_json_encode( $fields );
			die();
		}

		$lessons = learndash_get_lesson_list( $ld_course_id, array( 'num' => 0 ) );

		foreach ( $lessons as $lesson ) {

			$fields[] = array(
				'value' => $lesson->ID,
				'text'  => $lesson->post_title,
			);

			$topics = learndash_get_topic_list( $lesson->ID, $ld_course_id );

			foreach ( $topics as $topic ) {
				$fields[] = array(
					'value' => $topic->ID,
					'text'  => $topic->post_title,
				);
			}
		}

		usort(
			$fields,
			function ( $a, $b ) {
				return strcasecmp( $a['text'], $b['text'] );
			}
		);

		if ( absint( '-1' ) === absint( $ld_course_id ) || true === $this->load_any_options ) {
			array_unshift(
				$fields,
				array(
					'value' => '-1',
					'text'  => __( 'Any lesson or topic', 'uncanny-automator-pro' ),
				)
			);
		}

		echo wp_json_encode( $fields );
		die();
	}

	/**
	 * Return all Lesson and topic options.
	 *
	 * @return array
	 */
	public function get_all_lessons_and_topic_options( $any = true ) {

		$args = array(
			'post_type'      => 'sfwd-lessons',
			'posts_per_page' => 999,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		);

		// Query for lessons.
		$lesson_options = Automator()->helpers->recipe->options->wp_query( $args );

		// Query for topics.
		$args['post_type'] = 'sfwd-topic';
		$topic_options     = Automator()->helpers->recipe->options->wp_query( $args );

		$options = array();
		if ( $any ) {
			$options[] = array(
				'value' => '-1',
				'text'  => __( 'Any lesson or topic', 'uncanny-automator-pro' ),
			);
		}

		foreach ( $lesson_options as $id => $title ) {
			$options[] = array(
				'value' => $id,
				'text'  => $title,
			);
		}

		if ( ! empty( $topic_options ) ) {
			foreach ( $topic_options as $id => $title ) {
				$options[] = array(
					'value' => $id,
					'text'  => $title,
				);
			}
		}

		return $options;
	}

	/**
	 * Get Assignment Options.
	 *
	 * @return array
	 */
	public function assignments_from_lessontopic_func() {

		// Nonce and post object validation
		Automator()->utilities->ajax_auth_check( $_POST );

		$options = array(
			array(
				'value' => '-1',
				'text'  => __( 'Any assignment', 'uncanny-automator-pro' ),
			),
		);

		if ( ! isset( $_POST ) ) {
			echo wp_json_encode( $options );
			die();
		}

		$ld_post_value = sanitize_text_field( automator_filter_input( 'value', INPUT_POST ) );
		$ld_lesson_id  = $ld_post_value;
		$ld_course_id  = sanitize_text_field( $_POST['values']['LDCOURSE'] );

		// Check custom course value.
		if ( 'automator_custom_value' === $ld_course_id && '-1' !== absint( $ld_course_id ) ) {
			$ld_course_id = isset( $_POST['values']['LDCOURSE_custom'] ) ? absint( $_POST['values']['LDCOURSE_custom'] ) : 0;
		}

		// Check custom lesson value.
		if ( 'automator_custom_value' === $ld_post_value && '-1' !== absint( $ld_post_value ) ) {
			if ( 'automator_custom_value' === (string) $ld_lesson_id ) {
				$ld_lesson_id = isset( $_POST['values']['LDSTEP_custom'] ) ? absint( $_POST['values']['LDSTEP_custom'] ) : 0;
			} else {
				$ld_lesson_id = absint( $ld_lesson_id );
			}
		}

		$ld_course_id = $ld_course_id > 0 ? $ld_course_id : 0;
		$ld_lesson_id = $ld_lesson_id > 0 ? $ld_lesson_id : 0;
		$assignments  = $this->get_assignments( $ld_course_id, $ld_lesson_id );

		if ( ! empty( $assignments ) ) {
			foreach ( $assignments as $assignment ) {
				$options[] = array(
					'value' => $assignment->ID,
					'text'  => $assignment->post_title,
				);
			}
		}

		echo wp_json_encode( $options );
		die();
	}

	/**
	 * Get Assignments Query.
	 *
	 * @param int $course_id
	 * @param int $lesson_id
	 * @param int $user_id
	 * @param int $assignment_id
	 *
	 * @return array
	 */
	public function get_assignments( $course_id = 0, $lesson_id = 0, $user_id = 0, $assignment_id = 0 ) {

		$args = array(
			'post_type'   => 'sfwd-assignment',
			'numberposts' => - 1,
		);

		if ( ! empty( $assignment_id ) ) {
			$args['include'] = array( $assignment_id );
		}

		if ( ! empty( $user_id ) ) {
			$args['author'] = (int) $user_id;
		}

		$meta_query = array();
		if ( ! empty( $course_id ) ) {
			$meta_query[] = array(
				'key'     => 'course_id',
				'value'   => (int) $course_id,
				'compare' => '=',
			);
		}

		if ( ! empty( $lesson_id ) ) {
			$meta_query[] = array(
				'key'     => 'lesson_id',
				'value'   => (int) $lesson_id,
				'compare' => '=',
			);
		}

		if ( ! empty( $meta_query ) ) {
			$args['meta_query'] = $meta_query;
			if ( count( $meta_query ) > 1 ) {
				$args['meta_query']['relation'] = 'AND';
			}
		}

		return get_posts( $args );
	}

	/**
	 * @param string $label
	 * @param string $option_code
	 *
	 * @return mixed
	 */
	public function all_ld_certificates( $label = null, $option_code = 'LDCERTIFICATES', $args = array() ) {

		if ( ! $label ) {
			$label = __( 'Certificate', 'uncanny-automator-pro' );
		}

		$token        = key_exists( 'token', $args ) ? $args['token'] : false;
		$is_ajax      = key_exists( 'is_ajax', $args ) ? $args['is_ajax'] : false;
		$target_field = key_exists( 'target_field', $args ) ? $args['target_field'] : '';
		$end_point    = key_exists( 'endpoint', $args ) ? $args['endpoint'] : '';
		$include_all  = key_exists( 'include_all', $args ) ? $args['include_all'] : false;

		$args = array(
			'post_type'      => 'sfwd-certificates',
			'posts_per_page' => 9999,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		);

		$options = Automator()->helpers->recipe->options->wp_query( $args, $include_all, __( 'Any certificate', 'uncanny-automator-pro' ) );

		$pattern = get_shortcode_regex();
		foreach ( $options as $key => $option ) {
			$content = get_post_field( 'post_content', $key );
			if ( preg_match_all( '/' . $pattern . '/s', $content, $matches )
				 && array_key_exists( 2, $matches )
				 && ( in_array( 'quizinfo', $matches[2] ) || in_array( 'courseinfo', $matches[2] ) )
			) {
				// shortcode is being used
				unset( $options[ $key ] );
			}
		}

		$option = array(
			'option_code'     => $option_code,
			'label'           => $label,
			'input_type'      => 'select',
			'required'        => true,
			'options'         => $options,
			'relevant_tokens' => array(
				$option_code          => __( 'Certificate title', 'uncanny-automator-pro' ),
				$option_code . '_ID'  => __( 'Certificate ID', 'uncanny-automator-pro' ),
				$option_code . '_URL' => __( 'Certificate URL', 'uncanny-automator-pro' ),
			),
		);

		return apply_filters( 'uap_option_all_ld_certificates', $option );
	}


	/**
	 * @param $matches
	 *
	 * @return string
	 */
	public function post2pdf_conv_image_align_center( $matches ) {
		$tag_begin = '<p class="post2pdf_conv_image_align_center">';
		$tag_end   = '</p>';

		return $tag_begin . $matches[1] . $tag_end;
	}

	/**
	 * @param $matches
	 *
	 * @return string
	 */
	public function post2pdf_conv_img_size( $matches ) {
		$size = null;

		if ( strpos( $matches[2], site_url() ) === false ) {
			return $matches[1] . $matches[5];
		}

		$image_path = ABSPATH . str_replace( site_url() . '/', '', $matches[2] );

		if ( is_file( $image_path ) ) {
			$size = getimagesize( $image_path );
		} else {
			return $matches[1] . $matches[5];
		}

		return $matches[1] . ' ' . $size[3] . $matches[5];
	}

	/**
	 * Adds the height and width to the image tag.
	 *
	 * Used as a callback in `preg_replace_callback` function.
	 *
	 * @param array $matches array with strings to search and replace.
	 *
	 * @return string The image align center markup.
	 */
	public function learndash_post2pdf_conv_img_size( $matches ) {
		global $q_config;
		$size = null;

		if ( strpos( $matches[2], site_url() ) === false ) {
			return $matches[1] . $matches[5];
		}

		$image_path = ABSPATH . str_replace( site_url() . '/', '', $matches[2] );

		if ( is_file( $image_path ) ) {
			$size = getimagesize( $image_path );
		} else {
			return $matches[1] . $matches[5];
		}

		return $matches[1] . ' ' . $size[3] . $matches[5];
	}

	/**
	 * Grab all attributes for a given shortcode in a text
	 *
	 * @param string $tag Shortcode tag
	 * @param string $text Text containing shortcodes
	 *
	 * @return array  $out   Array of attributes
	 * @uses shortcode_parse_atts()
	 * @uses get_shortcode_regex()
	 */
	public function maybe_extract_shorcode_attributes( $tag, $text ) {
		preg_match_all( '/' . get_shortcode_regex() . '/s', $text, $matches );
		$out = array();
		if ( isset( $matches[2] ) ) {
			foreach ( (array) $matches[2] as $key => $value ) {
				if ( $tag === $value ) {
					$out = shortcode_parse_atts( $matches[3][ $key ] );
				}
			}
		}

		return $out;
	}

	/**
	 * @param $this ->post_id
	 *
	 * @return string
	 */
	public function learndash_get_thumb_path( $post_id ) {
		$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		if ( $thumbnail_id ) {
			$img_path      = get_post_meta( $thumbnail_id, '_wp_attached_file', true );
			$upload_url    = wp_upload_dir();
			$img_full_path = $upload_url['basedir'] . '/' . $img_path;

			return $img_full_path;
		}
	}

	/**
	 * Adds the markup to align image to center.
	 *
	 * Used as callback in `preg_replace_callback` function.
	 *
	 * @param array $matches An array with strings to search and replace.
	 *
	 * @return string Image align center output.
	 */
	public function learndash_post2pdf_conv_image_align_center( $matches ) {
		$tag_begin = '<p class="post2pdf_conv_image_align_center">';
		$tag_end   = '</p>';

		return $tag_begin . $matches[1] . $tag_end;
	}

	/**
	 * @return string
	 */
	public function get_learndash_plugin_directory() {
		$all_plugins = get_plugins();
		$dir         = '';
		if ( $all_plugins ) {
			foreach ( $all_plugins as $key => $plugin ) {
				if ( 'LearnDash LMS' === $plugin['Name'] ) {
					$dir = plugin_dir_path( $key );

					return WP_PLUGIN_DIR . '/' . $dir;
					break;
				}
			}
		}

		return $dir;
	}

	/**
	 * @param        $args
	 * @param        $body
	 * @param string $certificate_type
	 * @param string $custom_css
	 *
	 * @return array
	 */
	public function generate_pdf( $args, $body, $certificate_type = 'course', $custom_css = '' ) {
		$save_path = $args['save_path'];
		$file_name = $args['file_name'];
		$user      = ( isset( $args['user'] ) ) ? $args['user'] : wp_get_current_user();

		$cert_args_defaults = array(
			'cert_id'       => 0,        // The certificate Post ID.
			'post_id'       => 0,     // The Course/Quiz Post ID.
			'user_id'       => 0,        // The User ID for the Certificate.
			'lang'          => 'eng', // The default language.
			'filename'      => '',
			'filename_url'  => '',
			'filename_type' => 'title',
			'pdf_title'     => '',
			'ratio'         => 1.25,
		);

		$cert_args = shortcode_atts( $cert_args_defaults, $args );

		// Just to ensure we have valid IDs.
		$cert_args['cert_id'] = absint( $args['certificate_post'] );
		$cert_args['user_id'] = absint( $user->ID );

		if ( 'preview' === (string) $certificate_type || 'automator' === (string) $certificate_type ) {
			$cert_args['post_id'] = absint( $args['certificate_post'] );
		}

		if ( empty( $cert_args['cert_id'] ) ) {
			if ( isset( $_GET['id'] ) ) {
				$cert_args['cert_id'] = absint( $_GET['id'] );
			} else {
				$cert_args['cert_id'] = get_the_id();
			}
		}

		if ( empty( $cert_args['user_id'] ) ) {
			if ( isset( $_GET['user'] ) ) {
				$cert_args['user_id'] = absint( $_GET['user'] );
			} elseif ( isset( $_GET['user_id'] ) ) {
				$cert_args['user_id'] = absint( $_GET['user_id'] );
			}
		}

		$cert_args['cert_post'] = get_post( $cert_args['cert_id'] );

		if ( ( ! $cert_args['cert_post'] ) || ( ! is_a( $cert_args['cert_post'], 'WP_Post' ) ) || ( learndash_get_post_type_slug( 'certificate' ) !== $cert_args['cert_post']->post_type ) ) {
			return array(
				'return'  => false,
				'message' => esc_html__( 'Certificate Post does not exist.', 'uncanny-automator-pro' ),
			);
		}

		$cert_args['post_post'] = get_post( $cert_args['post_id'] );

		if ( ( ! $cert_args['post_post'] ) || ( ! is_a( $cert_args['post_post'], 'WP_Post' ) ) ) {
			return array(
				'return'  => false,
				'message' => esc_html__( 'Awarded Post does not exist.', 'uncanny-automator-pro' ),
			);
		}

		$cert_args['user'] = get_user_by( 'ID', $cert_args['user_id'] );

		if ( ( ! $cert_args['user'] ) || ( ! is_a( $cert_args['user'], 'WP_User' ) ) ) {
			return array(
				'return'  => false,
				'message' => esc_html__( 'User does not exist.', 'uncanny-automator-pro' ),
			);
		}

		// Start config override section.

		// Language codes in TCPDF are 3 character eng, fra, ger, etc.
		/**
		 * We check for cert_lang=xxx first since it may need to be different than
		 * lang=yyy.
		 */
		$config_lang_tmp = 'eng';
		if ( ( isset( $_GET['cert_lang'] ) ) && ( ! empty( $_GET['cert_lang'] ) ) ) {
			$config_lang_tmp = substr( esc_attr( $_GET['cert_lang'] ), 0, 3 );
		} elseif ( ( isset( $_GET['lang'] ) ) && ( ! empty( $_GET['lang'] ) ) ) {
			$config_lang_tmp = substr( esc_attr( $_GET['lang'] ), 0, 3 );
		}

		if ( ( ! empty( $config_lang_tmp ) ) && ( strlen( $config_lang_tmp ) == 3 ) ) {
			$ld_cert_lang_dir = LEARNDASH_LMS_LIBRARY_DIR . '/tcpdf/config/lang';
			$lang_files       = array_diff(
				scandir( $ld_cert_lang_dir ),
				array(
					'..',
					'.',
				)
			);
			if ( ( ! empty( $lang_files ) ) && ( is_array( $lang_files ) ) && ( in_array( $config_lang_tmp, $lang_files, true ) ) && ( file_exists( $ld_cert_lang_dir . '/' . $config_lang_tmp . '.php' ) ) ) {
				$cert_args['lang'] = $config_lang_tmp;
			}
		}

		$target_post_id             = 0;
		$cert_args['filename_type'] = 'title';

		$logo_file = $logo_enable = $subsetting_enable = $filters = $header_enable = $footer_enable = $monospaced_font = $font = $font_size = '';

		ob_start();

		$cert_args['cert_title'] = $cert_args['cert_post']->post_title;
		$cert_args['cert_title'] = strip_tags( $cert_args['cert_title'] );

		/** This filter is documented in https://developer.wordpress.org/reference/hooks/document_title_separator/ */
		$sep = apply_filters( 'document_title_separator', '-' );

		/**
		 * Filters username of the user to be used in creating certificate PDF.
		 *
		 * @param string $user_name User display name.
		 * @param int $user_id User ID.
		 * @param int $cert_id Certificate post ID.
		 */
		$learndash_pdf_username = apply_filters( 'learndash_pdf_username', $cert_args['user']->display_name, $cert_args['user_id'], $cert_args['cert_id'] );
		if ( ! empty( $learndash_pdf_username ) ) {
			if ( ! empty( $cert_args['pdf_title'] ) ) {
				$cert_args['pdf_title'] .= " $sep ";
			}
			$cert_args['pdf_title'] .= $learndash_pdf_username;
		}

		$cert_for_post_title = get_the_title( $cert_args['post_id'] );
		strip_tags( $cert_for_post_title );
		if ( ! empty( $cert_for_post_title ) ) {
			if ( ! empty( $cert_args['pdf_title'] ) ) {
				$cert_args['pdf_title'] .= " $sep ";
			}
			$cert_args['pdf_title'] .= $cert_for_post_title;
		}

		if ( ! empty( $cert_args['pdf_title'] ) ) {
			$cert_args['pdf_title'] .= " $sep ";
		}
		$cert_args['pdf_title'] .= $cert_args['cert_title'];

		if ( ! empty( $cert_args['pdf_title'] ) ) {
			$cert_args['pdf_title'] .= " $sep ";
		}
		$cert_args['pdf_title'] .= get_bloginfo( 'name', 'display' );

		$cert_args['cert_permalink']  = get_permalink( $cert_args['cert_post']->ID );
		$cert_args['pdf_author_name'] = $cert_args['user']->display_name;

		$tags_array                = array();
		$cert_args['pdf_keywords'] = '';
		$tags_data                 = wp_get_post_tags( $cert_args['cert_post']->ID );

		if ( $tags_data ) {
			foreach ( $tags_data as $val ) {
				$tags_array[] = $val->name;
			}
			$cert_args['pdf_keywords'] = implode( ' ', $tags_array );
		}

		if ( ! empty( $_GET['font'] ) ) {
			$font = esc_html( $_GET['font'] );
		}

		if ( ! empty( $_GET['monospaced'] ) ) {
			$monospaced_font = esc_html( $_GET['monospaced'] );
		}

		if ( ! empty( $_GET['fontsize'] ) ) {
			$font_size = intval( $_GET['fontsize'] );
		}

		if ( ! empty( $_GET['subsetting'] ) && ( $_GET['subsetting'] == 1 || $_GET['subsetting'] == 0 ) ) {
			$subsetting_enable = $_GET['subsetting'];
		}

		if ( $subsetting_enable == 1 ) {
			$subsetting = 'true';
		} else {
			$subsetting = 'false';
		}

		if ( ! empty( $_GET['ratio'] ) ) {
			$cert_args['ratio'] = floatval( $_GET['ratio'] );
		}

		if ( ! empty( $_GET['header'] ) ) {
			$header_enable = $_GET['header'];
		}

		if ( ! empty( $_GET['logo'] ) ) {
			$logo_enable = $_GET['logo'];
		}

		if ( ! empty( $_GET['logo_file'] ) ) {
			$logo_file = esc_html( $_GET['logo_file'] );
		}

		if ( ! empty( $_GET['logo_width'] ) ) {
			$logo_width = intval( $_GET['logo_width'] );
		}

		if ( ! empty( $_GET['footer'] ) ) {
			$footer_enable = $_GET['footer'];
		}

		/**
		 * Start Cert post content processing.
		 */
		if ( ! defined( 'LEARNDASH_TCPDF_LEGACY_LD322' ) ) {
			$use_LD322_define = apply_filters( 'learndash_tcpdf_legacy_ld322', true, $cert_args );
			define( 'LEARNDASH_TCPDF_LEGACY_LD322', $use_LD322_define );
		}

		$cert_content = ! empty( $body ) ? html_entity_decode( $body ) : $cert_args['cert_post']->post_content;

		// Delete shortcode for POST2PDF Converter
		$cert_content = preg_replace( '|\[pdf[^\]]*?\].*?\[/pdf\]|i', '', $cert_content );

		$cert_content = $this->generate_certificate_contents( $cert_content, $args );

		$cert_content = do_shortcode( $cert_content );

		// Convert relative image path to absolute image path
		$cert_content = preg_replace( "/<img([^>]*?)src=['\"]((?!(http:\/\/|https:\/\/|\/))[^'\"]+?)['\"]([^>]*?)>/i", '<img$1src="' . site_url() . '/$2"$4>', $cert_content );

		// Set image align to center
		$cert_content = preg_replace_callback(
			"/(<img[^>]*?class=['\"][^'\"]*?aligncenter[^'\"]*?['\"][^>]*?>)/i",
			array(
				$this,
				'learndash_post2pdf_conv_image_align_center',
			),
			$cert_content
		);

		// Add width and height into image tag
		$cert_content = preg_replace_callback(
			"/(<img[^>]*?src=['\"]((http:\/\/|https:\/\/|\/)[^'\"]*?(jpg|jpeg|gif|png))['\"])([^>]*?>)/i",
			array(
				$this,
				'learndash_post2pdf_conv_img_size',
			),
			$cert_content
		);

		if ( ( ! defined( 'LEARNDASH_TCPDF_LEGACY_LD322' ) ) || ( true !== LEARNDASH_TCPDF_LEGACY_LD322 ) ) {
			$cert_content = wpautop( $cert_content );
		}

		// For other sourcecode
		$cert_content = preg_replace( '/<pre[^>]*?><code[^>]*?>(.*?)<\/code><\/pre>/is', '<pre style="word-wrap:break-word; color: #406040; background-color: #F1F1F1; border: 1px solid #9F9F9F;">$1</pre>', $cert_content );

		// For blockquote
		$cert_content = preg_replace( '/<blockquote[^>]*?>(.*?)<\/blockquote>/is', '<blockquote style="color: #406040;">$1</blockquote>', $cert_content );

		$cert_content = '<br/><br/>' . $cert_content;

		/**
		 * If the $font variable is not empty we use it to replace all font
		 * definitions. This only affects inline styles within the structure
		 * of the certificate content HTML elements.
		 */
		if ( ! empty( $font ) ) {
			$cert_content = preg_replace( '/(<[^>]*?font-family[^:]*?:)([^;]*?;[^>]*?>)/is', '$1' . $font . ',$2', $cert_content );
		}

		if ( ( defined( 'LEARNDASH_TCPDF_LEGACY_LD322' ) ) && ( true === LEARNDASH_TCPDF_LEGACY_LD322 ) ) {
			$cert_content = preg_replace( '/\n/', '<br/>', $cert_content ); //"\n" should be treated as a next line
		}

		/**
		 * Filters whether to include certificate CSS styles in certificate content or not.
		 *
		 * @param boolean $include_certificate_styles Whether to include certificate styles.
		 * @param int $cert_id Certificate post ID.
		 */
		$certificate_styles = '';
		if ( apply_filters( 'learndash_certificate_styles', true, $cert_args['cert_id'] ) ) {
			$setting_styles = \LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Certificates_Styles', 'styles' );
			$setting_styles = preg_replace( '/<style[^>]*?>(.*?)<\/style>/is', '$1', $certificate_styles );
			if ( ! empty( $setting_styles ) ) {
				$certificate_styles = $setting_styles;
			}
		}

		// Add custom CSS styles.
		if ( ! empty( $custom_css ) ) {
			$custom_css         = preg_replace( '/<style[^>]*?>(.*?)<\/style>/is', '$1', $custom_css );
			$certificate_styles .= $custom_css;
		}

		// Add style tag to the certificate content.
		if ( ! empty( $certificate_styles ) ) {
			$cert_content = '<style>' . $certificate_styles . '</style>' . $cert_content;
		}

		/**
		 * Filters certificate content after all processing.
		 *
		 * @param string $cert_content Certificate post content HTML/TEXT.
		 * @param int $cert_id Certificate post ID.
		 *
		 * @since 3.2.0
		 */
		$cert_content = apply_filters( 'learndash_certificate_content', $cert_content, $cert_args['cert_id'] );

		/**
		 * Build the PDF Certificate using TCPDF.
		 */
		if ( ! class_exists( 'TCPDF' ) ) {
			require_once LEARNDASH_LMS_LIBRARY_DIR . '/tcpdf/config/lang/' . $cert_args['lang'] . '.php';
			require_once LEARNDASH_LMS_LIBRARY_DIR . '/tcpdf/tcpdf.php';
		}

		$learndash_certificate_options = get_post_meta( $cert_args['cert_post']->ID, 'learndash_certificate_options', true );
		if ( ! is_array( $learndash_certificate_options ) ) {
			$learndash_certificate_options = array( $learndash_certificate_options );
		}

		if ( ! isset( $learndash_certificate_options['pdf_page_format'] ) ) {
			$learndash_certificate_options['pdf_page_format'] = PDF_PAGE_FORMAT;
		}

		if ( ! isset( $learndash_certificate_options['pdf_page_orientation'] ) ) {
			$learndash_certificate_options['pdf_page_orientation'] = PDF_PAGE_ORIENTATION;
		}

		if ( isset( $args['orientation'] ) ) {
			$learndash_certificate_options['pdf_page_orientation'] = $args['orientation'];
		}

		// Create a new object
		$tcpdf_params = array(
			'orientation' => $learndash_certificate_options['pdf_page_orientation'],
			'unit'        => PDF_UNIT,
			'format'      => $learndash_certificate_options['pdf_page_format'],
			'unicode'     => true,
			'encoding'    => 'UTF-8',
			'diskcache'   => false,
			'pdfa'        => false,
			'margins'     => array(
				'top'    => PDF_MARGIN_TOP,
				'right'  => PDF_MARGIN_RIGHT,
				'bottom' => PDF_MARGIN_BOTTOM,
				'left'   => PDF_MARGIN_LEFT,
			),
		);

		/**
		 * Filters certificate tcpdf paramaters.
		 *
		 * @param array $tcpdf_params An array of tcpdf parameters.
		 * @param int $cert_id Certificate post ID.
		 *
		 * @since 2.4.7
		 */
		$tcpdf_params = apply_filters( 'learndash_certificate_params', $tcpdf_params, $cert_args['cert_id'] );

		$pdf = new \TCPDF(
			$tcpdf_params['orientation'],
			$tcpdf_params['unit'],
			$tcpdf_params['format'],
			$tcpdf_params['unicode'],
			$tcpdf_params['encoding'],
			$tcpdf_params['diskcache'],
			$tcpdf_params['pdfa']
		);

		// Added to let external manipulate the $pdf instance.
		/**
		 * Fires after creating certificate `TCPDF` class object.
		 *
		 * @param \TCPDF $pdf `TCPDF` class instance.
		 * @param int $cert_id Certificate post ID.
		 *
		 * @since 2.4.7
		 */
		do_action( 'learndash_certification_created', $pdf, $cert_args['cert_id'] );

		// Set document information

		/**
		 * Filters the value of pdf creator.
		 *
		 * @param string $pdf_creator The name of the PDF creator.
		 * @param \TCPDF $pdf `TCPDF` class instance.
		 * @param int $cert_id Certificate post ID.
		 */
		$pdf->SetCreator( apply_filters( 'learndash_pdf_creator', PDF_CREATOR, $pdf, $cert_args['cert_id'] ) );

		/**
		 * Filters the name of the pdf author.
		 *
		 * @param string $pdf_author_name PDF author name.
		 * @param \TCPDF $pdf `TCPDF` class instance.
		 * @param int $cert_id Certificate post ID.
		 */
		$pdf->SetAuthor( apply_filters( 'learndash_pdf_author', $cert_args['pdf_author_name'], $pdf, $cert_args['cert_id'] ) );

		/**
		 * Filters the title of the pdf.
		 *
		 * @param string $pdf_title PDF title.
		 * @param \TCPDF $pdf `TCPDF` class instance.
		 * @param int $cert_id Certificate post ID.
		 */
		$pdf->SetTitle( apply_filters( 'learndash_pdf_title', $cert_args['pdf_title'], $pdf, $cert_args['cert_id'] ) );

		/**
		 * Filters the subject of the pdf.
		 *
		 * @param string $pdf_subject PDF subject
		 * @param \TCPDF $pdf `TCPDF` class instance.
		 * @param int $cert_id Certificate post ID.
		 */
		$pdf->SetSubject( apply_filters( 'learndash_pdf_subject', strip_tags( get_the_category_list( ',', '', $cert_args['cert_id'] ) ), $pdf, $cert_args['cert_id'] ) );

		/**
		 * Filters the pdf keywords.
		 *
		 * @param string $pdf_keywords PDF keywords.
		 * @param \TCPDF $pdf `TCPDF` class instance.
		 * @param int $cert_id Certificate post ID.
		 */
		$pdf->SetKeywords( apply_filters( 'learndash_pdf_keywords', $cert_args['pdf_keywords'], $pdf, $cert_args['cert_id'] ) );

		// Set header data
		if ( mb_strlen( $cert_args['cert_title'], 'UTF-8' ) < 42 ) {
			$header_title = $cert_args['cert_title'];
		} else {
			$header_title = mb_substr( $cert_args['cert_title'], 0, 42, 'UTF-8' ) . '...';
		}

		if ( $header_enable == 1 ) {
			if ( $logo_enable == 1 && $logo_file ) {
				$pdf->SetHeaderData( $logo_file, $logo_width, $header_title, 'by ' . $cert_args['pdf_author_name'] . ' - ' . $cert_args['cert_permalink'] );
			} else {
				$pdf->SetHeaderData( '', 0, $header_title, 'by ' . $cert_args['pdf_author_name'] . ' - ' . $cert_args['cert_permalink'] );
			}
		}

		// Set header and footer fonts
		if ( $header_enable == 1 ) {
			$pdf->setHeaderFont( array( $font, '', PDF_FONT_SIZE_MAIN ) );
		}

		if ( $footer_enable == 1 ) {
			$pdf->setFooterFont( array( $font, '', PDF_FONT_SIZE_DATA ) );
		}

		// Remove header/footer
		if ( $header_enable == 0 ) {
			$pdf->setPrintHeader( false );
		}

		if ( $header_enable == 0 ) {
			$pdf->setPrintFooter( false );
		}

		// Set default monospaced font
		$pdf->SetDefaultMonospacedFont( $monospaced_font );

		// Set margins
		$pdf->SetMargins( $tcpdf_params['margins']['left'], $tcpdf_params['margins']['top'], $tcpdf_params['margins']['right'] );

		if ( $header_enable == 1 ) {
			$pdf->SetHeaderMargin( PDF_MARGIN_HEADER );
		}

		if ( $footer_enable == 1 ) {
			$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );
		}

		// Set auto page breaks
		$pdf->SetAutoPageBreak( true, $tcpdf_params['margins']['bottom'] );

		// Set image scale factor
		if ( ! empty( $cert_args['ratio'] ) ) {
			$pdf->setImageScale( $cert_args['ratio'] );
		}

		// Set some language-dependent strings
		if ( isset( $l ) ) {
			$pdf->setLanguageArray( $l );
		}

		// Set fontsubsetting mode
		$pdf->setFontSubsetting( $subsetting );

		// Set font
		if ( ( ! empty( $font ) ) && ( ! empty( $font_size ) ) ) {
			$pdf->SetFont( $font, '', $font_size, true );
		}

		// Add a page
		$pdf->AddPage();

		// Added to let external manipulate the $pdf instance.
		/**
		 * Fires after setting certificate pdf data.
		 *
		 * @param \TCPDF $pdf `TCPDF` class instance.
		 * @param int $post_id Post ID.
		 *
		 * @since 2.4.7
		 */
		do_action( 'learndash_certification_after', $pdf, $cert_args['cert_id'] );

		// get featured image
		$img_file = $this->learndash_get_thumb_path( $cert_args['cert_id'] );

		//Only print image if it exists
		if ( ! empty( $img_file ) ) {

			//Print BG image
			$pdf->setPrintHeader( false );

			// get the current page break margin
			$bMargin = $pdf->getBreakMargin();

			// get current auto-page-break mode
			$auto_page_break = $pdf->getAutoPageBreak();

			// disable auto-page-break
			$pdf->SetAutoPageBreak( false, 0 );

			// Get width and height of page for dynamic adjustments
			$pageH = $pdf->getPageHeight();
			$pageW = $pdf->getPageWidth();

			//Print the Background
			$pdf->Image( $img_file, '0', '0', $pageW, $pageH, '', '', '', false, 300, '', false, false, 0, false, false, false, false, array() );

			// restore auto-page-break status
			$pdf->SetAutoPageBreak( $auto_page_break, $bMargin );

			// set the starting point for the page content
			$pdf->setPageMark();
		}

		// Print post
		$pdf->writeHTMLCell( 0, 0, '', '', $cert_content, 0, 1, 0, true, '', true );

		// Set background
		$pdf->SetFillColor( 255, 255, 127 );
		$pdf->setCellPaddings( 0, 0, 0, 0 );
		// Print signature

		ob_clean();

		$full_path = $save_path . $file_name . '.pdf';

		switch ( $certificate_type ) {
			case 'quiz':
				$output = apply_filters( 'automator_generate_quiz_certificate_tcpdf_dest', 'F' );
				break;
			case 'course':
				$output = apply_filters( 'automator_generate_course_certificate_tcpdf_dest', 'F' );
				break;
			case 'automator':
				$output = apply_filters( 'automator_certificate_tcpdf_dest', 'F' );
				break;
			default:
				$output = apply_filters( 'automator_generate_quiz_certificate_tcpdf_dest', 'I' );
				break;

		}

		$pdf->Output( $full_path, $output ); /* F means saving on server. */

		return array(
			'return'  => true,
			'message' => $full_path,
		);

	}

	/**
	 * @param $cert_content
	 * @param $args
	 *
	 * @return mixed|string|string[]|void
	 */
	public function generate_certificate_contents( $cert_content, $args ) {
		$user            = $args['user'];
		$completion_time = current_time( 'timestamp' );
		$format          = 'F d, Y';
		preg_match( '/\[courseinfo(.*?)(completed_on)(.*?)\]/', $cert_content, $courseinfo_match );
		if ( $courseinfo_match && is_array( $courseinfo_match ) ) {
			$text        = $courseinfo_match[0];
			$date_format = $this->maybe_extract_shorcode_attributes( 'courseinfo', $text );
			if ( $date_format ) {
				$format = key_exists( 'format', $date_format ) ? $date_format['format'] : $format;
			}
		}
		$cert_content = preg_replace( '/\[courseinfo(.*?)(course_title)(.*?)\]/', '', $cert_content );
		$cert_content = preg_replace( '/\[courseinfo(.*?)(completed_on)(.*?)\]/', date_i18n( $format, $completion_time ), $cert_content );
		$cert_content = preg_replace( '/(\[usermeta)/', '[usermeta user_id="' . $user->ID . '" ', $cert_content );

		return apply_filters( 'automator_certificate_contents', $cert_content, $args );
	}

	/**
	 * @param $label
	 * @param $option_code
	 * @param $all_label
	 * @param $any_option
	 *
	 * @return array|mixed|void
	 */
	public function all_ld_groups_with_hierarchy( $label = null, $option_code = 'LDGROUP', $all_label = false, $any_option = false, $only_with_child = false, $relevant_tokens = array() ) {
		if ( ! $this->load_options ) {

			return Automator()->helpers->recipe->build_default_options_array( $label, $option_code );
		}

		if ( ! $label ) {
			$label = esc_attr__( 'Group', 'uncanny-automator-pro' );
		}
		if ( ! function_exists( 'learndash_is_groups_hierarchical_enabled' ) ) {
			return $this->all_ld_groups( $label, $option_code, $all_label, $any_option );
		}
		if ( false === self::is_group_hierarchy_enabled() ) {
			return $this->all_ld_groups( $label, $option_code, $all_label, $any_option );
		}
		$args                  = array(
			'post_type'      => 'groups',
			'posts_per_page' => 9999,
			//phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
			'post_parent'    => 0,
		);
		$results               = get_posts( $args );
		$this->group_hierarchy = array();
		if ( $results ) {
			foreach ( $results as $r ) {
				if ( true === $only_with_child && empty( learndash_get_group_children( $r->ID ) ) ) {
					continue;
				}
				$group_id                           = $r->ID;
				$title                              = $r->post_title;
				$this->group_hierarchy[ $group_id ] = $title;
				$this->get_children( $group_id, 1, $only_with_child );
			}
		}

		if ( true === $any_option ) {
			$this->group_hierarchy = array( '-1' => __( 'Any group', 'uncanny-automator-pro' ) ) + $this->group_hierarchy;
		}

		if ( true === $all_label ) {
			$this->group_hierarchy = array( '-1' => __( 'All groups', 'uncanny-automator-pro' ) ) + $this->group_hierarchy;
		}

		$default_tokens = array(
			$option_code                => esc_attr__( 'Group title', 'uncanny-automator-pro' ),
			$option_code . '_ID'        => esc_attr__( 'Group ID', 'uncanny-automator-pro' ),
			$option_code . '_URL'       => esc_attr__( 'Group URL', 'uncanny-automator-pro' ),
			$option_code . '_THUMB_ID'  => esc_attr__( 'Group featured image ID', 'uncanny-automator-pro' ),
			$option_code . '_THUMB_URL' => esc_attr__( 'Group featured image URL', 'uncanny-automator-pro' ),
		);

		if ( ! empty( $relevant_tokens ) ) {
			$default_tokens = array_merge( $default_tokens, $relevant_tokens );
		}

		$option = array(
			'option_code'              => $option_code,
			'label'                    => $label,
			'input_type'               => 'select',
			'required'                 => true,
			'options'                  => $this->group_hierarchy,
			'relevant_tokens'          => $default_tokens,
			'custom_value_description' => _x( 'Group ID', 'LearnDash', 'uncanny-automator-pro' ),
		);

		return apply_filters( 'uap_option_all_ld_groups', $option );
	}

	/**
	 * Get children of a group
	 *
	 * @param $parent_id
	 * @param $depth
	 *
	 * @return void
	 */
	private function get_children( $parent_id, $depth = 1, $only_with_child = false ) {
		$args    = array(
			'post_type'      => 'groups',
			'posts_per_page' => 9999,
			//phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
			'post_parent'    => $parent_id,
		);
		$options = array();
		$results = get_posts( $args );
		if ( $results ) {
			foreach ( $results as $r ) {
				if ( true === $only_with_child && empty( learndash_get_group_children( $r->ID ) ) ) {
					continue;
				}
				$group_id              = $r->ID;
				$title                 = $r->post_title;
				$options[ $group_id ]  = $this->get_dashes( $depth ) . $title;
				$i                     = array_search( $parent_id, array_keys( $this->group_hierarchy ), true );
				$this->group_hierarchy = array_slice( $this->group_hierarchy, 0, $i + 1, true ) + $options + array_slice( $this->group_hierarchy, $i - 1, null, true );
				$this->get_children( $r->ID, ++ $depth, $only_with_child );
			}
		}
	}

	/**
	 * Get the dashes length based on the depth of a child
	 *
	 * @param $depth
	 *
	 * @return string
	 */
	private function get_dashes( $depth ) {
		$r = '';
		$i = 0;
		while ( $i < $depth ) {
			$r .= '&mdash;';
			$i ++;
		}

		return "$r ";
	}

	/**
	 * @return bool
	 */
	public static function is_group_hierarchy_enabled() {
		$settings = get_option( 'learndash_settings_groups_management_display' );
		if ( empty( $settings ) ) {
			return false;
		}
		if ( ! isset( $settings['group_hierarchical_enabled'] ) ) {
			return false;
		}
		if ( 'yes' !== $settings['group_hierarchical_enabled'] ) {
			return false;
		}

		return true;
	}

	/**
	 * @param $parent_id
	 * @param $depth
	 * @param $groups
	 *
	 * @return array|mixed
	 */
	public static function get_group_children_in_an_action( $parent_id, $depth = 1, $groups = array() ) {
		$args    = array(
			'post_type'      => 'groups',
			'posts_per_page' => 9999,
			//phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
			'post_parent'    => $parent_id,
		);
		$results = get_posts( $args );
		if ( $results ) {
			foreach ( $results as $r ) {
				$group_id    = $r->ID;
				$groups[]    = $group_id;
				$ld_children = learndash_get_group_children( $group_id );
				$groups      = array_merge( $groups, $ld_children );
				self::get_group_children_in_an_action( $r->ID, ++ $depth, $groups );
			}
		}
		if ( empty( $groups ) ) {
			return array();
		}
		$ld_children = learndash_get_group_children( $parent_id );

		return array_unique( array_merge( $groups, $ld_children ) );
	}

	/**
	 * @param $options
	 *
	 * @return mixed
	 */
	public function add_all_groups_option( $options ) {
		if ( empty( $options ) ) {
			return $options;
		}

		if ( 'LDREMOVEGROUP' !== $options['option_code'] && 'REMOVEGROUPLEADER_META' !== $options['option_code'] ) {
			return $options;
		}

		$all_groups         = array( '-1' => esc_attr__( 'All groups', 'uncanny-automator-pro' ) );
		$options['options'] = $all_groups + $options['options'];

		return $options;
	}

	/**
	 * @return void
	 */
	public function select_quiz_essays() {
		Automator()->utilities->ajax_auth_check();

		$fields = array();

		if ( ! automator_filter_has_var( 'value', INPUT_POST ) ) {
			echo wp_json_encode( $fields );
			die();
		}

		$fields[] = array(
			'value' => '-1',
			'text'  => __( 'Any essay', 'uncanny-automator' ),
		);

		$quiz_id = (int) automator_filter_input( 'value', INPUT_POST );

		if ( 0 < $quiz_id ) {
			$quiz_question_ids = learndash_get_quiz_questions( $quiz_id );
			if ( ! empty( $quiz_question_ids ) ) {
				foreach ( $quiz_question_ids as $question_post_id => $question_pro_id ) {
					$question_type = get_post_meta( $question_post_id, 'question_type', true );

					if ( 'essay' === (string) $question_type ) {
						$fields[] = array(
							'value' => $question_post_id,
							'text'  => get_the_title( $question_post_id ),
						);
					}
				}
			}
		}

		echo wp_json_encode( $fields );

		die();
	}

	/**
	 * Validate an array of Group post IDs.
	 *
	 * @param array $group_ids Array of Groups post IDs to check.
	 *
	 * @return array validated Group post IDS.
	 */
	public function learndash_validate_groups( $group_ids = array() ) {
		if ( ( is_array( $group_ids ) ) && ( ! empty( $group_ids ) ) ) {
			$groups_query_args = array(
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'post_type'              => learndash_get_post_type_slug( 'group' ),
				'fields'                 => 'ids',
				'orderby'                => 'title',
				'order'                  => 'ASC',
				'post__in'               => $group_ids,
				'posts_per_page'         => - 1,
				'suppress_filters'       => true,
			);

			$groups_query_args = apply_filters( 'uap_option_learndash_validate_groups', $groups_query_args );

			$groups_query = new \WP_Query( $groups_query_args );
			if ( ( is_a( $groups_query, '\WP_Query' ) ) && ( property_exists( $groups_query, 'posts' ) ) ) {
				return $groups_query->posts;
			}
		}

		return array();
	}

	/**
	 * @param $label
	 * @param $option_code           string
	 * @param $is_any                bool
	 * @param $is_all                bool
	 * @param $supports_custom_value bool
	 * @param $is_ajax               bool
	 * @param $fill_values_in        string|null
	 * @param $endpoint              string|null
	 *
	 * @return array|mixed|void
	 */
	public function all_ld_quizzes( $label = null, $option_code = 'LD_QUIZZES', $is_any = false, $is_all = false, $supports_custom_value = false ) {

		if ( ! $label ) {
			$label = esc_attr__( 'Quiz', 'uncanny-automator-pro' );
		}

		$any     = true === $is_any ? esc_attr__( 'Any quiz', 'uncanny-automator-pro' ) : false;
		$any     = true === $is_all ? esc_attr__( 'All quizzes', 'uncanny-automator-pro' ) : $any;
		$options = $this->get_all_quiz_options( $any );

		$option = array(
			'option_code'           => $option_code,
			'label'                 => $label,
			'input_type'            => 'select',
			'required'              => true,
			'options'               => $options,
			'is_ajax'               => true,
			'fill_values_in'        => 'LD_QUESTIONS',
			'endpoint'              => 'ld_select_quiz_questions',
			'options_show_id'       => true,
			'supports_custom_value' => $supports_custom_value,
			'relevant_tokens'       => array(
				$option_code          => esc_attr__( 'Quiz title', 'uncanny-automator-pro' ),
				$option_code . '_ID'  => esc_attr__( 'Quiz ID', 'uncanny-automator-pro' ),
				$option_code . '_URL' => esc_attr__( 'Quiz URL', 'uncanny-automator-pro' ),
			),
		);

		return apply_filters( 'uap_option_all_ld_quizzes', $option );
	}

	/**
	 * ld_select_quiz_questions
	 *
	 * @return void - Echoes JSON
	 */
	public function ld_select_quiz_questions() {
		Automator()->utilities->ajax_auth_check();
		$fields   = array();
		$fields[] = array(
			'value' => '-1',
			'text'  => __( 'Any question', 'uncanny-automator-pro' ),
		);

		$quiz_id = intval( filter_input( INPUT_POST, 'value' ) );

		if ( intval( '-1' ) !== $quiz_id ) {
			$quiz_question_ids = learndash_get_quiz_questions( $quiz_id );
			if ( ! empty( $quiz_question_ids ) ) {
				foreach ( $quiz_question_ids as $question_post_id => $question_pro_id ) {
					$fields[] = array(
						'value' => $question_post_id,
						'text'  => get_the_title( $question_post_id ),
					);
				}
			}
		}

		echo wp_json_encode( $fields );
		die();
	}

	/**
	 * Workaround to get all quizzes from a course or lesson/topic w/o Any Option
	 *
	 * @return void - Echoes JSON
	 */
	public function select_specific_quiz_from_course_lessontopic() {
		if ( ! isset( $_POST ) ) {
			echo wp_json_encode( array() );
			die();
		}
		$_POST['ld_specific_quiz'] = true;
		$this->select_quizzes_from_course_lessontopic();
	}

	/**
	 * Select all Quizzes from a Course or Lesson / Topic.
	 *
	 * @return void - Echoes JSON
	 */
	public function select_quizzes_from_course_lessontopic() {

		Automator()->utilities->ajax_auth_check();

		$any = __( 'Any quiz', 'uncanny-automator-pro' );

		$options = array(
			array(
				'value' => '-1',
				'text'  => $any,
			),
		);

		if ( ! isset( $_POST ) ) {
			echo wp_json_encode( $options );
			die();
		}

		$ld_step_id   = sanitize_text_field( $_POST['values']['LDSTEP'] );
		$ld_course_id = sanitize_text_field( $_POST['values']['LDCOURSE'] );

		// Remove Any Option if ld_specific_quiz is set.
		if ( automator_filter_has_var( 'ld_specific_quiz', INPUT_POST ) ) {
			$options = array();
		}

		// Check custom course value.
		if ( 'automator_custom_value' === $ld_course_id && '-1' !== absint( $ld_course_id ) ) {
			$ld_course_id = isset( $_POST['values']['LDCOURSE_custom'] ) ? absint( $_POST['values']['LDCOURSE_custom'] ) : 0;
		}

		// Check custom lesson value.
		if ( 'automator_custom_value' === $ld_step_id && '-1' !== absint( $ld_step_id ) ) {
			if ( 'automator_custom_value' === (string) $ld_step_id ) {
				$ld_step_id = isset( $_POST['values']['LDSTEP_custom'] ) ? absint( $_POST['values']['LDSTEP_custom'] ) : 0;
			} else {
				$ld_step_id = absint( $ld_step_id );
			}
		}

		$ld_course_id = $ld_course_id > 0 ? $ld_course_id : 0;
		$ld_step_id   = $ld_step_id > 0 ? $ld_step_id : 0;

		// Return all quizzes if no course or lesson is selected.
		if ( empty( $ld_course_id ) && empty( $ld_step_id ) ) {
			$options = $this->get_all_quiz_options( $any, 'text_value' );
			echo wp_json_encode( $options );
			die();
		}

		// Get All Quizzes from Lesson or Course.
		$quizzes = array();
		if ( ! empty( $ld_step_id ) ) {
			$quizzes = learndash_get_lesson_quiz_list( $ld_step_id, null, $ld_course_id );
		} elseif ( ! empty( $ld_course_id ) ) {
			$quizzes  = learndash_get_course_quiz_list( $ld_course_id );
			$step_ids = learndash_get_course_steps( $ld_course_id );
			if ( ! empty( $step_ids ) ) {
				foreach ( $step_ids as $step_id ) {
					$quizzes = array_merge( $quizzes, learndash_get_lesson_quiz_list( $step_id, null, $ld_course_id ) );
				}
			}
		}

		// Format options.
		if ( ! empty( $quizzes ) ) {
			foreach ( $quizzes as $quiz ) {
				$options[] = array(
					'value' => $quiz['post']->ID,
					'text'  => $quiz['post']->post_title,
				);
			}
		}

		echo wp_json_encode( $options );
		die();
	}

	/**
	 * Get all quiz options.
	 *
	 * @param mixed $any - Label to add an "Any" option.
	 * @param string $format
	 *
	 * @return array
	 */
	public function get_all_quiz_options( $any = false, $format = 'value_text' ) {

		$args = array(
			'post_type'      => 'sfwd-quiz',
			'posts_per_page' => 9999,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		);

		$quizzes = Automator()->helpers->recipe->options->wp_query( $args );
		$options = array();

		// Add any option.
		if ( $any ) {
			$label = is_string( $any ) ? $any : esc_attr__( 'Any quiz', 'uncanny-automator-pro' );
			if ( $format === 'text_value' ) {
				$options[] = array(
					'text'  => $label,
					'value' => '-1',
				);
			} else {
				$options['-1'] = $label;
			}
		}

		if ( ! empty( $quizzes ) ) {
			foreach ( $quizzes as $quiz_id => $quiz_title ) {
				if ( $format === 'text_value' ) {
					$options[] = array(
						'text'  => $quiz_title,
						'value' => $quiz_id,
					);
				} else {
					$options[ $quiz_id ] = $quiz_title;
				}
			}
		}

		return $options;
	}

	/**
	 * Select all Essay Questions .
	 *
	 * @return void - Echoes JSON
	 */
	public function select_essay_questions_from_course_lessontopic_quiz() {

		Automator()->utilities->ajax_auth_check();

		$any = __( 'Any question', 'uncanny-automator-pro' );

		$options = array(
			array(
				'value' => '-1',
				'text'  => $any,
			),
		);

		if ( ! isset( $_POST ) ) {
			echo wp_json_encode( $options );
			die();
		}

		$ld_course_id  = sanitize_text_field( $_POST['values']['LDCOURSE'] );
		$ld_lesson_id  = sanitize_text_field( $_POST['values']['LDSTEP'] );
		$ld_post_value = sanitize_text_field( automator_filter_input( 'value', INPUT_POST ) );
		$ld_quizz_id   = sanitize_text_field( $_POST['values']['LDQUIZ'] );

		// Check custom course value.
		if ( 'automator_custom_value' === $ld_course_id && '-1' !== absint( $ld_course_id ) ) {
			$ld_course_id = isset( $_POST['values']['LDCOURSE_custom'] ) ? absint( $_POST['values']['LDCOURSE_custom'] ) : 0;
		}

		// Check custom lesson value.
		if ( 'automator_custom_value' === $ld_lesson_id && '-1' !== absint( $ld_lesson_id ) ) {
			$ld_lesson_id = isset( $_POST['values']['LDSTEP_custom'] ) ? absint( $_POST['values']['LDSTEP_custom'] ) : 0;
		}

		// Check custom quiz value.
		if ( 'automator_custom_value' === $ld_quizz_id && '-1' !== absint( $ld_post_value ) ) {
			if ( 'automator_custom_value' === (string) $ld_quizz_id ) {
				$ld_quizz_id = isset( $_POST['values']['LDQUIZ_custom'] ) ? absint( $_POST['values']['LDQUIZ_custom'] ) : 0;
			} else {
				$ld_quizz_id = absint( $ld_quizz_id );
			}
		}

		$ld_course_id = $ld_course_id > 0 ? $ld_course_id : 0;
		$ld_lesson_id = $ld_lesson_id > 0 ? $ld_lesson_id : 0;
		$ld_quizz_id  = $ld_quizz_id > 0 ? $ld_quizz_id : 0;

		// Return all Essay Questions if no course, lesson or quizz is selected.
		if ( empty( $ld_course_id ) && empty( $ld_lesson_id ) && empty( $ld_quizz_id ) ) {
			$options = $this->essay_question_option_query( $any );
			echo wp_json_encode( $options );
			die();
		}

		// Build quiz IDs array.
		$quiz_ids = array();
		if ( ! empty( $ld_quizz_id ) ) {
			$quiz_ids[] = $ld_quizz_id;
		} elseif ( ! empty( $ld_lesson_id ) ) {
			$quizzes = learndash_get_lesson_quiz_list( $ld_lesson_id );
			if ( ! empty( $quizzes ) ) {
				foreach ( $quizzes as $quiz ) {
					$quiz_ids[] = $quiz['post']->ID;
				}
			}
		} elseif ( ! empty( $ld_course_id ) ) {
			$quizzes  = learndash_get_course_quiz_list( $ld_course_id );
			$step_ids = learndash_get_course_steps( $ld_course_id );
			if ( ! empty( $step_ids ) ) {
				foreach ( $step_ids as $step_id ) {
					$quizzes = array_merge( $quizzes, learndash_get_lesson_quiz_list( $step_id ) );
				}
			}
			if ( ! empty( $quizzes ) ) {
				foreach ( $quizzes as $quiz ) {
					$quiz_ids[] = $quiz['post']->ID;
				}
			}
		}

		// Query Questions.
		if ( ! empty( $quiz_ids ) ) {
			$options = $this->essay_question_option_query( $any, $quiz_ids );
		}

		echo wp_json_encode( $options );
		die();

	}

	/**
	 * @param string $any
	 * @param array $quiz_ids
	 *
	 * @return array
	 */
	public function essay_question_option_query( $any = '', $quiz_ids = array() ) {

		$options = array();

		if ( ! empty( $any ) ) {
			$options[] = array(
				'value' => '-1',
				'text'  => is_string( $any ) ? $any : __( 'Any question', 'uncanny-automator-pro' ),
			);
		}

		$meta_query = array();
		if ( ! empty( $quiz_ids ) ) {
			$meta_query['relationship'] = 'AND';
			$meta_query[]               = array(
				'key'     => 'quiz_id',
				'value'   => $quiz_ids,
				'compare' => 'IN',
			);
		}
		$meta_query[] = array(
			'key'     => 'question_type',
			'value'   => 'essay',
			'compare' => '=',
		);

		$args = array(
			'post_type'      => 'sfwd-question',
			'posts_per_page' => 9999,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post_status'    => 'publish',
			'meta_query'     => $meta_query,
		);

		$questions = Automator()->helpers->recipe->options->wp_query( $args );

		if ( ! empty( $questions ) ) {
			foreach ( $questions as $question_id => $question_title ) {
				$options[] = array(
					'value' => $question_id,
					'text'  => $question_title,
				);
			}
		}

		return $options;
	}

	/**
	 * Get current course progress for a user.
	 *
	 * @param int $user_id User ID.
	 * @param int $course_id Course ID.
	 *
	 * @return array
	 */
	public static function get_user_current_course_progress( $user_id, $course_id ) {

		$status   = learndash_course_status( $course_id, $user_id );
		$progress = array(
			'is_completed' => 'completed' === $status ? 1 : 0,
			'lessons'      => array(),
			'quiz'         => array(),
			'course'       => array(
				'lessons' => array(),
				'topics'  => array(),
			),
		);

		// Get all quizzes for the course.
		$quizzes = learndash_get_course_quiz_list( $course_id );
		if ( ! empty( $quizzes ) ) {
			foreach ( $quizzes as $key => $quiz ) {
				$quiz_id                      = $quiz['post']->ID;
				$quiz_completed               = learndash_is_quiz_complete( $user_id, $quiz_id, $course_id );
				$progress['quiz'][ $quiz_id ] = $quiz_completed ? 1 : 0;
			}
		}

		// Get all lessons for the course.
		$lessons = learndash_get_course_lessons_list( $course_id, $user_id, array( 'per_page' => 9999 ) );
		if ( ! empty( $lessons ) ) {
			foreach ( $lessons as $lesson ) {
				$lesson_id                                   = $lesson['post']->ID;
				$lesson_completed                            = 'completed' === $lesson['status'] ? 1 : 0;
				$progress['course']['lessons'][ $lesson_id ] = $lesson_completed;
				// Build Lesson Progress.
				$progress['lessons'][ $lesson_id ]                 = ! empty( $progress['lessons'][ $lesson_id ] ) ? $progress['lessons'][ $lesson_id ] : array();
				$progress['lessons'][ $lesson_id ]['is_completed'] = $lesson_completed;
				$progress['lessons'][ $lesson_id ]['topics']       = ! empty( $progress['lessons'][ $lesson_id ]['topics'] ) ? $progress['lessons'][ $lesson_id ]['topics'] : array();
				$progress['lessons'][ $lesson_id ]['quizzes']      = ! empty( $progress['lessons'][ $lesson_id ]['quizzes'] ) ? $progress['lessons'][ $lesson_id ]['quizzes'] : array();

				// Get all topics for the lesson.
				$topics = learndash_topic_dots( $lesson_id, false, 'array', $user_id, $course_id );
				if ( ! empty( $topics ) ) {
					$progress['course']['topics'][ $lesson_id ] = ! empty( $progress['course']['topics'][ $lesson_id ] ) ? $progress['course']['topics'][ $lesson_id ] : array();
					foreach ( $topics as $topic ) {
						$topic_id        = $topic->ID;
						$topic_completed = ! empty( $topic->completed ) ? 1 : 0;

						// Build Topic Progress.
						$progress['lessons'][ $lesson_id ]['topics'][ $topic_id ]                 = ! empty( $progress['lessons'][ $lesson_id ]['topics'][ $topic_id ] ) ? $progress['lessons'][ $lesson_id ]['topics'][ $topic_id ] : array();
						$progress['lessons'][ $lesson_id ]['topics'][ $topic_id ]['is_completed'] = $topic_completed;
						$progress['course']['topics'][ $lesson_id ][ $topic_id ]                  = $topic_completed;
						$progress['lessons'][ $lesson_id ]['topics'][ $topic_id ]['quizzes']      = ! empty( $progress['lessons'][ $lesson_id ]['topics'][ $topic_id ]['quizzes'] ) ? $progress['lessons'][ $lesson_id ]['topics'][ $topic_id ]['quizzes'] : array();

						// Get all quizzes for the topic.
						$topic_quizzes = learndash_get_lesson_quiz_list( $topic_id, null, $course_id );
						if ( ! empty( $topic_quizzes ) ) {
							foreach ( $topic_quizzes as $key => $quiz ) {
								$quiz_id                                                                         = $quiz['post']->ID;
								$quiz_completed                                                                  = learndash_is_quiz_complete( $user_id, $quiz_id, $course_id ) ? 1 : 0;
								$progress['lessons'][ $lesson_id ]['topics'][ $topic_id ]['quizzes'][ $quiz_id ] = $quiz_completed;
								$progress['quiz'][ $quiz_id ]                                                    = $quiz_completed;
							}
						}
					}
				}

				// Lesson Quizzes.
				$lesson_quizzes = learndash_get_lesson_quiz_list( $lesson_id, $user_id, $course_id );
				if ( ! empty( $lesson_quizzes ) ) {
					foreach ( $lesson_quizzes as $key => $quiz ) {
						$quiz_id                                                  = $quiz['post']->ID;
						$quiz_completed                                           = learndash_is_quiz_complete( $user_id, $quiz_id, $course_id ) ? 1 : 0;
						$progress['lessons'][ $lesson_id ]['quizzes'][ $quiz_id ] = $quiz_completed;
						$progress['quiz'][ $quiz_id ]                             = $quiz_completed;
					}
				}
			}
		}

		return $progress;
	}

	/**
	 * Get Quiz Lesson and Topic IDs.
	 *
	 * @param array $lessons
	 * @param int $quiz_id
	 *
	 * @return array
	 */
	public static function quiz_step_ids( $lessons, $quiz_id ) {
		if ( empty( $lessons ) ) {
			return array(
				'lesson' => 0,
				'topic'  => 0,
			);
		}

		$quiz_lesson_id = 0;
		$quiz_topic_id  = 0;
		foreach ( $lessons as $lesson_id => $lesson ) {
			if ( ! empty( $lesson['quizzes'] ) ) {
				if ( array_key_exists( $quiz_id, $lesson['quizzes'] ) ) {
					$quiz_lesson_id = $lesson_id;
				}
			}
			if ( ! empty( $lesson['topics'] ) ) {
				foreach ( $lesson['topics'] as $topic_id => $topic ) {
					if ( ! empty( $topic['quizzes'] ) ) {
						if ( array_key_exists( $quiz_id, $topic['quizzes'] ) ) {
							$quiz_lesson_id = $lesson_id;
							$quiz_topic_id  = $topic_id;
						}
					}
				}
			}
		}

		return array(
			'lesson' => $quiz_lesson_id,
			'topic'  => $quiz_topic_id,
		);
	}

	/**
	 * Returns the file attachment field description.
	 *
	 * @return string
	 */
	public static function get_file_attachment_field_description() {

		$attachment_description = sprintf(
			__( 'Please ensure the file has a valid extension (e.g., .pdf, .png, .doc) and does not exceed the file size limit.', 'uncanny-automator' ),
		);

		// File description.
		if ( class_exists( '\Uncanny_Automator\Services\Email\Attachment\Validator' ) ) {
			$attachment_description = sprintf(
				__( 'Please ensure the file has a valid extension (e.g., .pdf, .png, .doc) and does not exceed the file size limit of %d MB.', 'uncanny-automator' ),
				\Uncanny_Automator\Services\Email\Attachment\Validator::to_megabytes(
					\Uncanny_Automator\Services\Email\Attachment\Validator::get_file_size_limit()
				)
			);
		}

		return $attachment_description;

	}

}
