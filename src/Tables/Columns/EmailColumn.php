<?php

namespace Andreg\FilamentEnhancer\Tables\Columns;

use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

class EmailColumn extends \Filament\Tables\Columns\TextColumn {

	protected function setUp(): void {
		parent::setUp();

		$this->copyable();
		$this->icon( Heroicon::OutlinedClipboard );
		$this->iconPosition( IconPosition::After );
		$this->fontFamily( FontFamily::Mono );
	}

}
