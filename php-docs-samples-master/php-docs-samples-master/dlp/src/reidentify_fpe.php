<?php

/**
 * Copyright 2018 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Google\Cloud\Samples\Dlp;

# [START dlp_reidentify_fpe]
use Google\Cloud\Dlp\V2\CryptoReplaceFfxFpeConfig;
use Google\Cloud\Dlp\V2\CryptoReplaceFfxFpeConfig_FfxCommonNativeAlphabet;
use Google\Cloud\Dlp\V2\CryptoKey;
use Google\Cloud\Dlp\V2\DlpServiceClient;
use Google\Cloud\Dlp\V2\PrimitiveTransformation;
use Google\Cloud\Dlp\V2\KmsWrappedCryptoKey;
use Google\Cloud\Dlp\V2\InfoType;
use Google\Cloud\Dlp\V2\InspectConfig;
use Google\Cloud\Dlp\V2\InfoTypeTransformations_InfoTypeTransformation;
use Google\Cloud\Dlp\V2\InfoTypeTransformations;
use Google\Cloud\Dlp\V2\ContentItem;
use Google\Cloud\Dlp\V2\CustomInfoType;
use Google\Cloud\Dlp\V2\DeidentifyConfig;
use Google\Cloud\Dlp\V2\CustomInfoType_SurrogateType;

/**
 * Reidentify a deidentified string using Format-Preserving Encryption (FPE).
 *
 * @param string $callingProjectId The GCP Project ID to run the API call under
 * @param string $string The string to deidentify
 * @param keyName $keyName The name of the Cloud KMS key used to encrypt ('wrap') the AES-256 key
 * @param string $wrappedKey The AES-256 key to use, encrypted ('wrapped') with the KMS key
 *        defined by $keyName.
 * @param string $surrogateTypeName Optional surrogate custom info type to enable
 *        reidentification. Can be essentially any arbitrary string that doesn't
 *        appear in your dataset'
 */
function reidentify_fpe(
    $callingProjectId,
    $string,
    $keyName,
    $wrappedKey,
    $surrogateTypeName = ''
) {
    // Instantiate a client.
    $dlp = new DlpServiceClient();

    // The infoTypes of information to mask
    $ssnInfoType = (new InfoType())
        ->setName('US_SOCIAL_SECURITY_NUMBER');
    $infoTypes = [$ssnInfoType];

    // The set of characters to replace sensitive ones with
    // For more information, see https://cloud.google.com/dlp/docs/reference/rest/v2/organizations.deidentifyTemplates#ffxcommonnativealphabet
    $commonAlphabet = CryptoReplaceFfxFpeConfig_FfxCommonNativeAlphabet::NUMERIC;

    // Create the wrapped crypto key configuration object
    $kmsWrappedCryptoKey = (new KmsWrappedCryptoKey())
        ->setWrappedKey(base64_decode($wrappedKey))
        ->setCryptoKeyName($keyName);

    // Create the crypto key configuration object
    $cryptoKey = (new CryptoKey())
        ->setKmsWrapped($kmsWrappedCryptoKey);

    // Create the surrogate type object
    $surrogateType = (new InfoType())
        ->setName($surrogateTypeName);

    $customInfoType = (new CustomInfoType())
        ->setInfoType($surrogateType)
        ->setSurrogateType(new CustomInfoType_SurrogateType());

    // Create the crypto FFX FPE configuration object
    $cryptoReplaceFfxFpeConfig = (new CryptoReplaceFfxFpeConfig())
        ->setCryptoKey($cryptoKey)
        ->setCommonAlphabet($commonAlphabet)
        ->setSurrogateInfoType($surrogateType);

    // Create the information transform configuration objects
    $primitiveTransformation = (new PrimitiveTransformation())
        ->setCryptoReplaceFfxFpeConfig($cryptoReplaceFfxFpeConfig);

    $infoTypeTransformation = (new InfoTypeTransformations_InfoTypeTransformation())
        ->setPrimitiveTransformation($primitiveTransformation);

    $infoTypeTransformations = (new InfoTypeTransformations())
        ->setTransformations([$infoTypeTransformation]);

    // Create the inspect configuration object
    $inspectConfig = (new InspectConfig())
        ->setCustomInfoTypes([$customInfoType]);

    // Create the reidentification configuration object
    $reidentifyConfig = (new DeidentifyConfig())
        ->setInfoTypeTransformations($infoTypeTransformations);

    $item = (new ContentItem())
        ->setValue($string);

    $parent = $dlp->projectName($callingProjectId);

    // Run request
    $response = $dlp->reidentifyContent($parent, [
        'reidentifyConfig' => $reidentifyConfig,
        'inspectConfig' => $inspectConfig,
        'item' => $item
    ]);

    $likelihoods = ['Unknown', 'Very unlikely', 'Unlikely', 'Possible',
                    'Likely', 'Very likely'];

    // Print the results
    $reidentifiedValue = $response->getItem()->getValue();
    print($reidentifiedValue);
}
# [END dlp_reidentify_fpe]
