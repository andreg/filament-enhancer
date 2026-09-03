<?php

namespace Andreg\FilamentEnhancer\Providers;

use Illuminate\Support\ServiceProvider;

class FilamentEnhancerServiceProvider extends ServiceProvider {

	use Traits\HandlesTables;

	/**
	 * Register any package services.
	 */
	public function register(): void {
		$this->mergeConfigFrom(
			__DIR__ . '/../../config/filament-enhancer.php',
			'filament-enhancer'
		);
	}

	public function boot(): void {
		$this->createTableMacros();

		$this->publishes( [
			__DIR__ . '/../../config/filament-enhancer.php' => config_path( 'filament-enhancer.php' ),
			__DIR__ . '/../../skills/filament-ui-patterns'  => base_path( '.cursor/skills/filament-ui-patterns' ),
		], 'filament-enhancer' );
	}

}
