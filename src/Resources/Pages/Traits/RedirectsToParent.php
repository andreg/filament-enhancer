<?php

namespace Andreg\FilamentEnhancer\Resources\Pages\Traits;

trait RedirectsToParent {

	protected function getRedirectUrl(): string {
		return $this->getParentResource()::getUrl( 'edit', [ 'record' => $this->getParentRecord() ] );
	}

}
