<!-- Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('code', '代码(最多五位数,不能重复):') !!}
    {!! Form::text('code', null, ['class' => 'form-control','maxlength'=>5]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('codes.index') !!}" class="btn btn-default">返回</a>
</div>
