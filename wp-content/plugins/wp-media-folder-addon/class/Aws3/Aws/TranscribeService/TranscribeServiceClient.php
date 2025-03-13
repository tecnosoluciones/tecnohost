<?php
namespace WP_Media_Folder\Aws\TranscribeService;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Transcribe Service** service.
 * @method \WP_Media_Folder\Aws\Result createVocabulary(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createVocabularyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteTranscriptionJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteTranscriptionJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteVocabulary(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteVocabularyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getTranscriptionJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getTranscriptionJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getVocabulary(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getVocabularyAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTranscriptionJobs(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTranscriptionJobsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listVocabularies(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listVocabulariesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result startTranscriptionJob(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise startTranscriptionJobAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateVocabulary(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateVocabularyAsync(array $args = [])
 */
class TranscribeServiceClient extends AwsClient {}
