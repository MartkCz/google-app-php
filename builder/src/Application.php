<?php declare(strict_types = 1);

namespace App;

use Nette\Utils\FileSystem;

final class Application
{

	private const NGINX_GEN_FILE = '/etc/nginx/conf.d/nginx-gen.conf';
	private const NGINX_FILE = '/etc/nginx/nginx.conf';
	private const XDEBUG_FILE = '/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini';
	private const PHP_FILE = '/usr/local/etc/php/conf.d/php.ini';

	public function run(): void
	{
		$arguments = new ConsoleArguments(
			getopt('', [
				'skip::', // skip all
				'port::', // (default: 8080) set port
				'https::', // enable http to https redirection
				'non-www::', // enable www to non-www redirection
				'cache-css-js-long::', // cache css and js for long time
				'cache-media-long::', // cache images, icons, video, audio, HTC for long time
				'xdebug::', // enable xdebug
				'memory-limit::', // (default: 64M) php memory limit
				'max-execution-time::', // (default: 30) php max execution time
				'max-input-time::', // (default: 30) php max input time
				'mkdir::', // makes directories
			])
		);

		if ($arguments->skip) {
			return;
		}

		$nginx = new FileBuilder(self::NGINX_GEN_FILE);
		$xdebug = new FileBuilder(self::XDEBUG_FILE);
		if ($arguments->port !== '8080') {
			File::replace(self::NGINX_FILE, 'listen 8080;', sprintf('listen %s;', $arguments->port));
		}

		if ($arguments->maxExecutionTime !== '30') {
			File::replace(
				self::PHP_FILE,
				'max_execution_time = 30',
				sprintf('max_execution_time = %s', $arguments->maxExecutionTime)
			);
		}

		if ($arguments->maxInputTime !== '30') {
			File::replace(
				self::PHP_FILE,
				'max_input_time = 30',
				sprintf('max_input_time = %s', $arguments->maxInputTime)
			);
		}

		if ($arguments->memoryLimit !== '64M') {
			File::replace(
				self::PHP_FILE,
				'memory_limit = 64M',
				sprintf('memory_limit = %s', $arguments->memoryLimit)
			);
		}

		if ($arguments->https) {
			$nginx->addFromFile(__DIR__ . '/../assets/nginx-https.conf');
		}

		if ($arguments->nonWww) {
			$nginx->addFromFile(__DIR__ . '/../assets/nginx-non-www.conf');
		}

		if ($arguments->cacheCssJsLong) {
			$nginx->addFromFile(__DIR__ . '/../assets/nginx-cache-css-js-long.conf');
		}

		if ($arguments->cacheMediaLong) {
			$nginx->addFromFile(__DIR__ . '/../assets/nginx-cache-media-long.conf');
		}

		if ($arguments->xdebug) {
			$xdebug->addFromFile(__DIR__ . '/../assets/xdebug.ini');
		}

		foreach ($arguments->mkdir as $path) {
			FileSystem::createDir($path);

			chown($path, 'www-data');
			chgrp($path, 'www-data');
		}

		// writes
		$nginx->write();
		$xdebug->write();
	}

}
