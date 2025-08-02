<?php

namespace Andreg\FilamentEnhancer\Resources\Pages\Traits;

trait RedirectsToParent {

	protected function getRedirectUrl(): string {
		if ( $this->previousUrl ) {
			return $this->previousUrl;
		}

		return $this->getParentResource()::getUrl( 'edit', [
			'record' => $this->getParentRecord(),
		], shouldGuessMissingParameters: true );
	}

}
