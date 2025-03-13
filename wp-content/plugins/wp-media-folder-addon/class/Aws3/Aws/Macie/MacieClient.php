<?php
namespace WP_Media_Folder\Aws\Macie;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Macie** service.
 * @method \WP_Media_Folder\Aws\Result associateMemberAccount(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise associateMemberAccountAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result associateS3Resources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise associateS3ResourcesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result disassociateMemberAccount(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise disassociateMemberAccountAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result disassociateS3Resources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise disassociateS3ResourcesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listMemberAccounts(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listMemberAccountsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listS3Resources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listS3ResourcesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateS3Resources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateS3ResourcesAsync(array $args = [])
 */
class MacieClient extends AwsClient {}
