<?php

use Andreg\FilamentEnhancer\Forms\Fields\SubdomainInput;
use Filament\Support\Icons\Heroicon;

describe( 'SubdomainInput', function () {
	test( 'applies mono input class and globe prefix icon', function () {
		$input = SubdomainInput::make( 'subdomain' );

		expect( $input->getExtraInputAttributes() )->toMatchArray( [ 'class' => 'font-mono' ] )
			->and( $input->getPrefixIcon() )->toBe( Heroicon::OutlinedGlobeAlt );
	} );
} );
