<?php

namespace Andreg\FilamentEnhancer\Forms\Fields;

use Filament\Forms\Components\TagsInput;
use Filament\Support\Icons\Heroicon;

class MultipleEmailInput extends TagsInput {

	protected function setUp(): void {
		parent::setUp();

		$this->placeholder( '' );
		$this->extraInputAttributes( [ 'class' => 'font-mono' ] );
		$this->prefixIcon( Heroicon::OutlinedAtSymbol );
		$this->nestedRecursiveRules( [
			'email',
		] );
	}

}
