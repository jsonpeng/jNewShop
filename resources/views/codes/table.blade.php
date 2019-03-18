<table class="table table-responsive" id="codes-table">
    <thead>
        <tr>
            <th>代码</th>
            <th>使用状态</th>
            {{-- <th colspan="3">操作</th> --}}
        </tr>
    </thead>
    <tbody>
    @foreach($codes as $code)
        <tr>
            <td>{!! $code->code !!}</td>
            <td>{!! $code->use ? '已使用' : '未使用' !!}</td>
      {{--       <td>
                {!! Form::open(['route' => ['codes.destroy', $code->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                   <!--  <a href="{!! route('codes.show', [$code->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('codes.edit', [$code->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a> -->
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td> --}}
        </tr>
    @endforeach
    </tbody>
</table>