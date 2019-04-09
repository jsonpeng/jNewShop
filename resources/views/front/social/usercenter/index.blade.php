@extends('front.social.layout.base')

@section('css')
    <style>
        .weui-grid{width: 25%;}
        .user-zone .weui-cell{border-bottom:1px solid #e0e0e0;}
        .weui-cell .weui-cell__bd{color: #000;font-weight: bold;}
        .userInfo{background-image: url('images/social/usercenter.jpg');background-repeat: no-repeat;background-position: center;background-size: cover;box-sizing: border-box;padding-bottom: 15px;}
        .userInfo .weui-media-box_appmsg{margin-top:15px;}
        .app-wrapper .userInfo .weui-media-box .weui-media-box__desc span{color: #fff;}
        .userInfo .line{margin: 0 15px;}
        .nav-top a .icon{color: #fff;font-size:30px;}
        .nav-top .weui-cell__bd{text-align: right;}
    </style>
@endsection

@section('content')

    <div class="userInfo">
        {{-- <div class="weui-cell nav-top">
            <a href="javascript:;" class="weui-cell__hd">
                <img class="mail" id="mail" src="{{ asset('images/social/mail.png') }}">
            </a>
            <a class="weui-cell__bd" href="javascript:;">
                <i class="icon ion-ios-gear-outline"></i>
            </a>
        </div> --}}

        <div class="weui-media-box weui-media-box_appmsg">
            <div class="weui-media-box__hd">
                <div class="userImg">
                    <img src="{{ $user->head_image }}" alt="">
                </div>
            </div>
            <div class="weui-media-box__bd">
                <div class="weui-media-box__title">
                    @if(funcOpen('FUNC_MEMBER_LEVEL') && !empty($userLevel))
                        <div class="menber">
                            <img src="{{ asset('images/vip.png') }}" alt="">
                        </div>
                    @endif
                    <div class="name">{{ $user->nickname }}</div>
                </div>
                <div class="weui-media-box__desc">
              {{--       @if(funcOpen('FUNC_CREDITS'))<a href="/usercenter/credits"><span>{{ getSettingValueByKeyCache('credits_alias') }}：</span><span>{{ $user->credits }}</span></a>@endif 
                    @if(funcOpen('FUNC_CREDITS') && funcOpen('FUNC_FUNDS'))<span class="line">|</span>@endif  --}}
                    @if(funcOpen('FUNC_FUNDS'))<a href="/usercenter/blances"><span>我的收益：{!! getSettingValueByKeyCache('price_fuhao') !!}</span><span>{{ $user->user_money }}</span></a>@endif
                </div>
            </div>
        </div>

    </div>
    <div class="weui-cells section-margin">
        <a class="weui-cell weui-cell_access" href="/orders">
            <div class="weui-cell__bd">
                <p>我的订单</p>
            </div>
            <div class="weui-cell__ft">所有订单</div>
        </a>
        <div class="weui-grids index-function-grids">
            <a href="/orders/2" class="weui-grid">
                <div class="weui-grid__icon">
                    <img src="{{ asset('images/social/c1.png') }}" alt="">
                </div>
                <p class="weui-grid__label">待付款</p>
            </a>
            <a href="/orders/3" class="weui-grid">
                <div class="weui-grid__icon">
                    <img src="{{ asset('images/social/c2.png') }}" alt="">
                </div>
                <p class="weui-grid__label">待发货</p>
            </a>
            <a href="/orders/4" class="weui-grid">
                <div class="weui-grid__icon">
                    <img src="{{ asset('images/social/c3.png') }}" alt="">
                </div>
                <p class="weui-grid__label">待收货</p>
            </a>
            <a href="/orders/4" class="weui-grid">
                <div class="weui-grid__icon">
                    <img src="{{ asset('images/social/c4.png') }}" alt="">
                </div>
                <p class="weui-grid__label">交易成功</p>
            </a>
        </div>
    </div>


    <div class="weui-cells section-margin user-zone">
        @if(funcOpen('FUNC_COUPON'))
        <a class="weui-cell weui-cell_access" href="/coupon">
            <div class="weui-cell__bd">
                <p>我的优惠券</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        @endif
        <a class="weui-cell weui-cell_access" href="/usercenter/collections">
            <div class="weui-cell__bd">
                <p>我的收藏</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <a class="weui-cell weui-cell_access" href="/address">
            <div class="weui-cell__bd">
                <p>地址管理</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>

        @if(!$user->code)
            <a class="weui-cell weui-cell_access" href="/sign_shop">
                <div class="weui-cell__bd">
                    <p>申请开店</p>
                </div>
                <div class="weui-cell__ft"></div>
            </a>
        @endif
<!--         <a class="weui-cell weui-cell_access" href="/integral/cert">
            <div class="weui-cell__bd">
                <p>实名认证</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a> -->
        
    </div>

    <div class="weui-cells section-margin user-zone">
        <a class="weui-cell weui-cell_access" href="javascript:;" onclick="openChange()">
            <div class="weui-cell__bd">
                <p>修改推荐人</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
    </div>
    
    @if(funcOpen('FUNC_DISTRIBUTION') && $user->code)
    <div class="weui-cells section-margin user-zone">
        <a class="weui-cell weui-cell_access" href="/usercenter/fellow">
            <div class="weui-cell__bd">
                <p>我的伙伴</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
        <a class="weui-cell weui-cell_access" href="/usercenter/bonus">
            <div class="weui-cell__bd">
                <p>分佣记录</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
   
 
   
        <a class="weui-cell weui-cell_access" href="/?rec_code={!! zcjy_base64_en($user->code) !!}">
            <div class="weui-cell__bd">
                <p>分享店铺</p>
            </div>
            <div class="weui-cell__ft"></div>
        </a>
    </div>
    @endif

    @include(frontView('layout.nav'), ['tabIndex' => 4])
@endsection


@section('js')
 <script type="text/template" id="change_man">
        <div style="text-align: center;">
          <div style="font-size: 16px;padding-bottom: 15px;">
              修改推荐人
          </div>
          <input type="text" name="code" class="form-control" placeholder="请输入店主邀请码" />
          <div style="padding-top:15px;font-size: 14px;color: red;">平台用户自注册起,最多可以更换绑定修改推荐人3次,请谨慎选择</div>
      </div>
  </script>
  <script type="text/javascript">
      function openChange()
      {
          layer.open({
          content: $('#change_man').html()
          ,btn: ['确定', '取消']
          ,yes: function(index){
              var code = $('input[name=code]').val();
              if($.empty(code))
              {
                alert('请输入店主邀请码');
                return;
              }
              $.zcjyRequest('/ajax/obzy/edit_leader/'+code,function(res){
              if(res){
                  alert(res);
                   //ajax请求
                  layer.close(index);
              }
            });
          }
          ,no: function(index){
            layer.close(index);
          }
          });
      }
  </script>
@endsection