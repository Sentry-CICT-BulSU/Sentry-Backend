@extends('pdf.Layouts.index')
@section('content')
	<div>
		@foreach (collect($reports)->chunk(18) as $rpts)
			<div class="page-{{ $loop->iteration }}">
				<table style="width: 100%;position: absolute; top: 10px;">
					<tr>
						<td>
							<div class="" style="text-align: left; margin-left: 280px; margin-top: -20px">
								<img class="img_head" src="{{ Vite::images('bulsu_logo.png') }}" alt="bulsu_logo">
							</div>
						</td>
						<td>
							<p class="text-center" style="text-align: center; font-weight: bold">
								College of Information and Communications Technology
								<br>
								Bulacan State University
							</p>
						</td>
						<td>
							<div class="" style="text-align: right; margin-right: 280px; margin-top: -20px">
								<img class="img_head" src="{{ Vite::images('cict_logo.png') }}" alt="bulsu_logo">
							</div>
						</td>
					</tr>
				</table>
				<div class="header" style="margin-top: 100px">
					<div class="header_p">
						<h1><strong>Attendance Report</strong></h1>
					</div>
				</div>
				<div class="body">
					<div class="body_table">
						<table>
							<thead class="text-center">
								@foreach ($headings as $heading)
									<th class="text-center boder-override font">{{ $heading }}</th>
								@endforeach
							</thead>
							<tbody class="text-center">
								{{-- @foreach (range(1, 18) as $report)
									<tr>
										<td>{{ 'test' }}</td>
										<td>{{ 'test' }}</td>
										<td>{{ 'test' }}</td>
										<td>{{ 'test' }}</td>
										<td>{{ 'test' }}</td>
										<td>{{ 'test' }}</td>
										<td>{{ 'test' }}</td>
										<td>{{ 'test' }}</td>
										<td>{{ 'test' }}</td>
										<td>{{ 'test' }}</td>
									</tr>
								@endforeach --}}
								@foreach ($rpts as $report)
									<tr>
										<td>{{ $report['semesters.name'] }}</td>
										<td>{{ $report['semesters.academic_year'] }}</td>
										<td>{{ $report['users.first_name'] }}</td>
										<td>{{ $report['users.last_name'] }}</td>
										<td>{{ $report['users.email'] }}</td>
										<td>{{ $report['attendances.status'] }}</td>
										<td>{{ $report['attendances.created_at'] }}</td>
										<td>{{ $report['rooms.name'] }}</td>
										<td>{{ $report['subjects.code'] }}</td>
										<td>{{ $report['subjects.title'] }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<table style="width: 100%;position: absolute; bottom: 20px;">
						<tr>
							<td>
								<div class="" style="text-align: left;">
									<div>Page {{ $loop->iteration }} of {{ $loop->count }}</div>
								</div>
							</td>
							<td>
								<div class="" style="text-align: right;">
									<div>Generated on: {{ now()->format('F d, o - h:i A') }}</div>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		@endforeach
	</div>
@endsection
