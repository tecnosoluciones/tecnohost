<?php
namespace WP_Media_Folder\Aws\Acm;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Certificate Manager** service.
 *
 * @method \WP_Media_Folder\Aws\Result addTagsToCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise addTagsToCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result exportCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise exportCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result importCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise importCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listCertificates(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listCertificatesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTagsForCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsForCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result removeTagsFromCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise removeTagsFromCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result requestCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise requestCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result resendValidationEmail(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise resendValidationEmailAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateCertificateOptions(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateCertificateOptionsAsync(array $args = [])
 */
class AcmClient extends AwsClient {}
