@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Shop Times
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('shop_times.show_fields')
                    <a href="{!! route('shopTimes.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
