<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'User Id:') !!}
    {!! Form::text('user_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Shoper Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('shoper_id', 'Shoper Id:') !!}
    {!! Form::text('shoper_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('shopTimes.index') !!}" class="btn btn-default">Cancel</a>
</div>
