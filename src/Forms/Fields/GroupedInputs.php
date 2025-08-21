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
	// public function schema( array | Closure $components ): static {
	// 	$static = parent::schema( $components );

	// 	return $static;
	// }

}
