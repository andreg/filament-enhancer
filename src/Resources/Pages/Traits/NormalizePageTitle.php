<?php

namespace Andreg\FilamentEnhancer\Resources\Pages\Traits;

use Filament\Facades\Filament;
use Illuminate\Contracts\Support\Htmlable;

trait NormalizePageTitle {

	public function getTitle(): string | Htmlable {
		$resourceClass = Filament::getModelResource( $this->getRecord() );
		$singularLabel = $resourceClass::getModelLabel();

		return ucfirst( $singularLabel ) . ': ' . $this->getRecordTitle();
	}

}
