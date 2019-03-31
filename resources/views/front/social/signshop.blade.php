@extends('front.social.layout.base')

@section('css')
<style type="text/css">
.weui-actionsheet {
    bottom: 30%;
    padding-top: 20px;
}
.obzy_btn {
    float: right;
    margin-right: 45px;
}
</style>
@endsection

@section('title')
@endsection

@section('content')
<div class="weui-mask" id="iosMask" style="display: none"></div>

<div class="weui-actionsheet bind_mobile" id="bindMobileBox">
        <div class="container" style="padding-bottom: 0;">
            <div class="f14" style="text-align: center;">为保护您的账号安全,请先验证电话号码</div>
            <div class="weui-cell mt10">
                <div class="weui-cell__bd" style="-webkit-flex: none;">
                    <input class="weui-input weui-input-set" type="text" name="mobile" maxlength="11" placeholder="请输入手机号">
                </div>
            </div>
            <div class="weui-cell mt10">
                <div class="weui-cell__bd" style="position: relative;-webkit-flex: none;">
                    <input class="weui-input weui-input-set" type="text" name="code" maxlength="11" placeholder="请输入验证码">
                    <a class="f14 getCode" data-abled="1" onclick="getCodeFunc(this)">获取验证码</a>
                </div>
            </div>
            <a class="obzy_btn bind_mobile_btn" href="javascript:;" onclick="bindMobileFunc()">确定</a>
        </div>
</div>

<div class="weui-actionsheet enter_code hidden" id="enterCodeBox">
        <div class="container" style="padding-bottom: 0;">
            <div class="f14" style="text-align: center;">请输入5位代码开通店铺</div>
            <div class="weui-cell mt10">
                <div class="weui-cell__bd" style="-webkit-flex: none;">
                    <input class="weui-input weui-input-set" type="text" name="code" maxlength="5" placeholder="请输入5位代码开通店铺">
                </div>
            </div>
            <a class="obzy_btn enter_code_btn" href="javascript:;" onclick="enterCodeBtn()">确定</a>
        </div>
</div>
@endsection


@section('js')
<script type="text/javascript">
  var $iosMask = $('#iosMask');
    //先绑定手机号
  $(function(){
    openMobileBind();
     //$.zcjyFrameOpen($('.bind_mobile').html());
  });

  function openMobileBind()
  {
      $('#bindMobileBox').addClass('weui-actionsheet_toggle');
      $iosMask.fadeIn(200);
  }

   function hideActionSheet() {
      $('#bindMobileBox').removeClass('weui-actionsheet_toggle').addClass('hidden');
        $iosMask.fadeOut(200);
    }

  var wait=60;
    function time() {
            var o = $('.getCode');
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
    function getCodeFunc(obj){
        var mobile = $('input[name=mobile]').val(); 
        if($.empty(mobile)){
          alert('请先输入手机号');
          return false;
        }
        if($(obj).data('abled')){
          $.zcjyRequest('/ajax/obzy/send_code',function(res){
              if(res){
                  time();
              }
          },{mobile:mobile});
        }
   }
  //点击绑定手机号
  function bindMobileFunc(){
        var mobile = $('input[name=mobile]').val(); 
        if($.empty(mobile)){
          alert('请先输入手机号');
          return false;
        }
        var code = $('input[name=code]').val(); 
        if($.empty(code)){
          alert('请先输入验证码');
          return false;
        }
        $.zcjyRequest('/ajax/obzy/bind_mobile',function(res){
              if(res){
                  alert(res);
                  hideActionSheet();
                  $('#enterCodeBox').addClass('weui-actionsheet_toggle').removeClass('hidden');
                  // layer.closeAll();
                  // $.zcjyFrameOpen($('.enter_code').html());
              }
        },{mobile:mobile,code:code});
  }

  //点击输入代码
  function enterCodeBtn(){
        var code = $('input[name=code]:eq(1)').val(); 
        if($.empty(code)){
          alert('请先输入代码');
          return false;
        }
        $.zcjyRequest('/ajax/obzy/bind_code',function(res){
              if(res){
                  alert(res);
              }
        },{code:code});
  }
</script>

@endsection