<?php

namespace Voonne\TestStorage;

use Codeception\Test\Unit;
use Mockery;
use Mockery\MockInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use UnitTester;
use Voonne\Storage\Adapters\IAdapter;
use Voonne\Storage\Directory;
use Voonne\Storage\DuplicateEntryException;
use Voonne\Storage\FileNotFoundException;
use Voonne\Storage\InvalidArgumentException;


class DirectoryTest extends Unit
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
	 * @var MockInterface
	 */
	private $adapter;

	/**
	 * @var Directory
	 */
	private $directory;


	protected function _before()
	{
		$this->root = vfsStream::setup('files');

		$this->adapter = Mockery::mock(IAdapter::class);

		$this->directory = new Directory('directory', $this->adapter);
	}


	protected function _after()
	{
		Mockery::close();
	}


	public function testInitialize()
	{
		$this->assertEquals('directory', $this->directory->getName());
	}


	public function testCreateFile()
	{
		vfsStream::newFile('test.txt')
			->at($this->root)
			->setContent('test');

		$this->adapter->shouldReceive('fileExists')
			->once()
			->with('directory', 'test.txt')
			->andReturn(false);

		$this->adapter->shouldReceive('createFile')
			->once()
			->with('directory', 'test.txt', $this->root->url() . '/test.txt');

		$file = $this->directory->createFile('test.txt', $this->root->url() . '/test.txt');

		$this->assertEquals('test.txt', $file->getName());
		$this->assertEquals($this->directory, $file->getDirectory());
	}


	public function testCreateFileBadFilePath()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->directory->createFile('test', '/bad/path/');
	}


	public function testCreateFileFileExists()
	{
		vfsStream::newFile('test.txt')
			->at($this->root)
			->setContent('test');

		$this->adapter->shouldReceive('fileExists')
			->once()
			->with('directory', 'test.txt')
			->andReturn(true);

		$this->expectException(DuplicateEntryException::class);
		$this->directory->createFile('test.txt', $this->root->url() . '/test.txt');
	}


	public function testGetFile()
	{
		$this->adapter->shouldReceive('fileExists')
			->once()
			->with('directory', 'test.txt')
			->andReturn(true);

		$file = $this->directory->getFile('test.txt');

		$this->assertEquals('test.txt', $file->getName());
		$this->assertEquals($this->directory, $file->getDirectory());
	}


	public function testGetFileNotFound()
	{
		$this->adapter->shouldReceive('fileExists')
			->once()
			->with('directory', 'test.txt')
			->andReturn(false);

		$this->expectException(FileNotFoundException::class);
		$this->directory->getFile('test.txt');
	}


	public function testGetFiles()
	{
		$this->adapter->shouldReceive('getFiles')
			->once()
			->with('directory')
			->andReturn(['test1.txt', 'test2.txt']);

		$files = $this->directory->getFiles();

		$this->assertCount(2, $files);

		$this->assertEquals('test1.txt', $files[0]->getName());
		$this->assertEquals($this->directory, $files[0]->getDirectory());
		$this->assertEquals('test2.txt', $files[1]->getName());
		$this->assertEquals($this->directory, $files[1]->getDirectory());
	}


	public function testRemove()
	{
		$this->adapter->shouldReceive('removeDirectory')
			->once()
			->with('directory');

		$this->directory->remove();
	}

}
