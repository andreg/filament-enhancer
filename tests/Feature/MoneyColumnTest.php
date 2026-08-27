<?php

use Andreg\FilamentEnhancer\Tables\Columns\MoneyColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoneyColumnItem extends Model {

	protected $table = 'money_column_items';

	public $timestamps = false;

	protected $guarded = [];

}

describe( 'MoneyColumn', function () {
	beforeEach( function () {
		Schema::create( 'money_column_items', function ( Blueprint $table ) {
			$table->id();
			$table->integer( 'amount' );
		} );
	} );

	test( 'is sortable by default as a numeric column', function () {
		$column = MoneyColumn::make( 'amount' );

		expect( $column->isSortable() )->toBeTrue()
			->and( $column->isNumeric() )->toBeTrue()
			->and( $column->isMoney() )->toBeTrue();
	} );

	test( 'sorts by the numeric database value, not the formatted display', function () {
		$column = MoneyColumn::make( 'amount' );
		$query  = MoneyColumnItem::query();

		$column->applySort( $query, 'asc' );

		$sql    = strtolower( $query->toSql() );
		$driver = $query->getConnection()->getDriverName();

		expect( $sql )->toContain( 'cast(' );

		if ( in_array( $driver, [ 'mysql', 'mariadb' ], true ) ) {
			expect( $sql )->toContain( 'as decimal' );
		} else {
			expect( $sql )->toContain( 'as numeric' );
		}
	} );

	test( 'orders records numerically rather than as formatted money strings', function () {
		MoneyColumnItem::insert( [
			[ 'amount' => 123456 ],
			[ 'amount' => 20000 ],
			[ 'amount' => 5000 ],
		] );

		$column = MoneyColumn::make( 'amount' );

		$ascending = MoneyColumnItem::query();
		$column->applySort( $ascending, 'asc' );

		expect( $ascending->pluck( 'amount' )->all() )->toBe( [ 5000, 20000, 123456 ] );

		$descending = MoneyColumnItem::query();
		$column->applySort( $descending, 'desc' );

		expect( $descending->pluck( 'amount' )->all() )->toBe( [ 123456, 20000, 5000 ] );
	} );
} );
