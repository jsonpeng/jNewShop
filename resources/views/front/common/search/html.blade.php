<!-- 商品搜索 -->
<?php 
$user = auth('web')->user();
if(isset($user)){
    $leader = $user->LeaderObj;
}
?>
<style type="text/css">
    .weui-cell{border-bottom: 1px solid #eee;}
</style>

<div class="page__bd">
    <div class="weui-search-bar" id="searchBar">
        @if(isset($leader) && $leader != '无')
        <img src="{!! $leader->head_image !!}" style="    max-width: 40px;
    height: auto;
    position: absolute;
    left: 20px;
    top: 3px;
    z-index: 999;
    border-radius: 20px;" />
        @endif
        <form class="weui-search-bar__form">
            <div class="weui-search-bar__box">
                <i class="weui-icon-search"></i>
                <input type="search" class="weui-search-bar__input" id="searchInput" placeholder="搜索" required/>
                <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
            </div>
            <label class="weui-search-bar__label" id="searchText">
                <i class="weui-icon-search"></i>
                <span>搜索</span>
            </label>
        </form>
        <a href="javascript:" class="weui-search-bar__cancel-btn" id="searchCancel">取消</a>
    </div>
    <div class="weui-cells searchbar-result" id="searchResult">
    </div>
</div>