<?php

namespace Andreg\FilamentEnhancer\Resources\Pages\Traits;

trait CannotCreateAnotherRecord {

	public function canCreateAnother(): bool {
		return false;
	}

}
