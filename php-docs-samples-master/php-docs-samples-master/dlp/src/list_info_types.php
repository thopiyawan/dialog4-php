<?php

/**
 * Copyright 2016 Google Inc.
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

# [START dlp_list_info_types]
use Google\Cloud\Dlp\V2\DlpServiceClient;

/**
 * Lists all Info Types for the Data Loss Prevention (DLP) API.
 *
 * @param string $category Optional filter to use, empty for ''.
 * @param string $languageCode Optional language code, empty for 'en-US'.
 */
function list_info_types($filter = '', $languageCode = '')
{
    // Instantiate a client.
    $dlp = new DlpServiceClient();

    // Run request
    $response = $dlp->listInfoTypes([
        'languageCode' => $languageCode,
        'filter' => $filter
    ]);

    // Print the results
    print('Info Types:' . PHP_EOL);
    foreach ($response->getInfoTypes() as $infoType) {
        printf(
            '  %s (%s)' . PHP_EOL,
            $infoType->getDisplayName(),
            $infoType->getName()
        );
    }
}
# [END dlp_list_info_types]
