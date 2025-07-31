<?php

namespace Andreg\FilamentEnhancer\Resources\Pages;

class CreateRecord extends \Filament\Resources\Pages\CreateRecord {
	use Traits\CannotCreateAnotherRecord;
	use Traits\RedirectsToIndex;

}
