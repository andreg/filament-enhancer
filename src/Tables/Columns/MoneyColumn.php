<?php

namespace Andreg\FilamentEnhancer\Tables\Columns;

use Closure;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;

class MoneyColumn extends \Filament\Tables\Columns\TextColumn {

	protected string $currency = 'USD';
	protected int $divideBy    = 1;

	public function currency( string $currency ): static {
		$this->currency = $currency;

		return $this;
	}

	public function divideBy( int $divideBy ): static {
		$this->divideBy = $divideBy;

		return $this;
	}

	/**
	 * @param  bool | array<string> | Closure  $condition
	 */
	public function sortable( bool | array | Closure $condition = true, ?Closure $query = null ): static {
		if ( null !== $query || is_array( $condition ) ) {
			return parent::sortable( $condition, $query );
		}

		return parent::sortable( $condition, $this->numericSortQuery() );
	}

	protected function setUp(): void {
		parent::setUp();

		$this->currency( config( 'filament-enhancer.currency', $this->currency ) );
		$this->numeric();
		$this->weight( FontWeight::SemiBold );
		$this->money( $this->currency, divideBy: function () {
			return $this->divideBy;
		} );
		// $this->sortable();
	}

	protected function numericSortQuery(): Closure {
		return function ( Builder $query, string $direction ): Builder {
			$direction = 'desc' === strtolower( $direction ) ? 'desc' : 'asc';
			$column    = $this->getName();

			if ( str_contains( $column, '.' ) ) {
				foreach ( array_reverse( $this->getSortColumns( $query->getModel() ) ) as $sortColumn ) {
					$query->orderBy( $sortColumn, $direction );
				}

				return $query;
			}

			$qualified = $query->getModel()->qualifyColumn( $column );
			$driver    = $query->getConnection()->getDriverName();
			$wrapped   = $query->getGrammar()->wrap( $qualified );

			$expression = match ( $driver ) {
				'mysql', 'mariadb' => "CAST({$wrapped} AS DECIMAL(65, 30))",
				'pgsql', 'sqlite'  => "CAST({$wrapped} AS NUMERIC)",
				default            => $wrapped,
			};

			return $query->orderByRaw( "{$expression} {$direction}" );
		};
	}

}
