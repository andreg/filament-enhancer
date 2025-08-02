<?php

namespace Andreg\FilamentEnhancer\Resources\Pages\Traits;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

trait RedirectsToParent {

	protected function getRedirectUrl(): string {
		if ( $this->previousUrl ) {
			return $this->previousUrl;
		}

		$request         = Request::create( url()->previous(), 'GET' );
		$route           = Route::getRoutes()->match( $request );
		$routeParameters = $route->parameters();
		$routeParameters = array_slice( $routeParameters, 0, -1, true );

		return $this->getParentResource()::getUrl( 'edit', [
			'record' => $this->getParentRecord(),
			...$routeParameters,
		] );
	}

}
