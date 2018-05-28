@if ($matches)
	<table class="table table-hover">
		<thead>
		<tr>
			<th> id </th>
			<th> content</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
		@foreach($matches as $match)
			<tr>
				<td> {{$match['id']}} </td>
				<td> {!! $match['content'] !!} </td>
				<td>
					<label class="switch">
						<input type="checkbox" data-id="{{$match['id']}}" @if (is_null($match['value'])) data-assigned="0" @else data-assigned="1" @endif @if ($match['value']) checked="checked"@endif>
						<span class="slider round"></span>
					</label>
				</td>
                <td>
                    <textarea data-id="{{$match['id']}}">{{$match['comment']}}</textarea>
                </td>
			</tr>
		@endforeach
		</tbody>
	</table>
@endif