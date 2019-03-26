@extends('front.social.layout.base')

@section('css')
<style type="text/css">

</style>
@endsection

@section('title')
<title>{!! getSettingValueByKeyCache('name') !!}</title>
@endsection

@section('content')

    @include('front.common.search.html')

    <!-- 商品分类 -->
    <div class="all_products">
        <div class="slide-box nav-scroll">
            <div class="slide-warp">
                <a class="slide-item active" href="javascript:;">首页</a>
                <?php 
                    $rootCats = cat_level01();
                ?>
             @foreach($rootCats as $element)
                <a class="slide-item" href="/category/level1/{{ $element->id }}">
                    {{ $element->name }}
                </a>
             @endforeach
            </div>
        </div>
    </div>
    
    <?php
        $banners = banners('index');
        $count = $banners->count();
    ?>
    @if ($count)

        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach ($banners as $element)
                <!-- Lazy image -->
                <a class="swiper-slide" @if($element->link) href="{{ $element->link }}" @else href="javascript:;" @endif>
                    <img data-src="{{ $element->image }}" class="swiper-lazy">
                    <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
                </a>
                @endforeach
            </div>
        </div>

    @endif

    @if(count($categories))
         <div class="weui-grids index-function-grids">
            @foreach($categories as $cat)
                <a href="/category/level1/{!! $cat->id !!}" class="weui-grid">
                    <div class="weui-grid__icon">
                        <img src="{!! $cat->image !!}" onerror="javascript:this.src='{{ asset('images/default/index/grid1.png') }}';" alt="">
                    </div>
                    <p class="weui-grid__label">{!! $cat->name !!}</p>
                </a>
            @endforeach
        </div>
    @endif

    <!-- 资讯 -->
    <?php
        $notices = notices();
    ?>
    @if ($notices->count())
        <div class="weui-cell notice">
            <div class="weui-cell__hd">
                <p>NOTICE</p>
            </div>
            <div class="weui-cell__bd txtScroll-top">
                <div class="swiper-container1">
                  <div class="swiper-wrapper infoList">
                    @foreach ($notices as $element)
                        <a class="swiper-slide" href="/notices/{{ $element->id }}">
                            <span class="title">{{ getSettingValueByKeyCache('name') }}</span>
                            <span class="content">{{ $element->name }}</span>
                        </a>
                    @endforeach
                  </div>
                </div>
            </div>
            
        </div>
    @endif

    @foreach($categories as $cat)
        <div class="weui-cells">
            <div class="weui-cell weui-cell_access">
                <div class="weui-cell__bd title-img" id="count_timer">
                <!--     <img src="{{ asset('images/default/index/miaosha.png') }}" style="vertical-align: middle; margin-right: 10px;"> -->
                    <span style="vertical-align: middle">{!! $cat->name !!}</span> 
                 
                </div>
                <a class="weui-cell__ft" href="/category/level1/{!! $cat->id !!}">查看更多</a>
            </div>
        </div>

         <?php
            $catProducts = app('commonRepo')->productRepo()->getProductsOfCatWithChildrenCatsCached($cat->id,0, 15);
         ?>

        <div class="product-wrapper country-sum">
            <div class="slide-box">
                <div class="slide-warp">
                    @foreach ($catProducts as $element)
                        <a class="slide-item" href="/product/{{ $element->id }}">
                            <img src="{{ $element->image }}" alt="" style="max-height: 120px;">
                            <p class="intr">{{ $element->name }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
    
    
 
    
    
    <!-- 精选专题 -->
    {{-- <div class="top-title">
        <p>精选专题</p>
    </div>
    <div class="weui-cell subject">
        <div class="product-show">
            <div class="show-item"">
                <img src="{{ asset('images/social/j1.jpg') }}" alt="">
            </div>
        </div>
    </div>
    <div class="weui-cell subject">
        <div class="product-show">
            <div class="show-item">
                <img src="{{ asset('images/social/j2.jpg') }}" alt="">
            </div>
        </div>
    </div>
    <div class="weui-cell subject">
        <div class="product-show">
            <div class="show-item">
                <img src="{{ asset('images/social/j3.jpg') }}" alt="">
            </div>
        </div>
    </div>
    <div class="weui-cell subject">
        <div class="product-show">
            <div class="show-item">
                <img src="{{ asset('images/social/j4.jpg') }}" alt="">
            </div>
        </div>
    </div> --}}
    
    <!-- 更多商品 -->
    <?php
        $products = products(0, 30);
    ?>
    <div class="top-title more-goods">
        <p>更多商品</p>
    </div>
    
    <div class="product-wrapper more-goods scroll-container" id="more-goods">
        @foreach ($products as $element)
            <a class="product-item3 scroll-post" href="/product/{{ $element->id }}">
                <div class="img">
                    <img class="lazy" data-original="{{ $element->image }}">
                </div> 
                <div class="title">{{ $element->name }}</div>
                @if ($element->realPrice)
                    <div class="price">{{ getSettingValueByKeyCache('price_fuhao') }}{{ $element->realPrice }} <span class="cross">{{ getSettingValueByKeyCache('price_fuhao') }}{{ $element->price }}</span></div>
                @else
                    <div class="price">{{ getSettingValueByKeyCache('price_fuhao') }}{{ $element->price }} </div>
                @endif
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
            <div class="title">@{{=value.name}}</div>
            @{{? value.realPrice }}
                <div class="price">{{ getSettingValueByKeyCache('price_fuhao') }}@{{=value.realPrice}} <span class="cross">{{ getSettingValueByKeyCache('price_fuhao') }}@{{=value.price}}</span></div>
            @{{??}}
                <div class="price">{{ getSettingValueByKeyCache('price_fuhao') }}@{{=value.price}} </div>
            @{{?}}
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
    $(document).ready(function(){

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
                url:"/api/products?skip=" + $('.scroll-post').length + "&take=30",
                type:"GET",
                success:function(data){
                    working = false;
                    var all_product=data.data;
                    if (all_product.length == 0) 
                    {
                        fireEvent = false;
                        $('#shopinfo').show();
                        return;
                    }

                    if($('.scroll-post').length >= 150)
                    {
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


    $('.price-btn').click(function(){
        var id=$(this).data('id');
        var status=$(this).data('status');
        var that=this;
        if(!status){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:'/api/userGetCoupons/'+id,
                type:'post',
                success:function(data){
                    if(data.code==0){
                        layer.open({
                        content:data.message
                        ,skin: 'msg'
                        ,time: 2 
                      });
                    $(that).text('已领取');
                    $(that).data('status',true);
                    $(that).attr("style","background-color:#ddd !important;");
                    }else{
                    layer.open({
                        content:data.message
                        ,skin: 'msg'
                        ,time: 2 
                      });
                    }
                }
            });
        }else{
            return false;
        }

    });

    @include('front.common.search.js')

</script>



<script>
    var mySwiper1 = new Swiper('.swiper-container1', {
        direction : 'vertical',
        loop : true,
        speed: 1000,
        // autoplay: {
        //   delay: 3000,//1秒切换一次
        // },
    })
</script>

@endsection