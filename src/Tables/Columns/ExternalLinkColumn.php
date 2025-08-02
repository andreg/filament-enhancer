<?php

namespace Andreg\FilamentEnhancer\Tables\Columns;

use Filament\Support\Enums\FontFamily;
use Illuminate\Support\Str;

class ExternalLinkColumn extends \Filament\Tables\Columns\TextColumn {

	protected function setUp(): void {
		parent::setUp();

		$this->url( function ( $state ) {
			return $state;
		}, true );

		$this->fontFamily( FontFamily::Mono );

		$this->formatStateUsing( function ( $state ) {
			return Str::excerpt( $state, '://', [
				'radius' => 20,
			] );
		} );
	}

}
