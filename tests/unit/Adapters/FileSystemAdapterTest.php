<?php

namespace Voonne\TestStorage\Adapters;

use Codeception\Test\Unit;
use Mockery;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use UnitTester;
use Voonne\Storage\Adapters\FileSystemAdapter;
use Voonne\Storage\IOException;


class FileSystemAdapterTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var vfsStreamDirectory
	 */
	private $root;

	/**
	 * @var FileSystemAdapter
	 */
	private $fileSystemAdapter;


	protected function _before()
	{
		$this->root = vfsStream::setup('files');

		$this->fileSystemAdapter = new FileSystemAdapter($this->root->url());
	}


	protected function _after()
	{
		Mockery::close();
	}


	public function testDirectoryExists()
	{
		$this->assertFalse($this->fileSystemAdapter->directoryExists('directory'));

		vfsStream::newDirectory('directory')
			->at($this->root);

		$this->assertTrue($this->fileSystemAdapter->directoryExists('directory'));
	}


	public function testCreateDirectory()
	{
		$this->fileSystemAdapter->createDirectory('directory');

		$this->expectException(IOException::class);
		$this->fileSystemAdapter->createDirectory('directory');

		$this->root->chmod(0000);

		$this->expectException(IOException::class);
		$this->fileSystemAdapter->createDirectory('directory1');

		$directories = $this->root->getChildren();
		$this->assertEquals('directory', $directories[0]->getName());
		$this->assertEquals('directory1', $directories[1]->getName());
	}


	public function testRemoveDirectory()
	{
		vfsStream::newDirectory('directory')
			->at($this->root);

		$this->fileSystemAdapter->removeDirectory('directory');

		$this->expectException(IOException::class);
		$this->fileSystemAdapter->removeDirectory('directory');
	}


	public function testFileExists()
	{
		$this->assertFalse($this->fileSystemAdapter->fileExists('directory', 'test.txt'));

		$directory = vfsStream::newDirectory('directory')
			->at($this->root);

		vfsStream::newFile('test.txt')
			->at($directory)
			->setContent('test');

		$this->assertTrue($this->fileSystemAdapter->fileExists('directory', 'test.txt'));
	}



	public function testCreateFile()
	{
		$directory = vfsStream::newDirectory('directory')
			->at($this->root);

		vfsStream::newFile('source.txt')
			->at($this->root)
			->setContent('source');

		$this->fileSystemAdapter->createFile('directory', 'test.txt', $this->root->url() . '/source.txt');

		$directory->chmod(0000);

		$this->expectException(IOException::class);
		$this->fileSystemAdapter->createFile('directory', 'test1.txt', $this->root->url() . '/source.txt');
	}


	public function testRemoveFile()
	{
		$directory = vfsStream::newDirectory('directory')
			->at($this->root);

		vfsStream::newFile('test.txt')
			->at($directory)
			->setContent('test');

		$this->fileSystemAdapter->removeFile('directory', 'test.txt');

		vfsStream::newFile('test.txt')
			->at($directory)
			->setContent('test')
			->chmod(0000);

		$this->fileSystemAdapter->removeFile('directory', 'test.txt');
	}


	public function testGetFilePath()
	{
		$this->assertEquals($this->root->url() . '/directory/test.txt', $this->fileSystemAdapter->getFilePath('directory', 'test.txt'));
	}


	public function testGetFiles()
	{
		$directory = vfsStream::newDirectory('directory')
			->at($this->root);

		vfsStream::newFile('test.txt')
			->at($directory)
			->setContent('test');

		vfsStream::newFile('test1.txt')
			->at($directory)
			->setContent('test1');

		$this->assertEquals(['test.txt', 'test1.txt'], $this->fileSystemAdapter->getFiles('directory'));
	}

}
