<?php

use Andreg\FilamentEnhancer\Tables\Columns\SubdomainColumn;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

describe( 'SubdomainColumn', function () {
	test( 'renders subdomain as mono html with globe icon', function () {
		$column = SubdomainColumn::make( 'subdomain' );

		$formatted = $column->formatState( 'acme' );

		expect( $formatted )->toBeInstanceOf( HtmlString::class )
			->and( $formatted->toHtml() )->toBe( '<span class="font-mono">acme</span>' )
			->and( $column->getIcon( 'acme' ) )->toBe( Heroicon::OutlinedGlobeAlt )
			->and( $column->getIconPosition() )->toBe( IconPosition::Before )
			->and( $column->getFontFamily( 'acme' ) )->toBe( FontFamily::Mono );
	} );
} );
