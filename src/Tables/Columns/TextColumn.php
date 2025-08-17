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

					$badgeClasses = [
						'fi-color',
						'fi-color-' . ( ! empty( $badge[ 'color' ] ) ? $badge[ 'color' ] : 'primary' ),
						'fi-text-color-700',
						'fi-badge',
						'fi-size-sm',
					];

					$state .= ' <span style="margin-inline-start: calc(var(--spacing) * 1.5); margin-block: calc(var(--spacing) * -1);" class="' . implode( ' ', $badgeClasses ) . '">' . $badge[ 'label' ] . '</span>';
				}
			}

			return new HtmlString( $state );
		} );
	}

}
