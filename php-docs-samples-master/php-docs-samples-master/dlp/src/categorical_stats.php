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

# [START dlp_categorical_stats]
use Google\Cloud\Dlp\V2\DlpServiceClient;
use Google\Cloud\Dlp\V2\RiskAnalysisJobConfig;
use Google\Cloud\Dlp\V2\BigQueryTable;
use Google\Cloud\Dlp\V2\DlpJob_JobState;
use Google\Cloud\Dlp\V2\Action;
use Google\Cloud\Dlp\V2\Action_PublishToPubSub;
use Google\Cloud\Dlp\V2\PrivacyMetric_CategoricalStatsConfig;
use Google\Cloud\Dlp\V2\PrivacyMetric;
use Google\Cloud\Dlp\V2\FieldId;
use Google\Cloud\PubSub\PubSubClient;

/**
 * Computes risk metrics of a column of data in a Google BigQuery table.
 *
 * @param string $callingProjectId The project ID to run the API call under
 * @param string $dataProjectId The project ID containing the target Datastore
 * @param string $topicId The name of the Pub/Sub topic to notify once the job completes
 * @param string $subscriptionId The name of the Pub/Sub subscription to use when listening for job
 * @param string $datasetId The ID of the dataset to inspect
 * @param string $tableId The ID of the table to inspect
 * @param string $columnName The name of the column to compute risk metrics for, e.g. 'age'
 */
function categorical_stats(
    $callingProjectId,
    $dataProjectId,
    $topicId,
    $subscriptionId,
    $datasetId,
    $tableId,
    $columnName
) {
    // Instantiate a client.
    $dlp = new DlpServiceClient();
    $pubsub = new PubSubClient();
    $topic = $pubsub->topic($topicId);

    // Construct risk analysis config
    $columnField = (new FieldId())
        ->setName($columnName);

    $statsConfig = (new PrivacyMetric_CategoricalStatsConfig())
        ->setField($columnField);

    $privacyMetric = (new PrivacyMetric())
        ->setCategoricalStatsConfig($statsConfig);

    // Construct items to be analyzed
    $bigqueryTable = (new BigQueryTable())
        ->setProjectId($dataProjectId)
        ->setDatasetId($datasetId)
        ->setTableId($tableId);

    // Construct the action to run when job completes
    $pubSubAction = (new Action_PublishToPubSub())
        ->setTopic($topic->name());

    $action = (new Action())
        ->setPubSub($pubSubAction);

    // Construct risk analysis job config to run
    $riskJob = (new RiskAnalysisJobConfig())
        ->setPrivacyMetric($privacyMetric)
        ->setSourceTable($bigqueryTable)
        ->setActions([$action]);

    // Submit request
    $parent = $dlp->projectName($callingProjectId);
    $job = $dlp->createDlpJob($parent, [
        'riskJob' => $riskJob
    ]);

    // Listen for job notifications via an existing topic/subscription.
    $subscription = $topic->subscription($subscriptionId);

    // Poll via Pub/Sub until job finishes
    while (true) {
        foreach ($subscription->pull() as $message) {
            if (isset($message->attributes()['DlpJobName']) &&
                $message->attributes()['DlpJobName'] === $job->getName()) {
                $subscription->acknowledge($message);
                break 2;
            }
        }
    }

    // Sleep for half a second to avoid race condition with the job's status.
    usleep(500000);

    // Get the updated job
    $job = $dlp->getDlpJob($job->getName());

    // Helper function to convert Protobuf values to strings
    $value_to_string = function ($value) {
        $json = json_decode($value->serializeToJsonString(), true);
        return array_shift($json);
    };

    // Print finding counts
    printf('Job %s status: %s' . PHP_EOL, $job->getName(), $job->getState());
    switch ($job->getState()) {
        case DlpJob_JobState::DONE:
            $histBuckets = $job->getRiskDetails()->getCategoricalStatsResult()->getValueFrequencyHistogramBuckets();

            foreach ($histBuckets as $bucketIndex => $histBucket) {
                // Print bucket stats
                printf('Bucket %s:' . PHP_EOL, $bucketIndex);
                printf('  Most common value occurs %s time(s)' . PHP_EOL, $histBucket->getValueFrequencyUpperBound());
                printf('  Least common value occurs %s time(s)' . PHP_EOL, $histBucket->getValueFrequencyLowerBound());
                printf('  %s unique value(s) total.', $histBucket->getBucketSize());

                // Print bucket values
                foreach ($histBucket->getBucketValues() as $percent => $quantile) {
                    printf(
                        '  Value %s occurs %s time(s).' . PHP_EOL,
                        $value_to_string($quantile->getValue()),
                        $quantile->getCount()
                    );
                }
            }

            break;
        case DlpJob_JobState::FAILED:
            $errors = $job->getErrors();
            printf('Job %s had errors:' . PHP_EOL, $job->getName());
            foreach ($errors as $error) {
                var_dump($error->getDetails());
            }
            break;
        default:
            printf('Unknown job state. Most likely, the job is either running or has not yet started.');
    }
}
# [END dlp_categorical_stats]
