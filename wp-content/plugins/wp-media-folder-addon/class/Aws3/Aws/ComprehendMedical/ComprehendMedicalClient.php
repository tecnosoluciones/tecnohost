<?php
namespace WP_Media_Folder\Aws\ComprehendMedical;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Comprehend Medical** service.
 * @method \WP_Media_Folder\Aws\Result detectEntities(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise detectEntitiesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result detectPHI(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise detectPHIAsync(array $args = [])
 */
class ComprehendMedicalClient extends AwsClient {}
