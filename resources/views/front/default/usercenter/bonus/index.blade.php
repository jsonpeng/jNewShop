@extends('front.default.layout.base')

@section('css')
    <style>
        .weui-grid{width: 25%;}
       	.credit-body .g-content .info .weui-flex__item{font-size: 13px;}
       	.credit .credit-body .g-title .weui-flex__item:last-child{flex: 3;}
       	.credit .credit-body .g-content{background-color: #fff;}
       	.credit .credit-body>div:nth-child(even){background-color:#fbfcfb;}
    </style>
@endsection

@section('content')
	{{-- <div class="nav_tip">
	  <div class="img">
	    <a href="javascript:history.back(-1)"><i class="icon ion-ios-arrow-left"></i></a></div>
	  <p class="titile">分佣记录</p>
	</div> --}}
    <div class="credit">
    	<div class="head">
    		<div class="intr">尊敬的会员您好，您的分佣总额为</div>
    		<div class="num"><span class="small-symbol">{{ getSettingValueByKeyCache('price_fuhao') }}  </span>{{ $user->distribut_money }}</div>
    	</div>
    	<div class="credit-body">
    		<div class="g-title weui-flex">
    			<div class="weui-flex__item">时间</div>
    			<div class="weui-flex__item">余额</div>
    			<div class="weui-flex__item">分佣</div>
    			<div class="weui-flex__item">说明</div>
    		</div>
            <div id="scroll-container">
        		@foreach ($moneyLogs as $moneyLog)

                    <div class="g-content scroll-post">
                        <div class="info weui-flex">
                            <div class="weui-flex__item">{{ $moneyLog->created_at->format('m-d') }}</div>
                            <div class="weui-flex__item">{{ $moneyLog->amount }}</div>
                            <div class="weui-flex__item">{{ $moneyLog->change }}</div>
                            <div class="weui-flex__item click-detail">查看详情</div>
                            <div class="weui-flex__item pic">
                                <img class="open" src="{{ asset('images/top.png') }}" alt="">
                                <img class="shut" src="{{ asset('images/bottom.png') }}" alt="">
                            </div>
                        </div>
                        <div class="detail-txt">
                            {{ $moneyLog->detail }}
                        </div>
                    </div>

                @endforeach
            </div>
    	</div>
    </div>
	
    @include(frontView('layout.nav'), ['tabIndex' => 4])
@endsection



@section('js')
    <script src="{{ asset('vendor/doT.min.js') }}"></script>

    <script type="text/template" id="template">
        @{{~it:value:index}}
            <div class="g-content scroll-post">
                <div class="info weui-flex">
                    <div class="weui-flex__item">@{{=value.created_at.substring(0,10)}}</div>
                    <div class="weui-flex__item">@{{=value.amount}}</div>
                    <div class="weui-flex__item">@{{=value.change}}</div>
                    <div class="weui-flex__item click-detail">查看详情</div>
                    <div class="weui-flex__item pic">
                        <img class="open" src="{{ asset('images/top.png') }}" alt="">
                        <img class="shut" src="{{ asset('images/bottom.png') }}" alt="">
                    </div>
                </div>
                <div class="detail-txt">
                    @{{=value.detail}}
                </div>
            </div>
        @{{~}}
    </script>

    <script type="text/javascript">

        $(document).ready(function(){
            //无限加载
            var fireEvent = true;
            var working = false;

            $(document).endlessScroll({

                bottomPixels: 250,

                fireDelay: 10,

                ceaseFire: function(){
                  if (!fireEvent) {
                    return true;
                  }
                },

                callback: function(p){

                  if(!fireEvent || working){return;}

                  working = true;

                  //加载函数
                  $.ajaxSetup({ 
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                  });
                  $.ajax({
                    url:"/ajax/bonus?skip=" + $('.scroll-post').length + "&take=18",
                    type:"GET",
                    success:function(data){
                      if (data.code != 0) {
                        return;
                      }

                      if (data.message.length == 0) {
                        fireEvent = false;
                        $('#scroll-container').append("<div id='loading-tips' style='padding: 15px; color: #999; font-size: 14px; text-align: center;'>别再扯了，已经没有了</div>");
                        return;
                      }

                      if (data.message.length) {
                          // 编译模板函数
                          var tempFn = doT.template( $('#template').html() );

                          // 使用模板函数生成HTML文本
                          var resultHTML = tempFn(data.message);

                          // 否则，直接替换list中的内容
                          $('#scroll-container').append(resultHTML);
                        } else {
                          
                        }
                        working = false;
                        }
                  });
                }
            });
        });
    </script>
@endsection