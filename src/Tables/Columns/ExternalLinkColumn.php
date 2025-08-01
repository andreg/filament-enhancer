<?php

namespace Andreg\FilamentEnhancer\Tables\Columns;

use Illuminate\Support\Str;

class ExternalLinkColumn extends \Filament\Tables\Columns\TextColumn {

	protected function setUp(): void {
		parent::setUp();

		$this->url( function ( $state ) {
			return $state;
		}, true );

		$this->formatStateUsing( function ( $state ) {
			return Str::excerpt( $state, '://', [
				'radius' => 20,
			] );
		} );
	}

}
