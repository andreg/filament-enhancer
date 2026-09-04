<?php

namespace Andreg\FilamentEnhancer\Tables\Columns;

use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class SubdomainColumn extends \Filament\Tables\Columns\TextColumn {

	protected function setUp(): void {
		parent::setUp();

		$this->icon( Heroicon::OutlinedGlobeAlt );
		$this->iconPosition( IconPosition::Before );
		$this->fontFamily( FontFamily::Mono );

		$this->formatStateUsing( function ( $state ) {
			return new HtmlString( '<span class="font-mono">' . $state . '</span>' );
		} );
	}

}
