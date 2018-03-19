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


	@foreach($params as $id => $param)
		<div data-id="{{$id}}" class="draggable drag-drop">{{$param['pretty']}}</div>
	@endforeach

	<div id="outer-dropzone" class="dropzone">

</form>

</div>


<div class="result">
	{{$result}}
</div>



</body>
</html>
