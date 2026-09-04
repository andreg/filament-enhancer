<?php

use Andreg\FilamentEnhancer\Tables\Columns\ExternalLinkColumn;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

describe( 'ExternalLinkColumn', function () {
	test( 'excerpts url around scheme separator and opens as external link', function () {
		$column = ExternalLinkColumn::make( 'url' );
		$url    = 'https://example.com/very/long/path/to/resource';

		expect( $column->formatState( $url ) )->toBe( 'https://example.com/very/lon...' )
			->and( $column->getUrl( $url ) )->toBe( $url )
			->and( $column->shouldOpenUrlInNewTab() )->toBeTrue()
			->and( $column->getFontFamily( $url ) )->toBe( FontFamily::Mono )
			->and( $column->getIcon( $url ) )->toBe( Heroicon::OutlinedGlobeAlt )
			->and( $column->getIconPosition() )->toBe( IconPosition::Before );
	} );

	test( 'leaves short urls unchanged', function () {
		$column = ExternalLinkColumn::make( 'url' );
		$url    = 'https://acme.test';

		expect( $column->formatState( $url ) )->toBe( $url )
			->and( $column->getUrl( $url ) )->toBe( $url );
	} );
} );
