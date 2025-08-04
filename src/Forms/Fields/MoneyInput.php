<?php

namespace Andreg\FilamentEnhancer\Forms\Fields;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

class MoneyInput extends TextInput {

	protected string $currency = 'USD';

	public function currency( string $currency ): static {
		$this->currency = $currency;

		return $this;
	}

	protected function setUp(): void {
		parent::setUp();

		$locale    = app()->getLocale();
		$formatter = new \NumberFormatter( $locale, \NumberFormatter::DECIMAL );
		$formatter->setAttribute( \NumberFormatter::FRACTION_DIGITS, 2 );

		$this->currency( config( 'enhancer.currency', $this->currency ) );

		$this->prefix( function () {
			$formatter = new \NumberFormatter( 'en', \NumberFormatter::CURRENCY );
			$formatted = $formatter->formatCurrency( 0, $this->currency );

			// Fallback: extract non-numeric, non-space, non-minus characters from formatted string
			if ( preg_match( '/([^\d\s\-.,]+)/u', $formatted, $matches ) ) {
				return $matches[ 1 ];
			}

			// Final fallback: return the currency code
			return $this->currency;
		} );

		$this->minValue( 0 );
		$this->live( onBlur: true );

		$this->formatStateUsing( function ( $state ) use ( $formatter ) {
			return $formatter->format( floatval( $state ) );
		} );

		$this->afterStateUpdated( function ( $state, Field $component ) use ( $formatter ) {
			$component->state( $formatter->format( floatval( $state ) ) );
		} );

		$this->dehydrateStateUsing( function ( $state ) use ( $formatter ) {
			$state = str_replace( $formatter->getSymbol( \NumberFormatter::GROUPING_SEPARATOR_SYMBOL ), '', $state );
			$state = str_replace( $formatter->getSymbol( \NumberFormatter::DECIMAL_SEPARATOR_SYMBOL ), '.', $state );

			return (float) $state;
		} );

		$this->mask( function () use ( $formatter ) {
			return RawJs::make(
				strtr(
					'$money($input, \'{decimalSeparator}\', \'{groupingSeparator}\', {fractionDigits})',
					[
						'{decimalSeparator}'  => $formatter->getSymbol( \NumberFormatter::DECIMAL_SEPARATOR_SYMBOL ),
						'{groupingSeparator}' => $formatter->getSymbol( \NumberFormatter::GROUPING_SEPARATOR_SYMBOL ),
						'{fractionDigits}'    => $formatter->getAttribute( \NumberFormatter::FRACTION_DIGITS ),
					]
				)
			);
		} );
	}

}
