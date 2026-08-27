<?php

namespace Tests;

use Andreg\FilamentEnhancer\Providers\FilamentEnhancerServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {

	protected function getPackageProviders( $app ): array {
		return [
			LivewireServiceProvider::class,
			SupportServiceProvider::class,
			TablesServiceProvider::class,
			FilamentEnhancerServiceProvider::class,
		];
	}

	protected function getEnvironmentSetUp( $app ): void {
		$app[ 'config' ]->set( 'database.default', 'testing' );
		$app[ 'config' ]->set( 'database.connections.testing', [
			'driver'   => 'sqlite',
			'database' => ':memory:',
			'prefix'   => '',
		] );
		$app[ 'config' ]->set( 'filament-enhancer.currency', 'EUR' );
	}

}
