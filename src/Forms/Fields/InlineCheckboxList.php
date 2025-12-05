<?php

namespace Andreg\FilamentEnhancer\Forms\Fields;

use Filament\Forms\Components\CheckboxList;

class InlineCheckboxList extends CheckboxList {

	protected function setUp(): void {
		parent::setUp();

		$this->extraAttributes( [ 'class' => join( ' ', [
			'fe-inline-checkbox-list',
			'[&_.fi-fo-checkbox-list-option-text]:grow',
			'[&_.fi-fo-checkbox-list-option-text]:flex',
			'[&_.fi-fo-checkbox-list-option-text]:gap-3',
			'[&_.fi-fo-checkbox-list-option-label]:w-[50%]',
			'[&_.fi-fo-checkbox-list-option-description]:w-[50%]',
			'[&_.fi-fo-checkbox-list-option-description]:text-end',
		] ) ] );
	}

}
