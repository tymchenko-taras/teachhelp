<form class="klsjdfb" action="/user" method="post">
    {{ csrf_field() }}

    regular_continuous_verb regular_past_verb irregular_past_verb irregular_participle_verb irregular_equal_verb

    <div class="row">

        <div class="col-md-12">
            <input type="text" name="searchword" value="{{$searchword}}">
            <input type="submit">
        </div>

        <br>

        <div class="col-md-6">
            <br>
            <div>What?</div>
            @foreach($patterns as $pattern)
                <div>
                    <label>
                        <input type="checkbox" name="pattern[{{$pattern['id']}}]" value="1">
                        <span>{{$pattern['name']}}</span>
                    </label>
                </div>
            @endforeach
        </div>

        <div class="col-md-6">
            <br>
            <div>For who?</div>
            @foreach($groups as $group)
                <div data-groups>
                    <label>
                        <input type="checkbox" name="group[{{$group -> id}}]" value="1">
                        <span>{{$group -> name}}</span>
                    </label>
                </div>
            @endforeach
        </div>

    </div>


</form>



<div class="result">
    {{$result}}
</div>