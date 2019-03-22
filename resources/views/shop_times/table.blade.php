<table class="table table-responsive" id="shopTimes-table">
    <thead>
        <tr>
            <th>User Id</th>
        <th>Shoper Id</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($shopTimes as $shopTimes)
        <tr>
            <td>{!! $shopTimes->user_id !!}</td>
            <td>{!! $shopTimes->shoper_id !!}</td>
            <td>
                {!! Form::open(['route' => ['shopTimes.destroy', $shopTimes->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('shopTimes.show', [$shopTimes->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('shopTimes.edit', [$shopTimes->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>