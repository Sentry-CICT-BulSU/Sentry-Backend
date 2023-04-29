@section('title', 'Authorization Request - ' . config('app.name'))
<x-guest-layout>
	<x-slot:header>
		<div class="dark:text-white text-black flex justify-between text-sm">
			<p class="">Logged in as:</p>
			<p class="">{{ $user->full_name }} | {{ $user->email }}</p>
		</div>
		</x-slot>

		<div class="dark:text-white text-black">
			<h2 class="font-semibold text-xl">Authorization Request</h2>
			<p class="leading-tight my-4">
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
			<div class="mt-2  buttons flex flex-row-reverse justify-between">
				<div class="flex flex-row-reverse gap-x-1">
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
						<button type="submit" class="btn btn-ghost">Cancel</button>
					</form>
				</div>

				<!-- Logout Button -->
				<form method="post" action="{{ route('logout') }}" class=" ">
					@csrf
					<input type="hidden" name="redirect" value="api">
					<button type="submit" class="btn btn-ghost">Logout</button>
				</form>
			</div>
		</div>
</x-guest-layout>
