<?php

namespace Andreg\FilamentEnhancer\Tables\Columns;

use Illuminate\Support\HtmlString;

class TextColumn extends \Filament\Tables\Columns\TextColumn {

	protected array $badges = [];

	public function badges( array $badges ): static {
		$this->badges = $badges;

		return $this;
	}

	protected function setUp(): void {
		parent::setUp();

		$this->extraCellAttributes( [
			'class' => 'enhanced-text-column',
		] );
		$this->formatStateUsing( function ( $state, $record ) {
			if ( ! empty( $this->badges ) ) {
				foreach ( $this->badges as $badge ) {
					if ( isset( $badge[ 'condition' ] ) && ! $badge[ 'condition' ]( $record ) ) {
						continue;
					}

					$state .= ' <span class="fi-color fi-color-' . $badge[ 'color' ] . ' fi-text-color-600 dark:fi-text-color-200 fi-badge fi-size-sm enhanced-badge">' . $badge[ 'label' ] . '</span>';
				}
			}

			return new HtmlString( $state );
		} );
	}

}
