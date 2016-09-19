<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Storage\Adapters;

use Nette\SmartObject;
use Voonne\Storage\IOException;


class FileSystemAdapter implements IAdapter
{

	use SmartObject;

	/**
	 * @var string
	 */
	private $path;


	public function __construct($path)
	{
		$this->path = $path;
	}


	/**
	 * {@inheritdoc}
	 */
	public function directoryExists($directoryName)
	{
		return file_exists($this->path . '/' . $directoryName);
	}


	/**
	 * {@inheritdoc}
	 */
	public function createDirectory($directoryName)
	{
		if (!@mkdir($this->path . '/' . $directoryName, 0777)) {
			throw new IOException("Unable to create directory '$directoryName'.");
		}
	}


	/**
	 * {@inheritdoc}
	 */
	public function removeDirectory($directoryName)
	{
		array_map('unlink', glob($this->path . '/' . $directoryName . '/*.*'));

		if (!@rmdir($this->path . '/' . $directoryName)) {
			throw new IOException("Unable to remove directory '$directoryName'.");
		}
	}


	/**
	 * {@inheritdoc}
	 */
	public function fileExists($directoryName, $fileName)
	{
		return file_exists($this->path . '/' . $directoryName . '/' . $fileName);
	}


	/**
	 * @inheritdoc}
	 */
	public function createFile($directoryName, $fileName, $sourceFile)
	{
		if (!@copy($sourceFile, $this->path . '/' . $directoryName . '/' . $fileName)) {
			throw new IOException("Unable to create file '$directoryName'.");
		}
	}


	/**
	 * @inheritdoc}
	 */
	public function removeFile($directoryName, $fileName)
	{
		if (!@unlink($this->path . '/' . $directoryName . '/' . $fileName)) {
			throw new IOException("Unable to remove file '$directoryName'.");
		}
	}


	/**
	 * @inheritdoc}
	 */
	public function getFilePath($directoryName, $fileName)
	{
		return $this->path . '/' . $directoryName . '/' . $fileName;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getFiles($directoryName)
	{
		$files = [];

		foreach (scandir($this->path . '/' . $directoryName) as $file) {
			if(in_array($file, ['.', '..'])) {
				continue;
			}

			$files[] = $file;
		}

		return $files;
	}
}
