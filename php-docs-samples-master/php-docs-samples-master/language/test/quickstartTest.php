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
class quickstartTest extends PHPUnit_Framework_TestCase
{
    public function testQuickstart()
    {
        if (!$projectId = getenv('GOOGLE_PROJECT_ID')) {
            $this->markTestSkipped('GOOGLE_PROJECT_ID must be set.');
        }

        $file = sys_get_temp_dir() . '/language_quickstart.php';
        $contents = file_get_contents(__DIR__ . '/../quickstart.php');
        $contents = str_replace(
            ['YOUR_PROJECT_ID', '__DIR__'],
            [$projectId, sprintf('"%s/.."', __DIR__)],
            $contents
        );
        file_put_contents($file, $contents);

        // Invoke quickstart.php

        ob_start();
        $sentiment = include $file;
        $output = ob_get_clean();

        // Make sure it looks correct
        $this->assertTrue(is_array($sentiment));
        $this->assertArrayHasKey('score', $sentiment);
        $this->assertArrayHasKey('magnitude', $sentiment);
        $this->assertInternalType('double', $sentiment['score']);
        $this->assertTrue(0.1 < $sentiment['score']);
        $this->assertTrue($sentiment['score'] < 1.0);
        $this->assertTrue(0.1 < $sentiment['magnitude']);
        $this->assertTrue($sentiment['magnitude'] < 1.0);

        
        $expectedPatterns = array(
            '/Text: Hello, world!/',
            '/Sentiment: \\d.\\d+, \\d.\\d+/',
        );
        foreach ($expectedPatterns as $pattern) {
            $this->assertRegExp($pattern, $output);
        }
    }
}
