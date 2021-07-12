<?php declare(strict_types = 1);

namespace App;

use InvalidArgumentException;

final class ConsoleArguments
{

	public string $port = '8080';

	public string $memoryLimit = '64M';

	public string $maxExecutionTime = '30';

	public string $maxInputTime = '30';

	public bool $https = false;

	public bool $nonWww = false;

	public bool $skip = false;

	public bool $cacheCssJsLong = false;

	public bool $cacheMediaLong = false;

	public bool $xdebug = false;

	public array $mkdir = [];

	public function __construct(array $arguments)
	{
		foreach ($arguments as $option => $value) {
			$name = preg_replace_callback('#-(\w)#', fn (array $matches) => ucfirst($matches[1]), $option);

			if (!property_exists($this, $name)) {
				throw new InvalidArgumentException(sprintf('Option %s not exists.', $option));
			}

			$property = &$this->$name;
			if (is_string($property)) {
				if ($value === false) {
					continue;
				}

				if (is_array($value)) {
					throw new InvalidArgumentException(sprintf('Multiple options are not allowed for %s.', $option));
				}

				$property = (string) $value;
			} elseif (is_array($property)) {
				if ($value === false) {
					continue;
				}

				if (!is_array($value)) {
					$value = [$value];
				}

				foreach ($value as $val) {
					if ($val !== false) {
						$property[] = $val;
					}
				}

			} else { // bool
				if ($value === false) {
					$value = true;
				}

				if (is_array($value)) {
					throw new InvalidArgumentException(sprintf('Multiple options are not allowed for %s.', $option));
				}

				$property = (bool) $value;
			}
		}
	}

}
