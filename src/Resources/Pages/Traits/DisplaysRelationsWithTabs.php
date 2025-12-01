<?php

namespace Andreg\FilamentEnhancer\Resources\Pages\Traits;

trait DisplaysRelationsWithTabs {

	public function hasCombinedRelationManagerTabsWithContent(): bool {
		return count( $this->getRelationManagers() ) > 0 ? true : false;
	}

}
