<?php

use Andreg\FilamentEnhancer\Tables\Columns\ProgressColumn;
use Illuminate\Database\Eloquent\Model;

class ProgressColumnItem extends Model {

	protected $table = 'progress_column_items';

	public $timestamps = false;

	protected $guarded = [];

}

describe( 'ProgressColumn', function () {
	test( 'uses the package namespaced progress column view', function () {
		$column = ProgressColumn::make( 'usage' );

		expect( $column->getView() )->toBe( 'filament-enhancer::components.progress-column' )
			->and( view()->exists( 'filament-enhancer::components.progress-column' ) )->toBeTrue();
	} );

	test( 'defaults value and max to zero when unset', function () {
		expect( ProgressColumn::make( 'usage' )->getState() )->toBe( [
			'value' => 0,
			'max'   => 0,
		] );
	} );

	test( 'evaluates scalar value and maximum in getState', function () {
		$column = ProgressColumn::make( 'usage' )
			->value( 3 )
			->maximum( 10 );

		expect( $column->getState() )->toBe( [
			'value' => 3,
			'max'   => 10,
		] );
	} );

	test( 'defers closure evaluation until getState', function () {
		$valueResolved   = false;
		$maximumResolved = false;

		$column = ProgressColumn::make( 'usage' )
			->value( function () use ( &$valueResolved ): int {
				$valueResolved = true;

				return 2;
			} )
			->maximum( function () use ( &$maximumResolved ): int {
				$maximumResolved = true;

				return 8;
			} );

		expect( $valueResolved )->toBeFalse()
			->and( $maximumResolved )->toBeFalse();

		expect( $column->getState() )->toBe( [
			'value' => 2,
			'max'   => 8,
		] );

		expect( $valueResolved )->toBeTrue()
			->and( $maximumResolved )->toBeTrue();
	} );

	test( 'evaluates filament closures with injected record by type', function () {
		$record = new ProgressColumnItem( [
			'used'  => 4,
			'total' => 20,
		] );

		$column = ProgressColumn::make( 'usage' )
			->record( $record )
			->value( fn ( ProgressColumnItem $record ): int => (int) $record->used )
			->maximum( fn ( ProgressColumnItem $record ): int => (int) $record->total );

		expect( $column->getState() )->toBe( [
			'value' => 4,
			'max'   => 20,
		] );
	} );

	test( 'evaluates filament closures with injected record by name', function () {
		$record = new ProgressColumnItem( [
			'used'  => 7,
			'total' => 14,
		] );

		$column = ProgressColumn::make( 'usage' )
			->record( $record )
			->value( fn ( $record ): int => (int) $record->used )
			->maximum( fn ( $record ): int => (int) $record->total );

		expect( $column->getState() )->toBe( [
			'value' => 7,
			'max'   => 14,
		] );
	} );
} );
