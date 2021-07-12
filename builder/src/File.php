<?php declare(strict_types = 1);

namespace App;

use Nette\Utils\FileSystem;

final class File
{

	public static function replace(string $file, string $search, string $replace): void
	{
		FileSystem::write($file, str_replace($search, $replace, FileSystem::read($file)));
	}

}
