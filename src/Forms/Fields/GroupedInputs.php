<?php

namespace Andreg\FilamentEnhancer\Forms\Fields;

use Closure;
use Filament\Schemas\Components\Fieldset;
use Illuminate\Contracts\Support\Htmlable;

class GroupedInputs extends Fieldset {

	/**
	 * @param  array<string, ?int> | int | null  $columns
	 */
	public static function make( Htmlable | Closure | string | null $label = null ): static {
		$static = parent::make( $label );
		$static->extraAttributes( [ 'class' => join( ' ', [
			'fe-grouped-inputs',
			'isolate',
			'pt-2',
			'[&>.fi-sc]:gap-[1px]',
			'[&_.fi-fo-field-label-col]:hidden',
			'[&_.fi-input-wrp:focus-within]:z-1',
		] ) ] );
		$static->contained( false );

		return $static;
	}

	/**
	 * @param  array<Component | Action | ActionGroup | string | Htmlable> | Closure  $components
	 */
	public function schema( array | Closure $components ): static {
		$components          = $this->evaluate( $components );
		$currentColumnSpan   = [];
		$totalRows           = [];
		$groupedInputColumns = $this->getColumns();

		if ( ! isset( $groupedInputColumns[ 'default' ] ) ) {
			$groupedInputColumns[ 'default' ] = 1;
		}

		foreach ( $groupedInputColumns as $breakpoint => $breakpointColumns ) {
			foreach ( $components as $component ) {
				if ( ! isset( $totalRows[ $breakpoint ] ) ) {
					$totalRows[ $breakpoint ] = 0;
				}

				$totalRows[ $breakpoint ] += $component->getColumnSpan()[ $breakpoint ] ?? 1;
			}

			$totalRows[ $breakpoint ] = intval( ceil( $totalRows[ $breakpoint ] / $breakpointColumns ) );
		}

		foreach ( $groupedInputColumns as $breakpoint => $breakpointColumns ) {
			$row               = 1;
			$rowFirstComponent = true;
			$breakpointPrefix  = 'default' === $breakpoint ? '' : $breakpoint . ':';

			foreach ( $components as $component ) {
				/** @var Field $component */
				if ( method_exists( $component, 'getLabel' ) && method_exists( $component, 'getPlaceholder' ) ) {
					if ( ! $component->getPlaceholder() && $component->getLabel() ) {
						$component->placeholder( $component->getLabel() );
					}
				}

				$componentExtraClasses = isset( $component->getExtraAttributes()[ 'class' ] ) ? explode( ' ', $component->getExtraAttributes()[ 'class' ] ) : [];

				if ( ! isset( $currentColumnSpan[ $breakpoint ] ) ) {
					$currentColumnSpan[ $breakpoint ] = 0;
				}

				$currentColumnSpan[ $breakpoint ] += $component->getColumnSpan()[ $breakpoint ] ?? 1;

				if ( 1 === $row ) {
					if ( $rowFirstComponent || $component->getColumnSpan()[ $breakpoint ] === $breakpointColumns ) {
						$componentExtraClasses = array_merge( $componentExtraClasses, [ $breakpointPrefix . 'rounded-ss-lg' ] );
					}

					if ( $currentColumnSpan[ $breakpoint ] === $breakpointColumns ) {
						$componentExtraClasses = array_merge( $componentExtraClasses, [ $breakpointPrefix . 'rounded-se-lg' ] );
					}
				}

				if ( $row === $totalRows[ $breakpoint ] ) {
					if ( $rowFirstComponent || $component->getColumnSpan()[ $breakpoint ] === $breakpointColumns ) {
						$componentExtraClasses = array_merge( $componentExtraClasses, [ $breakpointPrefix . 'rounded-es-lg' ] );
					}

					if ( $currentColumnSpan[ $breakpoint ] === $breakpointColumns ) {
						$componentExtraClasses = array_merge( $componentExtraClasses, [ $breakpointPrefix . 'rounded-ee-lg' ] );
					}
				}

				$component->extraAttributes( [
					'class' => join( ' ', [
						...$componentExtraClasses,
						...$this->getSuppressedBorderRadiusClasses( $componentExtraClasses, $breakpoint ),
					] ),
				] );

				$rowFirstComponent = false;

				if ( $currentColumnSpan[ $breakpoint ] >= $breakpointColumns ) {
					$currentColumnSpan[ $breakpoint ] = 0;
					$row++;
					$rowFirstComponent = true;
				}
			}
		}

		$static = parent::schema( $components );

		return $static;
	}

	private function getSuppressedBorderRadiusClasses( array $classes, string $breakpoint ): array {
		$breakpointPrefix = 'default' === $breakpoint ? '' : $breakpoint . ':';

		$borderRadiusClasses = [
			$breakpointPrefix . 'rounded-ss-none',
			$breakpointPrefix . 'rounded-se-none',
			$breakpointPrefix . 'rounded-es-none',
			$breakpointPrefix . 'rounded-ee-none',
		];

		foreach ( $borderRadiusClasses as $borderRadiusClassIndex => $borderRadiusClass ) {
			$trimmedBorderRadiusClass = str_replace( '-none', '', $borderRadiusClass );

			if ( in_array( $trimmedBorderRadiusClass . '-lg', $classes ) ) {
				unset( $borderRadiusClasses[ $borderRadiusClassIndex ] );
			}
		}

		return $borderRadiusClasses;
	}

}

// Classes for Tailwind to pick up and generate:
// rounded-ss-lg
// rounded-ss-none
// rounded-se-none
// rounded-se-lg
// rounded-es-none
// rounded-es-lg
// rounded-ee-none
// rounded-ee-lg
// lg:rounded-ss-lg
// lg:rounded-ss-none
// lg:rounded-se-none
// lg:rounded-se-lg
// lg:rounded-es-none
// lg:rounded-es-lg
// lg:rounded-ee-none
// lg:rounded-ee-lg
