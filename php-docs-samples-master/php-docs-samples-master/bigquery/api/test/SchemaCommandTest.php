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

namespace Google\Cloud\Samples\BigQuery\Tests;

use Google\Cloud\Samples\BigQuery\SchemaCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Unit Tests for SchemaCommand.
 */
class SchemaCommandTest extends TestCase
{
    protected static $hasCredentials;

    public static function setUpBeforeClass()
    {
        $path = getenv('GOOGLE_APPLICATION_CREDENTIALS');
        self::$hasCredentials = $path && file_exists($path) &&
            filesize($path) > 0;
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Table must in the format "dataset.table"
     */
    public function testInvalidTableNameThrowsException()
    {
        if (!$projectId = getenv('GOOGLE_PROJECT_ID')) {
            $this->markTestSkipped('No project ID');
        }

        // run the import
        $application = new Application();
        $application->add(new SchemaCommand());
        $commandTester = new CommandTester($application->get('schema'));
        $commandTester->execute(
            [
                'dataset.table' => 'invalid.table.name',
                '--project' => $projectId,
            ],
            ['interactive' => false]
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage "schema-json" is required if the command is not interactive
     */
    public function testSchemaIsRequiredIfNotInteractiveException()
    {
        if (!$projectId = getenv('GOOGLE_PROJECT_ID')) {
            $this->markTestSkipped('No project ID');
        }
        if (!$datasetId = getenv('GOOGLE_BIGQUERY_DATASET')) {
            $this->markTestSkipped('No bigquery dataset name');
        }

        // run the import
        $application = new Application();
        $application->add(new SchemaCommand());
        $commandTester = new CommandTester($application->get('schema'));
        $commandTester->execute(
            [
                'dataset.table' => $datasetId . '.table_name',
                '--project' => $projectId,
            ],
            ['interactive' => false]
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage "no-confirmation" is required to create a dataset if the command is not interactive
     */
    public function testNonexistantDatasetWhenNotInteractiveThrowsException()
    {
        if (!$projectId = getenv('GOOGLE_PROJECT_ID')) {
            $this->markTestSkipped('No project ID');
        }

        // run the import
        $application = new Application();
        $application->add(new SchemaCommand());
        $commandTester = new CommandTester($application->get('schema'));
        $commandTester->execute(
            [
                'dataset.table' => 'thisdoes.notexist',
                'schema-json' => __DIR__ . '/data/test_data.json',
                '--project' => $projectId,
            ],
            ['interactive' => false]
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The supplied dataset does not exist
     */
    public function testDeleteNonexistantDatasetThrowsException()
    {
        if (!$projectId = getenv('GOOGLE_PROJECT_ID')) {
            $this->markTestSkipped('No project ID');
        }

        // run the import
        $application = new Application();
        $application->add(new SchemaCommand());
        $commandTester = new CommandTester($application->get('schema'));
        $commandTester->execute(
            [
                'dataset.table' => 'thisdoes.notexist',
                'schema-json' => __DIR__ . '/data/test_data.json',
                '--delete' => true,
                '--project' => $projectId,
            ],
            ['interactive' => false]
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The supplied table does not exist
     */
    public function testDeleteNonexistantTableThrowsException()
    {
        if (!$projectId = getenv('GOOGLE_PROJECT_ID')) {
            $this->markTestSkipped('No project ID');
        }
        if (!$datasetId = getenv('GOOGLE_BIGQUERY_DATASET')) {
            $this->markTestSkipped('No bigquery dataset name');
        }

        // run the import
        $application = new Application();
        $application->add(new SchemaCommand());
        $commandTester = new CommandTester($application->get('schema'));
        $commandTester->execute(
            [
                'dataset.table' => $datasetId . '.doesnotexist',
                '--delete' => true,
                '--project' => $projectId,
            ],
            ['interactive' => false]
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Cannot supply "--delete" with the "schema-json" argument
     */
    public function testDeleteWithSchemaThrowsException()
    {
        if (!$projectId = getenv('GOOGLE_PROJECT_ID')) {
            $this->markTestSkipped('No project ID');
        }
        if (!$datasetId = getenv('GOOGLE_BIGQUERY_DATASET')) {
            $this->markTestSkipped('No bigquery dataset name');
        }

        // run the import
        $application = new Application();
        $application->add(new SchemaCommand());
        $commandTester = new CommandTester($application->get('schema'));
        $commandTester->execute(
            [
                'dataset.table' => $datasetId . '.doesnotexist',
                'schema-json' => __DIR__ . '/data/test_data.json',
                '--delete' => true,
                '--project' => $projectId,
            ],
            ['interactive' => false]
        );
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage "no-confirmation" is required for deletion if the command is not interactive
     */
    public function testDeleteWhenNotInteractiveThrowsException()
    {
        if (!$projectId = getenv('GOOGLE_PROJECT_ID')) {
            $this->markTestSkipped('No project ID');
        }
        if (!$datasetId = getenv('GOOGLE_BIGQUERY_DATASET')) {
            $this->markTestSkipped('No bigquery dataset name');
        }
        if (!$tableId = getenv('GOOGLE_BIGQUERY_TABLE')) {
            $this->markTestSkipped('No bigquery table name');
        }

        // run the import
        $application = new Application();
        $application->add(new SchemaCommand());
        $commandTester = new CommandTester($application->get('schema'));
        $commandTester->execute(
            [
                'dataset.table' => $datasetId . '.' . $tableId,
                '--delete' => true,
                '--project' => $projectId,
            ],
            ['interactive' => false]
        );
    }
}
