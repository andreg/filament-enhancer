<?php

use Andreg\FilamentEnhancer\Tables\Columns\FileNameColumn;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

describe( 'FileNameColumn', function () {
	test( 'renders filename as mono html with document icon', function () {
		$column = FileNameColumn::make( 'path' );

		$formatted = $column->formatState( 'manuale.pdf' );

		expect( $formatted )->toBeInstanceOf( HtmlString::class )
			->and( $formatted->toHtml() )->toBe( '<span class="font-mono">manuale.pdf</span>' )
			->and( $column->getIcon( 'manuale.pdf' ) )->toBe( Heroicon::OutlinedDocumentText )
			->and( $column->getIconPosition() )->toBe( IconPosition::Before )
			->and( $column->getFontFamily( 'manuale.pdf' ) )->toBe( FontFamily::Mono );
	} );
} );
