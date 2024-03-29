@extends('front.default.layout.base')

@section('css')
    <style>
      ul li{
        display: inline-block; height: 40px; line-height: 40px; padding: 0 10px;
      }
      /*.product-wrapper{margin-top:40px;}*/
      .kongbai{
        height: 40px;background-color:#eee;
      }
      .cat-selector{
        position: fixed;
      }
    </style>
@endsection

@section('content')
    <div class="cat-selector">
      <ul>
        <li @if($id == $parent_id) class="active" @endif><a href="/category/level2/{{$parent_id}}">@if($category->level==2) {!! $category->name !!} @else {{ optional($parent_cat)->name }} @endif</a></li>
        @foreach ($childrenCats as $element)
          <li @if($id == $element->id) class="active" @endif><a href="/category/level2/{{$element->id}}">{{$element->name}}</a></li>
        @endforeach
      </ul>
    </div>
    <div class="kongbai"></div>
    <div class="product-wrapper scroll-container" style="">
      @foreach ($products as $element)
        <a class="product-item2 scroll-post" href="/product/{{$element->id}}">
            <div class="img">
                <img class="lazy" data-original="{{ $element->image }}" src="{{ $element->image }}">
            </div>
            <div class="title">{{$element->name}}</div>
            <div class="price">{{ getSettingValueByKeyCache('price_fuhao') }}{{$element->price}}@if($element->jifen)+{{ $element->jifen }}{!! getSettingValueByKeyCache('credits_alias') !!}@endif<br><span class="buynum">已售 {{ $element->sales_count }}</span></div>
        </a>
      @endforeach
    </div>

    @include('front.'.theme()['name'].'.layout.shopinfo')
    </div>
    @include(frontView('layout.nav'), ['tabIndex' => 2])
@endsection


@section('js')
  <script type="text/javascript">
    //无限滚动
    
    $(document).ready(function(){

      //信息是否请求完
      var fireEvent = true;
      //电器是否已经发送请求
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
            url:"/api/products_of_cat_with_children/{{ $id }}?skip=" + $('.scroll-post').length + "&take=18",
            type:"GET",
            success:function(data){
              working = false;
              var all_product=data.data;
              if (all_product.length == 0) {
                fireEvent = false;
                $('#shopinfo').show();
                return;
              }
              for (var i = all_product.length - 1; i >= 0; i--) {
                 var jifen_html = all_product[i].jifen ? '+'+all_product[i].jifen + "{!! getSettingValueByKeyCache('credits_alias') !!}" : '';
                $('.scroll-container').append(
                  "<a class='product-item2 scroll-post' href='/product/" + all_product[i].id + "'>\
                      <div class='img'>\
                          <img src='" + all_product[i].image + "'>\
                      </div>\
                      <div class='title'>" + all_product[i].name + "</div>\
                      <div class='price'>{{ getSettingValueByKeyCache('price_fuhao') }}" + all_product[i].price + jifen_html + "<br><span class='buynum'>已售 " + all_product[i].sales_count + "</span></div>\
                  </a>"
                );
        
              }
            }
          });
        }
      });
    });
  </script>
@endsection