<?php

namespace Andreg\FilamentEnhancer\Forms\Fields;

use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

class URLInput extends TextInput {

	protected function setUp(): void {
		parent::setUp();

		$this->extraInputAttributes( [ 'class' => 'font-mono' ] );
		$this->url( true );
		$this->prefixIcon( Heroicon::GlobeAlt );
	}

}
