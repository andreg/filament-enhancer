<?php

namespace Andreg\FilamentEnhancer\Forms\Components;

use Closure;
use Filament\Infolists\Components\Entry;

class ProgressEntry extends Entry {

	protected string $view = 'filament-enhancer::components.progress-entry';

	protected int | float | Closure $value = 0;

	protected int | float | Closure $max = 0;

	public function value( int | float | Closure $value ): static {
		$this->value = $value;

		return $this;
	}

	public function max( int | float | Closure $max ): static {
		$this->max = $max;

		return $this;
	}

	public function getState(): array {
		return [
			'value' => $this->evaluate( $this->value ),
			'max'   => $this->evaluate( $this->max ),
		];
	}

}
