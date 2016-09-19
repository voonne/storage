<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Storage;

use Defr\MimeType;
use Nette\SmartObject;
use Voonne\Storage\Adapters\IAdapter;


class File
{

	use SmartObject;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var Directory
	 */
	private $directory;

	/**
	 * @var IAdapter
	 */
	private $adapter;


	public function __construct($name, Directory $directory, IAdapter $adapter)
	{
		$this->name = $name;
		$this->directory = $directory;
		$this->adapter = $adapter;
	}


	/**
	 * Returns file name.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * Returns directory instance.
	 *
	 * @return Directory
	 */
	public function getDirectory()
	{
		return $this->directory;
	}


	/**
	 * Returns local path to file.
	 *
	 * @return string
	 */
	public function getPath()
	{
		return $this->adapter->getFilePath($this->directory->getName(), $this->name);
	}


	/**
	 * Returns mime type.
	 */
	public function getMimeType()
	{
		return MimeType::get($this->getPath());
	}


	/**
	 * Removes this file.
	 */
	public function remove()
	{
		$this->adapter->removeFile($this->directory->getName(), $this->name);
	}

}
