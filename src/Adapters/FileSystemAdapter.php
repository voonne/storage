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
	 * Checks if folder exists.
	 *
	 * @param string $directoryName
	 *
	 * @return bool
	 */
	public function directoryExists($directoryName)
	{
		return file_exists($this->path . '/' . $directoryName);
	}


	/**
	 * Creates new directory.
	 *
	 * @param string $directoryName
	 */
	public function createDirectory($directoryName)
	{
		if (!@mkdir($this->path . '/' . $directoryName, 0777)) {
			throw new IOException("Unable to create directory '$directoryName'.");
		}
	}


	/**
	 * Removes directory.
	 *
	 * @return string $directoryName
	 */
	public function removeDirectory($directoryName)
	{
		array_map('unlink', glob($this->path . '/' . $directoryName . '/*.*'));

		if (!@rmdir($this->path . '/' . $directoryName)) {
			throw new IOException("Unable to remove directory '$directoryName'.");
		}
	}


	/**
	 * Checks if file exists.
	 *
	 * @param string $directoryName
	 * @param string $fileName
	 *
	 * @return bool
	 */
	public function fileExists($directoryName, $fileName)
	{
		return file_exists($this->path . '/' . $directoryName . '/' . $fileName);
	}


	/**
	 * Creates new file.
	 *
	 * @param string $directoryName
	 * @param string $fileName
	 * @param string $sourceFile
	 */
	public function createFile($directoryName, $fileName, $sourceFile)
	{
		if (!@copy($sourceFile, $this->path . '/' . $directoryName . '/' . $fileName)) {
			throw new IOException("Unable to create file '$directoryName'.");
		}
	}


	/**
	 * Removes file.
	 *
	 * @param string $directoryName
	 * @param string $fileName
	 */
	public function removeFile($directoryName, $fileName)
	{
		if (!@unlink($this->path . '/' . $directoryName . '/' . $fileName)) {
			throw new IOException("Unable to remove file '$directoryName'.");
		}
	}


	/**
	 * Returns local path to file.
	 *
	 * @param string $directoryName
	 * @param string $fileName
	 *
	 * @return string
	 */
	public function getFilePath($directoryName, $fileName)
	{
		return $this->path . '/' . $directoryName . '/' . $fileName;
	}


	/**
	 * Returns list of files.
	 *
	 * @param string $directoryName
	 *
	 * @return array
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
