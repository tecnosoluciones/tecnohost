<?php
namespace WP_Media_Folder\Aws\Signature;

use WP_Media_Folder\Aws\Credentials\CredentialsInterface;
use WP_Media_Folder\Psr\Http\Message\RequestInterface;

/**
 * Provides anonymous client access (does not sign requests).
 */
class AnonymousSignature implements SignatureInterface
{
    public function signRequest(
        RequestInterface $request,
        CredentialsInterface $credentials
    ) {
        return $request;
    }

    public function presign(
        RequestInterface $request,
        CredentialsInterface $credentials,
        $expires
    ) {
        return $request;
    }
}
