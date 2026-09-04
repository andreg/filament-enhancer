<?php

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

describe( 'FilamentEnhancerServiceProvider', function () {
	test( 'injects custom styles at head end', function () {
		$html = FilamentView::renderHook( PanelsRenderHook::HEAD_END )->toHtml();

		expect( $html )->toContain( '<style>' )
			->and( $html )->toContain( '.font-mono' )
			->and( $html )->toContain( '.fi-ta-cell svg.fi-icon' )
			->and( $html )->toContain( '</style>' );
	} );
} );
