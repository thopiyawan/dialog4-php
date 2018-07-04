<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/kms/v1/resources.proto

namespace Google\Cloud\Kms\V1;

/**
 * [CryptoKeyPurpose][google.cloud.kms.v1.CryptoKey.CryptoKeyPurpose] describes the capabilities of a [CryptoKey][google.cloud.kms.v1.CryptoKey]. Two
 * keys with the same purpose may use different underlying algorithms, but
 * must support the same set of operations.
 *
 * Protobuf enum <code>Google\Cloud\Kms\V1\CryptoKey\CryptoKeyPurpose</code>
 */
class CryptoKey_CryptoKeyPurpose
{
    /**
     * Not specified.
     *
     * Generated from protobuf enum <code>CRYPTO_KEY_PURPOSE_UNSPECIFIED = 0;</code>
     */
    const CRYPTO_KEY_PURPOSE_UNSPECIFIED = 0;
    /**
     * [CryptoKeys][google.cloud.kms.v1.CryptoKey] with this purpose may be used with
     * [Encrypt][google.cloud.kms.v1.KeyManagementService.Encrypt] and
     * [Decrypt][google.cloud.kms.v1.KeyManagementService.Decrypt].
     *
     * Generated from protobuf enum <code>ENCRYPT_DECRYPT = 1;</code>
     */
    const ENCRYPT_DECRYPT = 1;
}

