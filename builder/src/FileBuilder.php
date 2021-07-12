<?php declare(strict_types = 1);

namespace App;

use Nette\Utils\FileSystem;

final class FileBuilder
{

	private string $content = '';

	public function __construct(
		private string $file,
	)
	{
	}

	public function addFromFile(string $file): void
	{
		$this->content .= FileSystem::read($file);
	}

	public function write(): void
	{
		if ($this->content) {
			FileSystem::write($this->file, $this->content);
		}
	}

}
