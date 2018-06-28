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

# [START dlp_inspect_datastore]
use Google\Cloud\Dlp\V2\DlpServiceClient;
use Google\Cloud\Dlp\V2\DatastoreOptions;
use Google\Cloud\Dlp\V2\InfoType;
use Google\Cloud\Dlp\V2\Action;
use Google\Cloud\Dlp\V2\Action_PublishToPubSub;
use Google\Cloud\Dlp\V2\InspectConfig;
use Google\Cloud\Dlp\V2\InspectJobConfig;
use Google\Cloud\Dlp\V2\KindExpression;
use Google\Cloud\Dlp\V2\PartitionId;
use Google\Cloud\Dlp\V2\StorageConfig;
use Google\Cloud\Dlp\V2\Likelihood;
use Google\Cloud\Dlp\V2\DlpJob_JobState;
use Google\Cloud\Dlp\V2\InspectConfig_FindingLimits;
use Google\Cloud\PubSub\PubSubClient;

/**
 * Inspect Datastore, using Pub/Sub for job status notifications.
 *
 * @param string $callingProjectId The project ID to run the API call under
 * @param string $dataProjectId The project ID containing the target Datastore
 *        (This may or may not be equal to $callingProjectId)
 * @param string $topicId The name of the Pub/Sub topic to notify once the job completes
 * @param string $subscriptionId The name of the Pub/Sub subscription to use when listening for job
 * @param string $kind The datastore kind to inspect
 * @param string $namespaceId The ID namespace of the Datastore document to inspect
 * @param int $maxFindings (Optional) The maximum number of findings to report per request (0 = server maximum)
 */
function inspect_datastore(
    $callingProjectId,
    $dataProjectId,
    $topicId,
    $subscriptionId,
    $kind,
    $namespaceId,
    $maxFindings = 0
) {
    // Instantiate clients
    $dlp = new DlpServiceClient();
    $pubsub = new PubSubClient();
    $topic = $pubsub->topic($topicId);

    // The infoTypes of information to match
    $personNameInfoType = (new InfoType())
        ->setName('PERSON_NAME');
    $phoneNumberInfoType = (new InfoType())
        ->setName('PHONE_NUMBER');
    $infoTypes = [$personNameInfoType, $phoneNumberInfoType];

    // The minimum likelihood required before returning a match
    $minLikelihood = likelihood::LIKELIHOOD_UNSPECIFIED;

    // Specify finding limits
    $limits = (new InspectConfig_FindingLimits())
        ->setMaxFindingsPerRequest($maxFindings);

    // Construct items to be inspected
    $partitionId = (new PartitionId())
        ->setProjectId($dataProjectId)
        ->setNamespaceId($namespaceId);

    $kindExpression = (new KindExpression())
        ->setName($kind);

    $datastoreOptions = (new DatastoreOptions())
        ->setPartitionId($partitionId)
        ->setKind($kindExpression);

    // Construct the inspect config object
    $inspectConfig = (new InspectConfig())
        ->setInfoTypes($infoTypes)
        ->setMinLikelihood($minLikelihood)
        ->setLimits($limits);

    // Construct the storage config object
    $storageConfig = (new StorageConfig())
        ->setDatastoreOptions($datastoreOptions);

    // Construct the action to run when job completes
    $pubSubAction = (new Action_PublishToPubSub())
        ->setTopic($topic->name());

    $action = (new Action())
        ->setPubSub($pubSubAction);

    // Construct inspect job config to run
    $inspectJob = (new InspectJobConfig())
        ->setInspectConfig($inspectConfig)
        ->setStorageConfig($storageConfig)
        ->setActions([$action]);

    // Listen for job notifications via an existing topic/subscription.
    $subscription = $topic->subscription($subscriptionId);

    // Submit request
    $parent = $dlp->projectName($callingProjectId);
    $job = $dlp->createDlpJob($parent, [
        'inspectJob' => $inspectJob
    ]);

    // Poll via Pub/Sub until job finishes
    $polling = true;
    while ($polling) {
        foreach ($subscription->pull() as $message) {
            if (isset($message->attributes()['DlpJobName']) &&
                $message->attributes()['DlpJobName'] === $job->getName()) {
                $subscription->acknowledge($message);
                $polling = false;
            }
        }
    }

    // Sleep for half a second to avoid race condition with the job's status.
    usleep(500000);

    // Get the updated job
    $job = $dlp->getDlpJob($job->getName());

    // Print finding counts
    printf('Job %s status: %s' . PHP_EOL, $job->getName(), $job->getState());
    switch ($job->getState()) {
        case DlpJob_JobState::DONE:
            $infoTypeStats = $job->getInspectDetails()->getResult()->getInfoTypeStats();
            if (count($infoTypeStats) === 0) {
                print('No findings.' . PHP_EOL);
            } else {
                foreach ($infoTypeStats as $infoTypeStat) {
                    printf('  Found %s instance(s) of infoType %s' . PHP_EOL, $infoTypeStat->getCount(), $infoTypeStat->getInfoType()->getName());
                }
            }
            break;
        case DlpJob_JobState::FAILED:
            printf('Job %s had errors:' . PHP_EOL, $job->getName());
            $errors = $job->getErrors();
            foreach ($errors as $error) {
                var_dump($error->getDetails());
            }
            break;
        default:
            print('Unknown job state. Most likely, the job is either running or has not yet started.');
    }
}
# [END dlp_inspect_datastore]
