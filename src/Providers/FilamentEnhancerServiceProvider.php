<?php

namespace Andreg\FilamentEnhancer\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
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
		$this->loadViewsFrom( __DIR__ . '/../../resources/views', 'filament-enhancer' );

		$this->createTableMacros();
		$this->injectCustomStyle();

		$this->publishes( [
			__DIR__ . '/../../config/filament-enhancer.php' => config_path( 'filament-enhancer.php' ),
			__DIR__ . '/../../skills/filament-ui-patterns'  => base_path( '.cursor/skills/filament-ui-patterns' ),
		], 'filament-enhancer' );

		$this->publishes( [
			__DIR__ . '/../../resources/views' => resource_path( 'views/vendor/filament-enhancer' ),
		], 'filament-enhancer-views' );
	}

	private function injectCustomStyle(): void {
		FilamentView::registerRenderHook(
			PanelsRenderHook::HEAD_END,
			function () {
				return '<style>' . file_get_contents( __DIR__ . '/../../resources/style.css' ) . '</style>';
			}
		);
	}

}
