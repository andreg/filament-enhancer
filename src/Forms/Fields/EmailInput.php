<?php

namespace Andreg\FilamentEnhancer\Forms\Fields;

use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

class EmailInput extends TextInput {

	protected function setUp(): void {
		parent::setUp();

		$this->extraInputAttributes( [ 'class' => 'font-mono' ] );
		$this->email();
		$this->prefixIcon( Heroicon::OutlinedAtSymbol );
	}

}
