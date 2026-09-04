<?php

namespace Andreg\FilamentEnhancer\Tables\Columns;

use Closure;
use Filament\Tables\Columns\Column;

class ProgressColumn extends Column {

	protected string $view = 'filament-enhancer::components.progress-column';

	protected int | float | Closure $value = 0;

	protected int | float | Closure $maximum = 0;

	public function value( int | float | Closure $value ): static {
		$this->value = $value;

		return $this;
	}

	/**
	 * Progress ceiling. Named maximum() because Column::max() is reserved for aggregate queries.
	 */
	public function maximum( int | float | Closure $maximum ): static {
		$this->maximum = $maximum;

		return $this;
	}

	public function getState(): array {
		return [
			'value' => $this->evaluate( $this->value ),
			'max'   => $this->evaluate( $this->maximum ),
		];
	}

}
