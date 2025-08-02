<?php

namespace Andreg\FilamentEnhancer\Tables\Columns;

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

		$this->currency( config( 'enhancer.currency', $this->currency ) );
		$this->isNumeric( true );
		$this->money( $this->currency, divideBy: function () {
			return $this->divideBy;
		} );
	}

}
