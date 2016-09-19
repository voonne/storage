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
	 * Creates new directory.
	 *
	 * @param string $name
	 *
	 * @return Directory
	 *
	 * @throws DuplicateEntryException
	 */
	public function createDirectory($name)
	{
		if ($this->adapter->directoryExists($name)) {
			throw new DuplicateEntryException("Directory '$name' already exists.");
		}

		$this->adapter->createDirectory($name);

		return new Directory($name, $this->adapter);
	}


	/**
	 * Returns existing directory.
	 *
	 * @param string $name
	 *
	 * @return Directory
	 *
	 * @throws DirectoryNotFoundException
	 */
	public function getDirectory($name)
	{
		if (!$this->adapter->directoryExists($name)) {
			throw new DirectoryNotFoundException("Directory '$name' not found.");
		}

		return new Directory($name, $this->adapter);
	}

}
