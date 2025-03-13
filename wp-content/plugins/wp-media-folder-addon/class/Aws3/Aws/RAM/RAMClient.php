<?php
namespace WP_Media_Folder\Aws\RAM;

use WP_Media_Folder\Aws\AwsClient;

/**
 * This client is used to interact with the **AWS Resource Access Manager** service.
 * @method \WP_Media_Folder\Aws\Result acceptResourceShareInvitation(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise acceptResourceShareInvitationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result associateResourceShare(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise associateResourceShareAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result createResourceShare(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise createResourceShareAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result deleteResourceShare(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise deleteResourceShareAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result disassociateResourceShare(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise disassociateResourceShareAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result enableSharingWithAwsOrganization(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise enableSharingWithAwsOrganizationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getResourcePolicies(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getResourcePoliciesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getResourceShareAssociations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getResourceShareAssociationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getResourceShareInvitations(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getResourceShareInvitationsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result getResourceShares(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise getResourceSharesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listPrincipals(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listPrincipalsAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result listResources(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise listResourcesAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result rejectResourceShareInvitation(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise rejectResourceShareInvitationAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result tagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result untagResource(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \WP_Media_Folder\Aws\Result updateResourceShare(array $args = [])
 * @method \WP_Media_Folder\GuzzleHttp\Promise\Promise updateResourceShareAsync(array $args = [])
 */
class RAMClient extends AwsClient {}
