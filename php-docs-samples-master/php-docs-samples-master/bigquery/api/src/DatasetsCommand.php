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

namespace Google\Cloud\Samples\BigQuery;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command line utility to list BigQuery datasets.
 *
 * Usage: php bigquery.php datasets
 */
class DatasetsCommand extends Command
{
    use ProjectIdTrait;

    protected function configure()
    {
        $this
            ->setName('datasets')
            ->setDescription('List BigQuery datasets')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command lists all the datasets associated with your project.

    <info>php %command.full_name%</info>

EOF
            )
            ->addOption(
                'project',
                null,
                InputOption::VALUE_REQUIRED,
                'The Google Cloud Platform project name to use for this invocation. ' .
                'If omitted then the current gcloud project is assumed. '
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$projectId = $input->getOption('project')) {
            $projectId = $this->getProjectIdFromGcloud();
        }
        list_datasets($projectId);
    }
}
