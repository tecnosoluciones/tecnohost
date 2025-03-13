<?php
namespace WP_Media_Folder\Aws\ACMPCA;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Certificate Manager Private Certificate Authority** service.
 * @method \WP_Media_Folder\Aws\Result createCertificateAuthority(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createCertificateAuthorityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createCertificateAuthorityAuditReport(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createCertificateAuthorityAuditReportAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteCertificateAuthority(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteCertificateAuthorityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeCertificateAuthority(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeCertificateAuthorityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeCertificateAuthorityAuditReport(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeCertificateAuthorityAuditReportAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCertificateAuthorityCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCertificateAuthorityCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getCertificateAuthorityCsr(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getCertificateAuthorityCsrAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result importCertificateAuthorityCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise importCertificateAuthorityCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result issueCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise issueCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listCertificateAuthorities(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listCertificateAuthoritiesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTags(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result restoreCertificateAuthority(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise restoreCertificateAuthorityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result revokeCertificate(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise revokeCertificateAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tagCertificateAuthority(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagCertificateAuthorityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untagCertificateAuthority(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagCertificateAuthorityAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateCertificateAuthority(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateCertificateAuthorityAsync(array $args = [])
 */
class ACMPCAClient extends AwsClient {}
