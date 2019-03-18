@extends('front.social.layout.base')

@section('css')
<style type="text/css">
#share_1{float:left;width:49%; display: block}
#share_2{float:right;width:49%; display: block}
#mess_share img{width:22px;height:22px;vertical-align: top;border: 0;}
.button1{font-size:18px;padding:10px 0;border:1px solid #5a5a59;color:#ffffff;background-color:#0e9e1a;background-image:linear-gradient(to top, #02a038, #02a138 18%, #02c244);box-shadow: 0 1px 1px rgba(7, 56, 28, 0.61), 0 1px 1px rgba(255, 255, 255, 0.51) inset;text-shadow: 0.5px 0.5px 1px rgba(15, 114, 57, 0.75);}
.button1:active{background-color: #007e2b;background-image: linear-gradient(to top, #016423, #007629 24%, #00a137);}
.button2{font-size:16px;padding:8px 0;border:1px solid #adadab;color:#000000;background-color: #e8e8e8;background-image: linear-gradient(to top, #dbdbdb, #f4f4f4);box-shadow: 0 1px 1px rgba(0, 0, 0, 0.45), inset 0 1px 1px #efefef; text-shadow: 0.5px 0.5px 1px #fff;text-align:center;border-radius:3px;width:100%;}
.button2:active{background-color: #dedede;background-image: linear-gradient(to top, #cacaca, #e0e0e0);}
#mcover{ position: fixed; top:0;  left:0; width:100%; height:100%;background:rgba(0, 0, 0, 0.7);  display:none;z-index:20000;}
#mcover img {position: fixed;right: 18px;top:5px;width: 260px;height: 180px;z-index:20001;}
.img{
  width: 100%;
  min-width: 100%;
  height: 50px;
  margin:0 auto;
  text-align: center;
  font-size: 20px;
}
</style>
@endsection

@section('title')
<title>{!! getSettingValueByKeyCache('name') !!}</title>
@endsection

@section('content')
<div class="img">分享店铺</div> 

<div id="mcover" onclick="document.getElementById('mcover').style.display='';" style=""><img src="http://www.17sucai.com/preview/397880/2015-11-13/分享到朋友圈提示层/images/tishi.png"></div>

<div id="share_1">
    <button class="button2" onclick="document.getElementById('mcover').style.display='block';"><img src="http://www.17sucai.com/preview/397880/2015-11-13/分享到朋友圈提示层/images/fenxiang.png">发送给朋友</button>
</div>
<div id="share_2">
    <button class="button2" onclick="document.getElementById('mcover').style.display='block';"><img src="http://www.17sucai.com/preview/397880/2015-11-13/分享到朋友圈提示层/images/quan.png">分享到朋友圈</button>
</div>
@endsection


@section('js')



@endsection