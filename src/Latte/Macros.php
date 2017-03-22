<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Storage\Latte;

use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;
use Voonne\Storage\InvalidArgumentException;


class Macros extends MacroSet
{

	public static function install(Compiler $compiler)
	{
		$macros = new static($compiler);

		$macros->addMacro('assetLink', [$macros, 'macroAssetLink']);

		$macros->addMacro('fileLink', [$macros, 'macroFileLink']);

		return $macros;
	}


	public function macroAssetLink(MacroNode $macroNode, PhpWriter $phpWriter)
	{
		if (!isset($macroNode->args) || empty($macroNode->args)) {
			throw new InvalidArgumentException('Invalid asset link: name must be non-empty string.');
		}

		return 'echo $this->global->uiPresenter->link(":Admin:Api:Assets:default", ["name" => "' . trim($macroNode->args) . '"]);';
	}


	public function macroFileLink(MacroNode $macroNode, PhpWriter $phpWriter)
	{
		$args = explode(',', $macroNode->args);

		if (!isset($args[0]) || empty($args[0])) {
			throw new InvalidArgumentException('Invalid asset link: directory name must be non-empty string.');
		}

		if (!isset($args[1]) || empty($args[1])) {
			throw new InvalidArgumentException('Invalid asset link: file name must be non-empty string.');
		}

		return 'echo $this->global->uiPresenter->link(":Admin:Api:Files:default", ["directoryName" => ' . trim($args[0]) . ', "fileName" => ' . trim($args[1]) . ']);';
	}

}
