<?php
namespace WP_Media_Folder\Aws\FSx;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon FSx** service.
 * @method \WP_Media_Folder\Aws\Result createBackup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createBackupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createFileSystem(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createFileSystemAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createFileSystemFromBackup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createFileSystemFromBackupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteBackup(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteBackupAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteFileSystem(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteFileSystemAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeBackups(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeBackupsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result describeFileSystems(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise describeFileSystemsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listTagsForResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateFileSystem(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateFileSystemAsync(array $args = [])
 */
class FSxClient extends AwsClient {}
