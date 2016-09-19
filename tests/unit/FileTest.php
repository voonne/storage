<?php

namespace Voonne\TestStorage;

use Codeception\Test\Unit;
use Mockery;
use Mockery\MockInterface;
use UnitTester;
use Voonne\Storage\Adapters\IAdapter;
use Voonne\Storage\Directory;
use Voonne\Storage\File;


class FileTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var MockInterface
	 */
	private $directory;

	/**
	 * @var MockInterface
	 */
	private $adapter;

	/**
	 * @var File
	 */
	private $file;


	protected function _before()
	{
		$this->directory = Mockery::mock(Directory::class);
		$this->adapter = Mockery::mock(IAdapter::class);

		$this->file = new File('test.txt', $this->directory, $this->adapter);
	}


	protected function _after()
	{
		Mockery::close();
	}


	public function testInitialize()
	{
		$this->assertEquals('test.txt', $this->file->getName());
		$this->assertEquals($this->directory, $this->file->getDirectory());
	}


	public function testGetPath()
	{
		$this->directory->shouldReceive('getName')
			->once()
			->withNoArgs()
			->andReturn('directory');

		$this->adapter->shouldReceive('getFilePath')
			->once()
			->with('directory', 'test.txt')
			->andReturn('/path/directory/test.txt');

		$this->assertEquals('/path/directory/test.txt', $this->file->getPath());
	}


	public function testGetMimeType()
	{
		$this->directory->shouldReceive('getName')
			->once()
			->withNoArgs()
			->andReturn('directory');

		$this->adapter->shouldReceive('getFilePath')
			->once()
			->with('directory', 'test.txt')
			->andReturn('/path/directory/test.txt');

		$this->assertEquals('text/plain', $this->file->getMimeType());
	}


	public function testRemove()
	{
		$this->directory->shouldReceive('getName')
			->once()
			->withNoArgs()
			->andReturn('directory');

		$this->adapter->shouldReceive('removeFile')
			->once()
			->with('directory', 'test.txt');

		$this->file->remove();
	}

}
