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

namespace Aws\S3\Waiter;

use Aws\Common\Enum\UaString as Ua;
use Aws\Common\Exception\InvalidArgumentException;
use Aws\Common\Waiter\AbstractResourceWaiter;

/**
 * Returns true when the specified object exists. The resource ID to check can
 * be specified using the following format: "bucket/key".
 */
class ObjectExists extends AbstractResourceWaiter
{
		protected $interval = 5;
		protected $maxAttempts = 20;
		protected $maxFailures = 0;

		/**
		 * {@inheritdoc}
		 * @throws InvalidArgumentException if the resource ID format is invalid
		 */
		public function setResourceId($resourceId)
		{
				if (!strpos($resourceId, '/')) {
						throw new InvalidArgumentException('The resource ID must be in the form of bucket/key');
				}
				parent::setResourceId($resourceId);

				return $this;
		}

		/**
		 * Wait until an object exists
		 */
		protected function doWait()
		{
				list($bucket, $key) = explode('/', $this->resourceId, 2);

				return $this->client->doesObjectExist($bucket, $key, array(
						Ua::OPTION => Ua::WAITER
				));
		}
}