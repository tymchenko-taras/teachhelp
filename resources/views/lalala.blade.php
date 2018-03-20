<!doctype html>
<html lang="en">
<head>
	<script src="/js/jquery-3.3.1.js"></script>
	<script src="/js/interact.js"></script>
	<script src="/js/drag.js"></script>
	<link href="/css/interact.css" rel="stylesheet" media="screen">
</head>

<body>


<form class="klsjdfb" action="/user" method="post">
	{{ csrf_field() }}
	<input type="text" name="searchword" value="{{$searchword}}">
	<input type="submit">


	@foreach($patterns as $pattern)
		<div>
			<label>
				<input type="checkbox" name="pattern[{{$pattern -> id}}]" value="1">
				<span>{{$pattern -> name}}</span>
			</label>
		</div>
	@endforeach


</form>

</div>


<div class="result">
	{{$result}}
</div>



</body>
</html>
