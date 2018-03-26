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



<div class="result">
    {{$result}}
</div>