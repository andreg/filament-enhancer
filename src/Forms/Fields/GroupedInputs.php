<?php

namespace Andreg\FilamentEnhancer\Forms\Fields;

use Closure;
use Filament\Schemas\Components\Grid;

class GroupedInputs extends Grid {

	/**
	 * @param  array<string, ?int> | int | null  $columns
	 */
	public static function make( array | int | null $columns = 2 ): static {
		$static = parent::make( $columns );
		$static->extraAttributes( [ 'class' => 'uuu' ] );

		return $static;
	}

	/**
	 * @param  array<Component | Action | ActionGroup | string | Htmlable> | Closure  $components
	 */
	public function schema( array | Closure $components ): static {
		$columnStart = 1;

		$components = array_map( function ( $component ) use ( &$columnStart ) {
			if ( ! $component->getPlaceholder() ) {
				$component->placeholder( $component->getLabel() );
			}

			switch ( $columnStart ) {
				case 1:
					$component->extraAttributes( [ 'class' => 'row-start' ] );

					break;
				case $this->getColumns():
					$component->extraAttributes( [ 'class' => 'row-end' ] );

					break;
			}

			$columnStart += $component->getColumnSpan()[ 'default' ];

			if ( $columnStart > $this->getColumns() ) {
				$columnStart = 1;
			}

			return $component;
		}, (array) $this->evaluate( $components ) );

		$static = parent::schema( $components );

		return $static;
	}

}
