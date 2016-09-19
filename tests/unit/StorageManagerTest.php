<?php

namespace Voonne\TestStorage;

use Codeception\Test\Unit;
use Mockery;
use Mockery\MockInterface;
use UnitTester;
use Voonne\Storage\Adapters\IAdapter;
use Voonne\Storage\DirectoryNotFoundException;
use Voonne\Storage\DuplicateEntryException;
use Voonne\Storage\StorageManager;


class StorageManagerTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var MockInterface
	 */
	private $adapter;

	/**
	 * @var StorageManager
	 */
	private $storageManager;


	protected function _before()
	{
		$this->adapter = Mockery::mock(IAdapter::class);

		$this->storageManager = new StorageManager($this->adapter);
	}


	protected function _after()
	{
		Mockery::close();
	}


	public function testCreateDirectory()
	{
		$this->adapter->shouldReceive('directoryExists')
			->once()
			->with('directory')
			->andReturn(false);

		$this->adapter->shouldReceive('createDirectory')
			->once()
			->with('directory');

		$directory = $this->storageManager->createDirectory('directory');

		$this->assertEquals('directory', $directory->getName());
	}


	public function testCreateDirectoryDirectoryExists()
	{
		$this->adapter->shouldReceive('directoryExists')
			->once()
			->with('directory')
			->andReturn(true);

		$this->expectException(DuplicateEntryException::class);
		$this->storageManager->createDirectory('directory');
	}


	public function testGetDirectory()
	{
		$this->adapter->shouldReceive('directoryExists')
			->once()
			->with('directory')
			->andReturn(true);

		$directory = $this->storageManager->getDirectory('directory');

		$this->assertEquals('directory', $directory->getName());
	}


	public function testGetDirectoryNoFound()
	{
		$this->adapter->shouldReceive('directoryExists')
			->once()
			->with('directory')
			->andReturn(false);

		$this->expectException(DirectoryNotFoundException::class);
		$this->storageManager->getDirectory('directory');
	}

}
