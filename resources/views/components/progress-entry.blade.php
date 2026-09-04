<x-dynamic-component
	:component="$getEntryWrapperView()"
	:entry="$entry"
>
	@php
		$state = $getState();
		$max = 100;
		$value = 0;

		if (is_array($state)) {
			$value = (float) ($state['value'] ?? $state['current'] ?? 0);
			$max = max(1.0, (float) ($state['max'] ?? 100));
		} elseif (is_numeric($state)) {
			$value = (float) $state;
		}

		$percent = min(100, max(0, ($value / $max) * 100));
	@endphp

	<div
		{{
			$getExtraAttributeBag()->class([
				'fe-progress-entry',
			])
		}}
	>
		<p>{{ $value }} / {{ $max }}</p>
		<div
			class="fe-progress-entry-track"
			role="progressbar"
			aria-valuemin="0"
			aria-valuemax="{{ $max }}"
			aria-valuenow="{{ $value }}"
		>
			<div
				class="fe-progress-entry-bar"
				style="width: {{ $percent }}%"
			></div>
		</div>
	</div>
</x-dynamic-component>
