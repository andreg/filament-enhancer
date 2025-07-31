<?php

namespace Andreg\FilamentEnhancer\Resources\Pages\Traits;

trait RedirectsToIndex {

	protected function getRedirectUrl(): string {
		return $this->getResource()::getUrl( 'index' );
	}

}
