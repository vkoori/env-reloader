<?php 

namespace Kooriv\Env;

use Illuminate\Support\Facades\Artisan;

class Reload
{
	public static function env(array $data, ?string $envFile=null)
	{
		if (is_null($envFile)) {
			$envFile = is_laravel() ? app()->environmentFilePath() : base_path('.env');
		}

		self::updateEnv(data: $data, envFile: $envFile);
		self::reloadEnv();
		self::reloadConfig();
	}

	private static function updateEnv(array $data=[], string $envFile)
	{
		if (!count($data)) {
			return;
		}

		$pattern = '/([^\=]*)\=[^\n]*/';

		$lines = file($envFile);
		$newLines = [];
		foreach ($lines as $line) {
			preg_match($pattern, $line, $matches);

			if (!count($matches)) {
				$newLines[] = $line;
				continue;
			}

			$key = trim($matches[1]);

			if (!key_exists($key, $data)) {
				$newLines[] = $line;
				continue;
			}

			$line = $key . "={$data[$key]}\n";
			$newLines[] = $line;
			unset($data[$key]);
		}

		$newLines[] = "\n";

		foreach ($data as $key => $value) {
			$newLines[] = "{$key}=" . trim($value) . "\n";
		}

		$newContent = implode('', $newLines);
		file_put_contents($envFile, $newContent);
	}

	private static function reloadEnv()
	{
		if (is_lumen()) {
			(new \Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(base_path()))->bootstrap();
		} else if (is_laravel()) {
			(new \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables())->bootstrap(app());
		}
	}

	private static function reloadConfig()
	{
		if (is_laravel()) {
			(new \Illuminate\Foundation\Bootstrap\LoadConfiguration())->bootstrap(app());

			// Reload the cached config
			if (file_exists(app()->getCachedConfigPath())) {
				Artisan::call("config:cache");
			}
		}
	}
}