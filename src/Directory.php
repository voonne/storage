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


class Directory
{

	use SmartObject;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var IAdapter
	 */
	private $adapter;


	public function __construct($name, IAdapter $adapter)
	{
		$this->name = $name;
		$this->adapter = $adapter;
	}


	/**
	 * Returns directory name.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * Creates new file.
	 *
	 * @param string $file
	 * @param string $filePath
	 *
	 * @return File
	 *
	 * @throws InvalidArgumentException
	 * @throws DuplicateEntryException
	 */
	public function createFile($file, $filePath)
	{
		if (!is_file($filePath)) {
			throw new InvalidArgumentException('File must be valid path.');
		}

		if ($this->adapter->fileExists($this->name, $file)) {
			throw new DuplicateEntryException("File '$file' already exists.");
		}

		$this->adapter->createFile($this->name, $file, $filePath);

		return new File($file, $this, $this->adapter);
	}


	/**
	 * Returns specific file.
	 *
	 * @param string $file
	 *
	 * @return File
	 *
	 * @throws FileNotFoundException
	 */
	public function getFile($file)
	{
		if (!$this->adapter->fileExists($this->name, $file)) {
			throw new FileNotFoundException("File '$file' not found.");
		}

		return new File($file, $this, $this->adapter);
	}


	/**
	 * Returns list of files.
	 *
	 * @return array
	 */
	public function getFiles()
	{
		$files = [];

		foreach ($this->adapter->getFiles($this->name) as $file) {
			$files[] = new File($file, $this, $this->adapter);
		}

		return $files;
	}


	/**
	 * Removes this directory.
	 */
	public function remove()
	{
		$this->adapter->removeDirectory($this->name);
	}

}
