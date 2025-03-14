<?php
/**
 * LearnDash REST API V1 Questions Post Controller.
 *
 * @since 2.5.8
 * @package LearnDash\REST\V1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ( ! class_exists( 'LD_REST_Questions_Controller_V1' ) ) && ( class_exists( 'WP_REST_Controller' ) ) ) {
	/**
	 * Class LearnDash REST API V1 Questions Post Controller.
	 *
	 * @since 2.5.8
	 */
	class LD_REST_Questions_Controller_V1 extends WP_REST_Controller /* phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound */ {
		/**
		 * Registers the routes for the objects of the controller.
		 *
		 * @since 2.5.8
		 *
		 * @see register_rest_route() in WordPress core.
		 */
		public function register_routes() {
			$version   = '1';
			$namespace = LEARNDASH_REST_API_NAMESPACE . '/v' . $version;
			$base      = 'sfwd-questions';

			register_rest_route(
				$namespace,
				'/' . $base . '/(?P<id>[\d]+)',
				array(
					array(
						'methods'             => WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_item' ),
						'permission_callback' => array( $this, 'permissions_check' ),
						'args'                => array(
							'id' => array(
								'description'       => sprintf(
									// translators: question.
									esc_html_x( 'The %s ID', 'placeholder: question', 'learndash' ),
									learndash_get_custom_label_lower( 'question' )
								),
								'required'          => true,
								'validate_callback' => function ( $param, $request, $key ) {
									return is_numeric( $param );
								},
								'sanitize_callback' => 'absint',
							),
						),
					),
					array(
						'methods'             => WP_REST_Server::EDITABLE,
						'callback'            => array( $this, 'update_item' ),
						'permission_callback' => array( $this, 'permissions_check' ),
						'args'                => array(
							'id' => array(
								'description'       => sprintf(
									// translators: question.
									esc_html_x( 'The %s ID', 'placeholder: question', 'learndash' ),
									learndash_get_custom_label_lower( 'question' )
								),
								'required'          => true,
								'validate_callback' => function ( $param, $request, $key ) {
									return is_numeric( $param );
								},
								'sanitize_callback' => 'absint',
							),
						),
					),
					array(
						'methods'             => WP_REST_Server::DELETABLE,
						'callback'            => array( $this, 'delete_item' ),
						'permission_callback' => array( $this, 'permissions_check' ),
						'args'                => array(
							'id' => array(
								'description'       => sprintf(
									// translators: question.
									esc_html_x( 'The %s ID', 'placeholder: question', 'learndash' ),
									learndash_get_custom_label_lower( 'question' )
								),
								'required'          => true,
								'validate_callback' => function ( $param, $request, $key ) {
									return is_numeric( $param );
								},
								'sanitize_callback' => 'absint',
							),
						),
					),
					'schema' => array( $this, 'get_schema' ),
				)
			);
		}

		/**
		 * Check if a given request has access manage the item.
		 *
		 * @since 2.5.8
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 *
		 * @return WP_Error|bool
		 */
		public function permissions_check( $request ) {
			$params = $request->get_params();
			if ( ( isset( $params['id'] ) ) && ( ! empty( $params['id'] ) ) ) {
				$question_id = $params['id'];

				return current_user_can( 'edit_post', $question_id );
			}
			return false;
		}

		/**
		 * Get a question items
		 *
		 * @since 2.5.8
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 *
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_items( $request ) {
			$data = array();
			return new WP_REST_Response( $data, 200 );
		}

		/**
		 * Get a question item
		 *
		 * @since 2.5.8
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 *
		 * @return WP_Error|WP_REST_Response
		 */
		public function get_item( $request ) {
			$params      = $request->get_params();
			$question_id = $params['id'];
			$data        = $this->get_question_data( $question_id );

			return new WP_REST_Response( $data, 200 );
		}

		/**
		 * Delete one item from the collection
		 *
		 * @since 2.5.8
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 *
		 * @return WP_Error|WP_REST_Request
		 */
		public function delete_item( $request ) {
			$params          = $request->get_params();
			$question_id     = $params['id'];
			$question_pro_id = (int) get_post_meta( $question_id, 'question_pro_id', true );
			$question_mapper = new \WpProQuiz_Model_QuestionMapper();

			if ( false !== $question_mapper->delete( $question_pro_id ) &&
				false !== wp_delete_post( $params['id'], false ) ) {
				return new WP_REST_Response( true, 200 );
			}

			return new WP_Error(
				'cant-delete',
				sprintf(
				// translators: placeholder: Question label.
					esc_html_x( 'Could not delete the %s.', 'placeholder: Question label', 'learndash' ),
					\LearnDash_Custom_Label::get_label( 'question' )
				),
				array( 'status' => 500 )
			);
		}

		/**
		 * Update one item from the collection
		 *
		 * TODO: Add test.
		 *
		 * @since 2.5.8
		 *
		 * @param WP_REST_Request $request Full data about the request.
		 *
		 * @return WP_Error|WP_REST_Request
		 */
		public function update_item( $request ) {
			global $learndash_question_types;

			$params          = $request->get_params();
			$question_id     = $params['id'];
			$question_pro_id = (int) get_post_meta( $question_id, 'question_pro_id', true );
			$question_mapper = new \WpProQuiz_Model_QuestionMapper();

			$question_model = $question_mapper->fetch( $question_pro_id );

			// Prepare answer data.
			if ( isset( $params['_answerData'] ) && is_string( $params['_answerData'] ) ) {
				$params['_answerData'] = json_decode( $params['_answerData'], true );
			}

			// Decide if we need points recalculation.

			if (
				isset( $params['_answerData'] )
				|| isset( $params['_points'] )
				|| isset( $params['_answerPointsActivated'] )
			) {
				$validated_post = WpProQuiz_Controller_Question::clearPost(
					$this->map_parameters_to_validation( $question_model, $params )
				);

				// Apply the validated data.

				$params['_points']                = $validated_post['points'];
				$params['_answerPointsActivated'] = $validated_post['answerPointsActivated'];
				$params['_answerData']            = $validated_post['answerData'];
			}

			// Also save points at question's post meta data.
			if ( isset( $params['_points'] ) ) {
				update_post_meta( $question_id, 'question_points', $params['_points'] );
			}

			if ( ( isset( $params['_answerType'] ) ) && ( ! empty( $params['_answerType'] ) ) ) {
				if ( isset( $learndash_question_types[ $params['_answerType'] ] ) ) {
					update_post_meta( $question_id, 'question_type', $params['_answerType'] );
				}
			}

			// Update question's post content.
			if ( isset( $params['_question'] ) ) {
				wp_update_post(
					array(
						'ID'           => $question_id,
						'post_content' => wp_slash( $params['_question'] ),
					)
				);
			}

			// Update the question object with new data.
			$question_model->set_array_to_object( $params );

			// Save the new data to database.
			$question_mapper->save( $question_model );

			return new WP_REST_Response( $this->get_question_data( $question_id ), 200 );
		}

		/**
		 * Maps the API parameters to the format that can be used for validation.
		 * If a needed parameter is not received, the function will use the current question data.
		 *
		 * @since 4.14.0
		 *
		 * @param WpProQuiz_Model_Question $question   The question model.
		 * @param array<mixed>             $parameters The API parameters.
		 *
		 * @return array<mixed> The mapped parameters.
		 */
		private function map_parameters_to_validation( WpProQuiz_Model_Question $question, array $parameters ) {
			$mapped_parameters = [
				'answerPointsActivated' => isset( $parameters['_answerPointsActivated'] ) ? $parameters['_answerPointsActivated'] : $question->isAnswerPointsActivated(),
				/**
				 * Note that we always use the current answer type. Users can change it, but it should be considered only in the next request
				 * as it may affect the answer data and we don't recalculate points question if the answer type is changed.
				 */
				'answerType'            => $question->getAnswerType(),
				'points'                => isset( $parameters['_points'] ) ? $parameters['_points'] : $question->getPoints(),
				'answerData'            => [],
			];

			$is_answer_data_param_valid = isset( $parameters['_answerData'] )
				&& is_array( $parameters['_answerData'] )
				&& count( $parameters['_answerData'] ) > 0;

			$question_answer_data = $question->getAnswerData();
			$first_answer_data    = is_array( $question_answer_data ) && count( $question_answer_data ) > 0
				? $question_answer_data[0]
				: null;

			switch ( $mapped_parameters['answerType'] ) {
				case 'cloze_answer':
					$mapped_parameters['answerData']['cloze'] = [];

					$mapped_parameters['answerData']['cloze']['answer'] = $is_answer_data_param_valid
						? $parameters['_answerData'][0]['_answer']
						: ( $first_answer_data ? $first_answer_data->getAnswer() : '' );

					break;

				case 'assessment_answer':
					$mapped_parameters['answerData']['assessment'] = [];

					$mapped_parameters['answerData']['assessment']['answer'] = $is_answer_data_param_valid
						? $parameters['_answerData'][0]['_answer']
						: ( $first_answer_data ? $first_answer_data->getAnswer() : '' );

					break;

				case 'essay':
					$mapped_parameters['answerData']['essay'] = [];

					$mapped_parameters['answerData']['essay']['type'] = $is_answer_data_param_valid
						? $parameters['_answerData'][0]['_gradedType']
						: ( $first_answer_data ? $first_answer_data->getGradedType() : '' );

					$mapped_parameters['answerData']['essay']['progression'] = $is_answer_data_param_valid
						? $parameters['_answerData'][0]['_gradingProgression']
						: ( $first_answer_data ? $first_answer_data->getGradingProgression() : '' );

					break;

				case 'free_answer':
					$mapped_parameters['answerData'][] = [];

					$mapped_parameters['answerData'][0]['answer'] = $is_answer_data_param_valid
						? $parameters['_answerData'][0]['_answer']
						: ( $first_answer_data ? $first_answer_data->getAnswer() : '' );

					break;

				case 'single':
					// Fill up the modus 2 data.

					$mapped_parameters['answerPointsDiffModusActivated'] = isset( $parameters['_answerPointsDiffModusActivated'] )
						? $parameters['_answerPointsDiffModusActivated']
						: $question->isAnswerPointsDiffModusActivated();

					$mapped_parameters['disableCorrect'] = isset( $parameters['_disableCorrect'] )
						? $parameters['_disableCorrect']
						: $question->isDisableCorrect();

					// Other types, including single.

				default:
					if ( $is_answer_data_param_valid ) {
						foreach ( $parameters['_answerData'] as $answer ) {
							$answer = (array) $answer;

							$answer_data = [];
							foreach ( $answer as $key => $value ) {
								// Remove the underscore from the key for certain keys to match the existing array keys format.
								if ( in_array( $key, [ '_answer', '_points', '_sortString', '_correct' ], true ) ) {
									$key = str_replace( '_', '', $key );
								}

								$answer_data[ $key ] = $value;
							}

							$mapped_parameters['answerData'][] = $answer_data;
						}
					} else {
						foreach ( (array) $question_answer_data as $answer ) {
							if ( ! $answer instanceof WpProQuiz_Model_AnswerTypes ) {
								continue;
							}

							$mapped_parameters['answerData'][] = [
								'answer'              => $answer->getAnswer(),
								'points'              => $answer->getPoints(),
								'sortString'          => $answer->getSortString(),
								'correct'             => $answer->isCorrect(),
								'_html'               => $answer->isHtml(),
								'_sortStringHtml'     => $answer->isSortStringHtml(),
								'_graded'             => $answer->isGraded(),
								'_gradingProgression' => $answer->getGradingProgression(),
								'_gradedType'         => $answer->getGradedType(),
							];
						}
					}

					break;
			}

			return $mapped_parameters;
		}

		/**
		 * Get question data.
		 *
		 * @since 2.5.8
		 *
		 * @param int $question_id The question ID.
		 *
		 * @return object
		 */
		public function get_question_data( $question_id ) {
			// Get Answers from Question.
			$question_pro_id = (int) get_post_meta( $question_id, 'question_pro_id', true );
			$question_mapper = new \WpProQuiz_Model_QuestionMapper();

			if ( ! empty( $question_pro_id ) ) {
				$question_model = $question_mapper->fetch( $question_pro_id );
			} else {
				$question_model = $question_mapper->fetch( null );
			}

			// Get data as array.
			$question_data = $question_model->get_object_as_array();

			$answer_data = array();

			// Get answer data.

			if ( $question_data['_answerData'] ) {
				foreach ( $question_data['_answerData'] as $answer ) {
					$answer_data[] = $answer->get_object_as_array();
				}
			}

			unset( $question_data['_answerData'] );

			$question_data['_answerData'] = $answer_data;

			// Generate output object.
			$data = array_merge(
				$question_data,
				array(
					'question_id'         => $question_id,
					'question_post_title' => get_the_title( $question_id ),
				)
			);

			return $data;
		}

		/**
		 * Gets the sfwd-question schema.
		 *
		 * @since 2.5.8
		 *
		 * @return array
		 */
		public function get_schema() {
			$schema = array(
				'$schema'    => 'http://json-schema.org/draft-04/schema#',
				'title'      => 'question',
				'type'       => 'object',
				'properties' => array(
					'_id'                             => array(
						'description' => __( 'Unique identifier for the object.', 'learndash' ),
						'type'        => 'integer',
						'context'     => array( 'view', 'edit', 'embed' ),
						'readonly'    => true,
					),
					'_quizId'                         => array(
						// translators: quiz, question.
						'description' => sprintf( esc_html_x( 'The ID of the %1$s associated with the %2$s.', 'placeholder: quiz, question', 'learndash' ), learndash_get_custom_label_lower( 'quiz' ), learndash_get_custom_label_lower( 'question' ) ),
						'type'        => 'integer',
						'context'     => array( 'view', 'edit', 'embed' ),
						'readonly'    => true,
					),
					'_sort'                           => array(
						// translators: question, quiz.
						'description' => sprintf( esc_html_x( 'The order of the %1$s in the %2$s', 'placeholder: question, quiz', 'learndash' ), learndash_get_custom_label_lower( 'question' ), learndash_get_custom_label_lower( 'quiz' ) ),
						'type'        => 'integer',
						'context'     => array( 'view', 'edit' ),
					),
					'_title'                          => array(
						'description' => __( 'The title for the object.', 'learndash' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
					'_question'                       => array(
						// translators: question.
						'description' => sprintf( esc_html_x( 'The %s content', 'placeholder: question', 'learndash' ), learndash_get_custom_label_lower( 'question' ) ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
					'_correctMsg'                     => array(
						'description' => __( 'The message to show when the answer is correct.', 'learndash' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
					'_incorrectMsg'                   => array(
						'description' => __( 'The message to show when the answer is incorrect.', 'learndash' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
					'_correctSameText'                => array(
						'description' => __( 'Whether the incorrect and correct message are same.', 'learndash' ),
						'type'        => 'boolean',
						'context'     => array( 'view', 'edit' ),
					),
					'_tipEnabled'                     => array(
						'description' => __( 'The message to show when the answer is incorrect.', 'learndash' ),
						'type'        => 'boolean',
						'context'     => array( 'view', 'edit' ),
					),
					'_tipMsg'                         => array(
						'description' => __( 'The solution hint for the question.', 'learndash' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
					'_points'                         => array(
						// translators: placeholder: question.
						'description' => sprintf( esc_html_x( 'The total number of points that can be obtained from the %s', 'placeholder: question', 'learndash' ), learndash_get_custom_label_lower( 'question' ) ),
						'type'        => 'boolean',
						'context'     => array( 'view', 'edit' ),
					),
					'_showPointsInBox'                => array(
						'description' => __( 'Whether to show points in box.', 'learndash' ),
						'type'        => 'boolean',
						'context'     => array( 'view', 'edit' ),
					),
					'_answerPointsActivated'          => array(
						'description' => __( 'Whether the individual points for the answers are activated.', 'learndash' ),
						'type'        => 'boolean',
						'context'     => array( 'view', 'edit' ),
					),
					'_answerType'                     => array(
						'description' => __( 'The type of the answer.', 'learndash' ),
						'type'        => 'string',
						'enum'        => array(
							'single',
							'multiple',
							'free_answer',
							'sort_answer',
							'matrix_sort_answer',
							'cloze_answer',
							'essay',
							'assessment_answer',
						),
						'context'     => array( 'view', 'edit' ),
					),
					'_answerPointsDiffModusActivated' => array(
						'description' => __( 'Whether the different points modus is activated.', 'learndash' ),
						'type'        => array( 'boolean', 'null' ),
						'context'     => array( 'view', 'edit' ),
					),
					'_disableCorrect'                 => array(
						'description' => __( 'Whether to distinguish between correct and incorrect when the different point modus is activated.', 'learndash' ),
						'type'        => 'boolean',
						'context'     => array( 'view', 'edit' ),
					),
					'_matrixSortAnswerCriteriaWidth'  => array(
						'description' => __( 'The percentage width of the criteria table column for matrix sort answer.', 'learndash' ),
						'type'        => 'integer',
						'context'     => array( 'view', 'edit' ),
					),
					'_answerData'                     => array(
						'description' => __( 'An array of answer data objects', 'learndash' ),
						'type'        => 'array',
						'context'     => array( 'view', 'edit' ),
						'properties'  => array(
							'_answer'             => array(
								'description' => __( 'The answer text.', 'learndash' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'_html'               => array(
								'description' => __( 'Whether the HTML is allowed in the answer or not', 'learndash' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'_points'             => array(
								'description' => __( 'The number of points that can be obtained from the answer.', 'learndash' ),
								'type'        => 'integer',
								'context'     => array( 'view', 'edit' ),
							),
							'_correct'            => array(
								'description' => __( 'Whether the answer is correct.', 'learndash' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'_sortString'         => array(
								'description' => __( 'Sort string.', 'learndash' ),
								'type'        => 'string',
								'context'     => array( 'view', 'edit' ),
							),
							'_sortStringHtml'     => array(
								'description' => __( 'Whether to allow HTML in sort string.', 'learndash' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'_graded'             => array(
								'description' => __( 'Whether the answer can be graded or not.', 'learndash' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
							),
							'_gradingProgression' => array(
								// translators: question, quiz.
								'description' => sprintf( esc_html_x( 'Determines how should the answer to this %1$s be marked and graded upon %2$s submission.', 'placeholder: question, quiz', 'learndash' ), learndash_get_custom_label_lower( 'question' ), learndash_get_custom_label_lower( 'quiz' ) ),
								'type'        => 'text',
								'context'     => array( 'view', 'edit' ),
								'enum'        => array( 'not-graded-none', 'not-graded-full', 'graded-full' ),
							),
							'_gradedType'         => array(
								'description' => __( 'Determines how a user can submit answer.', 'learndash' ),
								'type'        => 'boolean',
								'context'     => array( 'view', 'edit' ),
								'enum'        => array( 'text', 'upload' ),
							),
						),
					),
					'question_id'                     => array(
						// translators: question.
						'description' => sprintf( esc_html_x( 'The %s post ID.', 'placeholder: question', 'learndash' ), learndash_get_custom_label_lower( 'question' ) ),
						'type'        => 'integer',
						'context'     => array( 'view', 'edit', 'embed' ),
						'readonly'    => true,
					),
					'question_post_title'             => array(
						// translators: question.
						'description' => sprintf( esc_html_x( 'The %s post title.', 'placeholder: question', 'learndash' ), learndash_get_custom_label_lower( 'question' ) ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
				),
			);

			return $schema;
		}
	}
}
