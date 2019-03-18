@extends('front.social.layout.base')

@section('css')
<style type="text/css">
 
    /*input::-webkit-input-placeholder{position：relative;left：20px;}*/
</style>
@endsection

@section('title')
@endsection

@section('content')
<div class="bind_mobile hidden">
        <div class="container" style="padding-bottom: 0;">
            <div class="f14">为保护您的账号安全,请先验证电话号码</div>
            <div class="weui-cell mt10">
                <div class="weui-cell__bd" style="-webkit-flex: none;">
                    <input class="weui-input weui-input-set" type="text" name="mobile" maxlength="11" placeholder="请输入手机号">
                </div>
            </div>
            <div class="weui-cell mt10">
                <div class="weui-cell__bd" style="position: relative;-webkit-flex: none;">
                    <input class="weui-input weui-input-set" type="text" name="code" maxlength="11" placeholder="请输入验证码">
                    <a class="f14 getCode" data-abled="1">获取验证码</a>
                </div>
            </div>
            <a class="obzy_btn bind_mobile_btn" href="javascript:;">确定</a>
        </div>
</div>

<div class="enter_code hidden">
        <div class="container" style="padding-bottom: 0;">
            <div class="f14">请输入5位代码开通店铺</div>
            <div class="weui-cell mt10">
                <div class="weui-cell__bd" style="-webkit-flex: none;">
                    <input class="weui-input weui-input-set" type="text" name="code" maxlength="5" placeholder="请输入5位代码开通店铺">
                </div>
            </div>
            <a class="obzy_btn enter_code_btn" href="javascript:;">确定</a>
        </div>
</div>
@endsection


@section('js')
<script type="text/javascript">

    //先绑定手机号
  $(function(){
     $.zcjyFrameOpen($('.bind_mobile').html());
  });



  var wait=60;
  function time() {
            var o = $('.getCode:eq(1)');
            if (wait == 0) {
                o.removeClass('disable');
                o.data("abled",1);   
                o.text("获取验证码");
                wait = 60;
            } 
            else { 
                o.addClass('disable');
                o.data("abled",0); 
                o.text("重新发送(" + wait + ")");
                wait--;
                setTimeout(function() {
                    time()
                }, 1000)
            }
    }
  //点击发送手机验证码
   $(document).on('click','.getCode',function(){
        var mobile = $('input[name=mobile]:eq(1)').val(); 
        if($.empty(mobile)){
          alert('请先输入手机号');
          return false;
        }
        if($(this).data('abled')){
          $.zcjyRequest('/ajax/obzy/send_code',function(res){
              if(res){
                  time();
              }
          },{mobile:mobile});
        }
   });
  //点击绑定手机号
  $(document).on('click','.bind_mobile_btn',function(){
        var mobile = $('input[name=mobile]:eq(1)').val(); 
        if($.empty(mobile)){
          alert('请先输入手机号');
          return false;
        }
        var code = $('input[name=code]:eq(2)').val(); 
        if($.empty(code)){
          alert('请先输入验证码');
          return false;
        }
        $.zcjyRequest('/ajax/obzy/bind_mobile',function(res){
              if(res){
                  alert(res);
                  layer.closeAll();
                  $.zcjyFrameOpen($('.enter_code').html());
              }
        },{mobile:mobile,code:code});
  });
  //点击输入代码
  $(document).on('click','.enter_code_btn',function(){
        var code = $('input[name=code]:eq(2)').val(); 
        if($.empty(code)){
          alert('请先输入代码');
          return false;
        }
        $.zcjyRequest('/ajax/obzy/bind_code',function(res){
              if(res){
                  alert(res);
              }
        },{code:code});
  });
</script>

@endsection