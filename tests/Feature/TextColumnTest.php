<?php

use Andreg\FilamentEnhancer\Tables\Columns\TextColumn;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class TextColumnItem extends Model {

	protected $table = 'text_column_items';

	public $timestamps = false;

	protected $guarded = [];

}

describe( 'TextColumn', function () {
	test( 'is bold with enhanced cell class by default', function () {
		$column = TextColumn::make( 'name' );

		expect( $column->getWeight( 'Acme' ) )->toBe( FontWeight::Bold )
			->and( $column->getExtraCellAttributes() )->toMatchArray( [ 'class' => 'enhanced-text-column' ] );
	} );

	test( 'appends matching badges to the formatted state', function () {
		$column = TextColumn::make( 'name' )
			->badges( [
				[
					'label'     => 'Default',
					'color'     => 'success',
					'condition' => fn ( TextColumnItem $record ): bool => (bool) $record->is_default,
				],
				[
					'label'     => 'Archived',
					'color'     => 'gray',
					'condition' => fn ( TextColumnItem $record ): bool => (bool) $record->is_archived,
				],
			] );

		$record = new TextColumnItem( [
			'name'        => 'Acme',
			'is_default'  => true,
			'is_archived' => false,
		] );

		$column->record( $record );

		$formatted = $column->formatState( 'Acme' );

		expect( $formatted )->toBeInstanceOf( HtmlString::class )
			->and( $formatted->toHtml() )->toContain( 'Acme' )
			->and( $formatted->toHtml() )->toContain( 'Default' )
			->and( $formatted->toHtml() )->toContain( 'fi-color-success' )
			->and( $formatted->toHtml() )->not->toContain( 'Archived' );
	} );

	test( 'wraps plain state in html when no badges match', function () {
		$column = TextColumn::make( 'name' )
			->badges( [
				[
					'label'     => 'Default',
					'condition' => fn ( TextColumnItem $record ): bool => (bool) $record->is_default,
				],
			] );

		$record = new TextColumnItem( [
			'name'       => 'Acme',
			'is_default' => false,
		] );

		$column->record( $record );

		$formatted = $column->formatState( 'Acme' );

		expect( $formatted )->toBeInstanceOf( HtmlString::class )
			->and( $formatted->toHtml() )->toBe( 'Acme' );
	} );
} );
