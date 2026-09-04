<?php

use Andreg\FilamentEnhancer\Forms\Components\ProgressEntry;
use Illuminate\Database\Eloquent\Model;

class ProgressEntryItem extends Model {

	protected $table = 'progress_entry_items';

	public $timestamps = false;

	protected $guarded = [];

}

describe( 'ProgressEntry', function () {
	test( 'uses the package namespaced progress entry view', function () {
		$entry = ProgressEntry::make( 'usage' );

		expect( $entry->getView() )->toBe( 'filament-enhancer::components.progress-entry' )
			->and( view()->exists( 'filament-enhancer::components.progress-entry' ) )->toBeTrue();
	} );

	test( 'defaults value and max to zero when unset', function () {
		expect( ProgressEntry::make( 'usage' )->getState() )->toBe( [
			'value' => 0,
			'max'   => 0,
		] );
	} );

	test( 'evaluates scalar value and max in getState', function () {
		$entry = ProgressEntry::make( 'usage' )
			->value( 3 )
			->max( 10 );

		expect( $entry->getState() )->toBe( [
			'value' => 3,
			'max'   => 10,
		] );
	} );

	test( 'defers closure evaluation until getState', function () {
		$valueResolved = false;
		$maxResolved   = false;

		$entry = ProgressEntry::make( 'usage' )
			->value( function () use ( &$valueResolved ): int {
				$valueResolved = true;

				return 2;
			} )
			->max( function () use ( &$maxResolved ): int {
				$maxResolved = true;

				return 8;
			} );

		expect( $valueResolved )->toBeFalse()
			->and( $maxResolved )->toBeFalse();

		expect( $entry->getState() )->toBe( [
			'value' => 2,
			'max'   => 8,
		] );

		expect( $valueResolved )->toBeTrue()
			->and( $maxResolved )->toBeTrue();
	} );

	test( 'evaluates filament closures with injected record by type', function () {
		$record = new ProgressEntryItem( [
			'used'  => 4,
			'total' => 20,
		] );

		$entry = ProgressEntry::make( 'usage' )
			->model( $record )
			->value( fn ( ProgressEntryItem $record ): int => (int) $record->used )
			->max( fn ( ProgressEntryItem $record ): int => (int) $record->total );

		expect( $entry->getState() )->toBe( [
			'value' => 4,
			'max'   => 20,
		] );
	} );

	test( 'evaluates filament closures with injected record by name', function () {
		$record = new ProgressEntryItem( [
			'used'  => 7,
			'total' => 14,
		] );

		$entry = ProgressEntry::make( 'usage' )
			->model( $record )
			->value( fn ( $record ): int => (int) $record->used )
			->max( fn ( $record ): int => (int) $record->total );

		expect( $entry->getState() )->toBe( [
			'value' => 7,
			'max'   => 14,
		] );
	} );
} );
