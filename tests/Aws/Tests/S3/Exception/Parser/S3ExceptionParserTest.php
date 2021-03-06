<?php
/**
 * Copyright 2010-2013 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace Aws\Tests\Common\Exception\Parser;

use Aws\S3\Exception\Parser\S3ExceptionParser;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Message\Request;

/**
 * @covers Aws\S3\Exception\Parser\S3ExceptionParser
 */
class S3ExceptionParserTest extends \Guzzle\Tests\GuzzleTestCase
{
		/**
		 * @return array
		 */
		public function getDataForParsingTest()
		{
				return array(
						array('http://foo.s3.amazonaws.com/', '400 Error', null),
						array('http://s3.amazonaws.com/', '404 Not Found', null),
						array('http://s3.amazonaws.com/foo', '404 Not Found', 'NoSuchBucket'),
						array('http://foo.s3.amazonaws.com/', '404 Not Found', 'NoSuchBucket'),
						array('http://foo.s3.amazonaws.com/bar', '404 Not Found', 'NoSuchKey'),
						array('http://s3.amazonaws.com/foo/bar', '404 Not Found', 'NoSuchKey'),
						array('http://foo.s3-us-gov-west-1.amazonaws.com/bar', '404 Not Found', 'NoSuchKey'),
						array('http://foo.s3.amazonaws.com/', '403 Access Denied', 'AccessDenied')
				);
		}

		/**
		 * @dataProvider getDataForParsingTest
		 */
		public function testParsesResponsesWithNoBody($url, $message, $code)
		{
				$request = new Request('HEAD', $url);
				$response = Response::fromMessage("HTTP/1.1 $message\r\n\r\n");
				$response->setRequest($request);

				$parser = new S3ExceptionParser();
				$result = $parser->parse($response);

				$this->assertEquals($code, $result['code']);
		}
}
