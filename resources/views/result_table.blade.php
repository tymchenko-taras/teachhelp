@if ($matches)
	<table class="table table-hover">
		<thead>
		<tr>
			<th> id </th>
			<th> content</th>
		</tr>
		</thead>
		<tbody>
		@foreach($matches as $match)
			<tr>
				<td> {{$match['id']}} </td>
				<td> {!! $match['content'] !!} </td>
			</tr>
		@endforeach
		</tbody>
	</table>
@endif