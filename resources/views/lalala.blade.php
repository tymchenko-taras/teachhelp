<form action="/user" method="post">
	{{ csrf_field() }}
	<input type="text" name="searchword" value="{{$searchword}}">
	<input type="submit">
</form>

@if ($result)
{{ dump($result) }}
@endif
