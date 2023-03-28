@section('title', 'Authorization Request - ' . config('app.name'))
<x-guest-layout>
	<div class="card w-96 bg-base-100 shadow-xl dark:text-white text-black">
		<div class="card-body">
			<h2 class="card-title">Authorization Request</h2>
			<p class="leading-tight">
				<strong>{{ $client->name }}</strong> is requesting permission to access your account.
			</p>
			<!-- Scope List -->
			@if (count($scopes) > 0)
				<div class="scopes mt-4">
					<p><strong>This application will be able to:</strong></p>

					<ul class="list-disc">
						@foreach ($scopes as $scope)
							<li class="list-inside">{{ $scope->description }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<div class="mt-2 card-actions buttons flex flex-row-reverse ">
				<!-- Authorize Button -->
				<form method="post" action="{{ route('passport.authorizations.approve') }}">
					@csrf
					<input type="hidden" name="state" value="{{ $request->state }}">
					<input type="hidden" name="client_id" value="{{ $client->getKey() }}">
					<input type="hidden" name="auth_token" value="{{ $authToken }}">
					<button type="submit" class="btn btn-success">Authorize</button>
				</form>

				<!-- Cancel Button -->
				<form method="post" action="{{ route('passport.authorizations.deny') }}">
					@csrf
					@method('DELETE')
					<input type="hidden" name="state" value="{{ $request->state }}">
					<input type="hidden" name="client_id" value="{{ $client->getKey() }}">
					<input type="hidden" name="auth_token" value="{{ $authToken }}">
					<button class="btn btn-ghost">Cancel</button>
				</form>
			</div>
		</div>
	</div>
</x-guest-layout>
