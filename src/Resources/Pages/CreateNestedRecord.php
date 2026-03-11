<?php

namespace Andreg\FilamentEnhancer\Resources\Pages;

class CreateNestedRecord extends \Filament\Resources\Pages\CreateRecord {

	use Traits\CannotCreateAnotherRecord;
	use Traits\RedirectsToParent;
	use Traits\SimplifiesBreadcrumbs;

	protected function afterCreate(): void {
		parent::afterCreate();

		$this->js( 'document.querySelector("#form [autofocus]")?.focus();' );
	}

}
