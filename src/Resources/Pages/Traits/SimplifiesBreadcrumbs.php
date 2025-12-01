<?php

namespace Andreg\FilamentEnhancer\Resources\Pages\Traits;

trait SimplifiesBreadcrumbs {

	/**
	 * @return array<string>
	 */
	public function getBreadcrumbs(): array {
		$resourceBreadcrumbs = $this->getResourceBreadcrumbs();

		if ( count( array_keys( $resourceBreadcrumbs ) ) <= 2 ) {
			return $resourceBreadcrumbs;
		}

		foreach ( $resourceBreadcrumbs as $key => $breadcrumb ) {
			if ( str_contains( $key, 'relation=' ) ) {
				$keyIndex = array_search( $key, array_keys( $resourceBreadcrumbs ) );

				if ( isset( array_keys( $resourceBreadcrumbs )[ $keyIndex + 1 ], $resourceBreadcrumbs[ array_keys( $resourceBreadcrumbs )[ $keyIndex + 1 ] ] ) ) {
					$resourceBreadcrumbs[ array_keys( $resourceBreadcrumbs )[ $keyIndex + 1 ] ] = $breadcrumb . ': ' . $resourceBreadcrumbs[ array_keys( $resourceBreadcrumbs )[ $keyIndex + 1 ] ];
					unset( $resourceBreadcrumbs[ $key ] );
				}
			}
		}

		$lastKey = array_keys( $resourceBreadcrumbs )[ count( $resourceBreadcrumbs ) - 1 ];

		if ( true === method_exists( $this, 'getRecordTitle' ) ) {
			$resourceBreadcrumbs[ $lastKey ] .= ': ' . $this->getRecordTitle();
			$resourceBreadcrumbs[] .= $resourceBreadcrumbs[ $lastKey ];
		}

		unset( $resourceBreadcrumbs[ $lastKey ] );

		return $resourceBreadcrumbs;
	}

}
