<?php

namespace Andreg\FilamentEnhancer\Resources\Pages;

use Filament\Resources\Pages\EditRecord;

class EditParentRecord extends EditRecord {

	use Traits\DisplaysRelationsWithTabs;
	use Traits\SimplifiesBreadcrumbs;

}
