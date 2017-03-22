<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Storage;

use Nette\SmartObject;
use Voonne\Storage\Adapters\IAdapter;


class StorageManager
{

	use SmartObject;

	/**
	 * @var IAdapter
	 */
	private $adapter;


	public function __construct(IAdapter $adapter)
	{
		$this->adapter = $adapter;
	}


	/**
	 * Returns existing directory.
	 *
	 * @param string $name
	 *
	 * @return Directory
	 */
	public function getDirectory($name)
	{
		if (!$this->adapter->directoryExists($name)) {
			$this->adapter->createDirectory($name);
		}

		return new Directory($name, $this->adapter);

	}

}
