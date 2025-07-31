<?php

namespace Andreg\FilamentEnhancer\Providers\Traits;

use Filament\Tables\Table;

trait HandlesTables {

	public function createTableMacros() {
		Table::macro( 'forcePagination', function ( int $limit = 20 ): Table {
			/** @var Table $this */
			$this->paginationPageOptions( [ $limit ] );
			$this->defaultPaginationPageOption( $limit );

			return $this;
		} );
	}

}
