<?php

namespace Andreg\FilamentEnhancer\Forms\Fields;

use Filament\Forms\Components\TextInput;

class VersionInput extends TextInput {

	protected function setUp(): void {
		parent::setUp();

		$this->extraInputAttributes( [
			'class'   => 'font-mono',
			'pattern' => '^v?[0-9]{1,3}\.[0-9]{1,3}(\.[0-9]{1,3})?$',
		] );
		$this->placeholder( '1.0' );
	}

}
