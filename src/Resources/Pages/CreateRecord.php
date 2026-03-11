<?php

namespace Andreg\FilamentEnhancer\Resources\Pages;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord {

	use Traits\CannotCreateAnotherRecord;
	use Traits\RedirectsToIndex;

	protected function afterCreate(): void {
		parent::afterCreate();

		$this->js( 'document.querySelector("#form [autofocus]")?.focus();' );
	}

}
