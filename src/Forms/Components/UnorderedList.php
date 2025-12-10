<?php

namespace Andreg\FilamentEnhancer\Forms\Components;

use Filament\Schemas\Components\UnorderedList as FilamentUnorderedList;

class UnorderedList extends FilamentUnorderedList {

	private int $renderColumns = 1;

	protected function setUp(): void {
		parent::setUp();

		$this->extraAttributes( [ 'class' => implode( ' ', [
			'fe-unordered-list',
			'sm:columns-' . $this->renderColumns,
		] ) ] );
	}

	public function renderColumns( int $columns ): static {
		$this->renderColumns = $columns;
		$classes             = explode( ' ', $this->getExtraAttributes()[ 'class' ] ?? '' );

		$classes = array_filter( $classes, function ( $class ) {
			return ! str_starts_with( $class, 'sm:columns-' );
		} );

		$classes[] = 'sm:columns-' . $columns;

		$this->extraAttributes( [ 'class' => implode( ' ', $classes ) ] );

		return $this;
	}

}

// sm:columns-1
// sm:columns-2
// sm:columns-3
