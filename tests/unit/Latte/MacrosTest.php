<?php

namespace Voonne\TestStorage\Latte;

use Codeception\Test\Unit;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\PhpWriter;
use Mockery;
use Mockery\MockInterface;
use UnitTester;
use Voonne\Storage\InvalidArgumentException;
use Voonne\Storage\Latte\Macros;


class MacroTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var MockInterface
	 */
	private $compiler;

	/**
	 * @var Macros
	 */
	private $macros;


	protected function _before()
	{
		$this->compiler = Mockery::mock(Compiler::class);

		$this->macros = new Macros($this->compiler);
	}


	protected function _after()
	{
		Mockery::close();
	}


	public function testMacroAssetLink()
	{
		$macroNode = Mockery::mock(MacroNode::class);
		$phpWriter = Mockery::mock(PhpWriter::class);

		$macroNode->args = 'styles/admin.css';

		$this->assertEquals(
			'echo $this->global->uiPresenter->link(":Admin:Api:Assets:default", ["name" => "styles/admin.css"]);',
			$this->macros->macroAssetLink($macroNode, $phpWriter));
	}


	public function testMacroAssetLinkMissingParameter()
	{
		$macroNode = Mockery::mock(MacroNode::class);
		$phpWriter = Mockery::mock(PhpWriter::class);

		$macroNode->args = '';

		$this->expectException(InvalidArgumentException::class);
		$this->macros->macroAssetLink($macroNode, $phpWriter);
	}


	public function testMacroFileLink()
	{
		$macroNode = Mockery::mock(MacroNode::class);
		$phpWriter = Mockery::mock(PhpWriter::class);

		$macroNode->args = 'photos, photo.png';

		$this->assertEquals(
			'echo $this->global->uiPresenter->link(":Admin:Api:Files:default", ["directoryName" => photos, "fileName" => photo.png]);',
			$this->macros->macroFileLink($macroNode, $phpWriter));
	}


	public function testMacroFileLinkMissingParameter()
	{
		$macroNode = Mockery::mock(MacroNode::class);
		$phpWriter = Mockery::mock(PhpWriter::class);

		$macroNode->args = 'photos';

		$this->expectException(InvalidArgumentException::class);
		$this->macros->macroFileLink($macroNode, $phpWriter);

		$macroNode->args = '';

		$this->expectException(InvalidArgumentException::class);
		$this->macros->macroFileLink($macroNode, $phpWriter);
	}

}
