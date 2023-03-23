<?php 

if (!function_exists('is_laravel')) {
	function is_laravel(): bool
	{
		return get_class(object: app()) == 'Illuminate\Foundation\Application';
	}
}

if (!function_exists('is_lumen')) {
	function is_lumen(): bool
	{
		return get_class(object: app()) == 'Laravel\Lumen\Application';
	}
}