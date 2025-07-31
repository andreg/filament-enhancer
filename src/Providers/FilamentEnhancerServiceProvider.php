<?php

namespace Andreg\FilamentEnhancer\Providers;

use Illuminate\Support\ServiceProvider;

class FilamentEnhancerServiceProvider extends ServiceProvider {
	use Traits\HandlesTables;

	public function boot(): void {
		$this->createTableMacros();
	}

}
