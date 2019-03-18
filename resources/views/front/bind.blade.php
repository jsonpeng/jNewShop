{{-- 推荐人设置绑定 --}}
@if(app('commonRepo')->varifyUserBindTMan())
<?php 
$leader = app('commonRepo')->userLeader();
$leaderImage = optional($leader)->head_image;
$leaderNickname = optional($leader)->nickname;
$leaderId = optional($leader)->id;
 ?>
<script type="text/template" id="confirm_man">
    <div style="text-align: center;">
        <div style="font-size: 16px;padding-bottom: 15px;">
            确认推荐人
        </div>
        <div style="font-size: 14px;"><img src="{!! $leaderImage !!}" onerror="javascript:this.src='{!! getSettingValueByKeyCache('logo') !!}'" style="max-width: 50px;height: auto;" />&nbsp;&nbsp;&nbsp;{!! $leaderNickname !!}</div>
        <div style="padding-top:15px;font-size: 14px;color: #bbb;">当您确认推荐人后,您今后的购买交易都将在此推荐人的店铺中完成</div>
    </div>
</script>
<script type="text/template" id="change_man">
      <div style="text-align: center;">
        <div style="font-size: 16px;padding-bottom: 15px;">
            修改推荐人
        </div>
        <input type="text" name="code" class="form-control" placeholder="请输入店主邀请码" />
        <div style="padding-top:15px;font-size: 14px;color: red;">平台用户自注册起,最多可以更换绑定修改推荐人3次,请谨慎选择</div>
    </div>
</script>
<script type="text/template" id="wanshan_info">
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
</script>
<script type="text/javascript">
    var leaderId = '{!! $leaderId !!}';
    @if(Request::is('sign_shop'))
        leaderId = 0;
    @endif
    function openChange()
    {
            layer.open({
            content: $('#change_man').html()
            ,btn: ['确定', '取消']
            ,yes: function(index){
                var code = $('input[name=code]').val();
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
    $(function(){
            layer.open({
            content: $('#confirm_man').html()
            ,btn: ['确定推荐人', '修改推荐人']
            ,yes: function(index){
                 layer.close(index);
                 layer.open({
                    type: 1,
                    shadeClose: true,
                    shade: 0.8,
                    area: ['100%', '100%'],
                    content: $('#wanshan_info').html(), 
                });
              // $.zcjyRequest('/ajax/obzy/set_leader/'+leaderId,function(res){
              //   if(res){
              //       alert(res);
              //        //ajax请求
              //       layer.close(index);
              //   }
              // });
            }
            ,no: function(index){
              layer.close(index);
              openChange();
            }
            });
    });
</script>
<script type="text/javascript">
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
        var mobile = $('input[name=mobile]').val(); 
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
                  layer.closeAll();
                  $.zcjyFrameOpen($('.enter_code').html());
              }
        },{mobile:mobile,code:code,leader:leaderId});
  });
</script>
@endif