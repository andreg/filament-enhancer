<?php

namespace Andreg\FilamentEnhancer\Tables\Columns;

use Filament\Support\Enums\FontWeight;

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

	protected function setUp(): void {
		parent::setUp();

		$this->currency( config( 'filament-enhancer.currency', $this->currency ) );
		$this->isNumeric( true );
		$this->weight( FontWeight::SemiBold );
		$this->money( $this->currency, divideBy: function () {
			return $this->divideBy;
		} );
	}

}
