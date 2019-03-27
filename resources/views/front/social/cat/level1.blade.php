@extends('front.social.layout.base')

@section('css')
    <style>
        .weui-grid{width: 25%;}
        .swipe-wrap{width: 100%;}
        a.swiper-slide{width: 100%; display: block;}
        a.swiper-slide img{width: 100%; display: block;}
        .mt22{margin-top: 22px;}
        .product-item3.scroll-post{
          width: 100%;
          height: 150px;
          padding-bottom: 0px;
        }
        .product-wrapper .product-item3 img {
          width:100px;
          height: auto;
        }
        .product-title-content{
          position: absolute;
          left: 130px;
          top: 0;
          right: 0;
        }
        .product-bottom-content{
          position: absolute;
          bottom: 20%;
          left: 35%;
        }
        .product-liji-gou{
          background-color: yellow;color: black;padding: 10px;font-size: 14px;
        }
        .product-add-cart{
          background-color: orange;margin-left:10px;color: black;padding: 10px;font-size: 14px;
        }
    </style>
@endsection

@section('title')
@endsection

@section('content')

    <!-- 搜索框 -->
    @include('front.common.search.html')

    <div class="all_products">
      <div class="slide-box nav-scroll" id="nav-scroll">
          <div class="slide-warp">
              <a class="slide-item" href="/">首页</a>
              <?php 
                  $rootCats = cat_level01();
              ?>
           @foreach($rootCats as $element)
              <a class="slide-item @if($id == $element->id) active @endif" href="/category/level1/{{ $element->id }}">
                  {{ $element->name }}
              </a>
           @endforeach
          </div>
      </div>
    </div>
    
    <!-- 板块 -->
    <div class="weui-grids index-function-grids">
      <?php
        $cat_level02 = cat_level02($id);
      ?>
      @foreach ($cat_level02 as $element)
        <a href="/category/level2/{{ $element->id }}" class="weui-grid">
           <div class="weui-grid__icon">
               <img src="{{ $element->image }}" onerror="javascript:this.src='{{ asset('images/default/index/grid1.png') }}';" alt="">
           </div>
           <p class="weui-grid__label">{{ $element->name }}</p>
        </a>
      @endforeach
   </div>
    
    <div class="product-wrapper more-goods scroll-container">

      @foreach ($products as $element)
        <a class="product-item3 scroll-post" href="/product/{{ $element->id }}">
          <div class="img">
              <img class="lazy" data-original="{{ $element->image }}">
          </div> 
          <div class="product-title-content">
            <div class="title">{{ $element->name }}</div>
            @if ($element->realPrice)
                <div class="price mt22">{{ getSettingValueByKeyCache('price_fuhao') }}{{ $element->realPrice }} <span class="cross">{{ getSettingValueByKeyCache('price_fuhao') }}{{ $element->price }}</span></div>
            @else
                <div class="price mt22">{{ getSettingValueByKeyCache('price_fuhao') }}{{ $element->price }} </div>
            @endif
          </div>

          <div class="product-bottom-content">
            <span class="product-liji-gou">立即抢购</span>
            <span class="product-add-cart">加入购物车</span>
          </div>
        </a>
      @endforeach

    </div>

    @include('front.'.theme()['name'].'.layout.shopinfo')

    @include(frontView('layout.nav'), ['tabIndex' => 1])
@endsection


@section('js')

<script src="{{ asset('vendor/doT.min.js') }}"></script>
<script src="{{ asset('vendor/underscore-min.js') }}"></script>

<script type="text/template" id="template">
    @{{~it:value:index}}
        <a class="product-item3 scroll-post" href="/product/@{{=value.id}}">
            <div class="img">
                <img class="lazy" data-original="@{{=value.image}}">
            </div> 
            <div class="product-title-content">
              <div class="title">@{{=value.name}}</div>
              @{{? value.realPrice }}
                  <div class="price mt22">{{ getSettingValueByKeyCache('price_fuhao') }}@{{=value.realPrice}} <span class="cross">{{ getSettingValueByKeyCache('price_fuhao') }}@{{=value.price}}</span></div>
              @{{??}}
                  <div class="price mt22">{{ getSettingValueByKeyCache('price_fuhao') }}@{{=value.price}} </div>
              @{{?}}
            </div>

            <div class="product-bottom-content">
              <span class="product-liji-gou">立即抢购</span>
              <span class="product-add-cart">加入购物车</span>
            </div>
            
        </a>
    @{{~}}
</script>

<script type="text/template" id="template-search">
    @{{~it:value:index}}
        <a class="weui-cell weui-cell_access" href="/product/@{{=value.id}}">
            <div class="weui-cell__bd weui-cell_primary">
                <p>@{{=value.name}}</p>
            </div>
        </a>
    @{{~}}
</script>

<script type="text/javascript">

  @include('front.common.search.js')

  $(document).ready(function(){
    //导航条
    var w1=$('.nav-scroll .slide-item').width()+20;
    var index=$('.nav-scroll .active').index()-2;
    var left=w1*index;
    $('.nav-scroll').scrollLeft(left);

    //秒杀倒计时
   
    //无限加载
    var fireEvent = true;
    var working = false;

    $(document).endlessScroll({

        bottomPixels: 350,

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
            url:"/ajax/category/level1/{{ $id }}?skip=" + $('.scroll-post').length + "&take=30",
            type:"GET",
            success:function(data){
              working = false;
              var all_product=data.data;
              if (all_product.length == 0) {
                fireEvent = false;
                $('#shopinfo').show();
                return;
              }
              // 编译模板函数
              var tempFn = doT.template( $('#template').html() );

              // 使用模板函数生成HTML文本
              var resultHTML = tempFn(all_product);

              // 否则，直接替换list中的内容
              $('.scroll-container').append(resultHTML);

              $("img.lazy").lazyload({effect: "fadeIn"});
            }
          });
        }
    });
  });

     // // 搜索框中是否有内容输入
     // $(".search-box .weui-cell__bd input").focus(function(){
     //        var len = $('.search-box .weui-cell__bd input').val();
     //        console.log(len);
     //        if(len == ''){
     //            $('.search-box .find-icon').show();
     //        }else{
     //            $('.search-box .find-icon').hide();
     //        }
     // });
     // $(this).on('click', '.selector', function(event) {
     //   event.preventDefault();
     //   /* Act on the event */
     // });
    // var mySwiper = new Swiper('.swiper-container', {
    //   // autoplay: 1000,//可选选项，自动滑动
    //   resistanceRatio : 0,
    //   slidesPerView : 'auto',//'auto'
    //   // centeredSlides : true,//设定为true时，active slide会居中，而不是默认状态下的居左
    //   slideToClickedSlide: false,
    //   onClick: function(swiper){
    //             /* Act on the event */
    //             $(this).siblings().removeClass('swiper-slide-active').removeClass('active');
    //             $(this).addClass('swiper-slide-active').addClass('active');
    //   }
    // })
    // mySwiper.slideTo($('.swiper-slide.active').index(), 1, false);
  /* Act on the event */
  // 导航项目保持居中

  // var wid1=$('.slide-item').parents('.slide-box').parent().width();
  // var wid2=wid1/3;
  // $('.country-sum .slide-item,.flash_sale .slide-item,.team_sale .slide-item').width(wid2);

  // $(document).resize(function(event) {
  //     /* Act on the event */
  //     var wid1=$('.slide-item').parents('.slide-box').width();
  //     var wid2=wid1/3;

  //     $('.country-sum .slide-item,.flash_sale .slide-item,.team_sale .slide-item').width(wid2);
  // });

</script>
@endsection