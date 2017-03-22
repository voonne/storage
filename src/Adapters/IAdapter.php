<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Storage\Adapters;


interface IAdapter
{

	/**
	 * Checks if folder exists.
	 *
	 * @param string $directoryName
	 *
	 * @return bool
	 */
	public function directoryExists($directoryName);


	/**
	 * Creates new directory.
	 *
	 * @param string $directoryName
	 */
	public function createDirectory($directoryName);


	/**
	 * Removes directory.
	 *
	 * @return string $directoryName
	 */
	public function removeDirectory($directoryName);


	/**
	 * Checks if file exists.
	 *
	 * @param string $directoryName
	 * @param string $fileName
	 *
	 * @return bool
	 */
	public function fileExists($directoryName, $fileName);


	/**
	 * Creates new file.
	 *
	 * @param string $directoryName
	 * @param string $fileName
	 * @param string $sourceFile
	 */
	public function createFile($directoryName, $fileName, $sourceFile);


	/**
	 * Removes file.
	 *
	 * @param string $directoryName
	 * @param string $fileName
	 */
	public function removeFile($directoryName, $fileName);


	/**
	 * Returns local path to file.
	 *
	 * @param string $directoryName
	 * @param string $fileName
	 *
	 * @return string
	 */
	public function getFilePath($directoryName, $fileName);


	/**
	 * Returns list of files.
	 *
	 * @param string $directoryName
	 *
	 * @return array
	 */
	public function getFiles($directoryName);

}
