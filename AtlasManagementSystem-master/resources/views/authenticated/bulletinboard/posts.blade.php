@extends('layouts.sidebar')

@section('content')
  <div class="board_area w-100 m-auto d-flex">
    <div class="post_view w-75 mt-5">
      @foreach($posts as $post)
      <div class="post_area w-75 m-auto p-3">
        <p class="post_name"><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
        <p class="post_title"><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
            @foreach($post->subCategories as $subCategory)
                <span class="post_sub">{{ $subCategory->sub_category }}</span>@if(!$loop->last), @endif
            @endforeach
        <div class="post_bottom_area d-flex">
          <div class="d-flex post_status">
            <div class="mr-5">
              <i class="fa fa-comment"></i>
              <span>{{ $post->commentCounts($post->id) }}</span>
            </div>
            <div>
              @if(Auth::user()->is_Like($post->id))
                  <p class="m-0">
                      <i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i>
                      <span class="like_counts{{ $post->id }}">{{ $post->likes()->count() }}</span>
                  </p>
              @else
                  <p class="m-0">
                      <i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i>
                      <span class="like_counts{{ $post->id }}">{{ $post->likes()->count() }}</span>
                  </p>
              @endif
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
   <div class="other_area w-25">
      <div class="posts_search">
          <div class="btn_toukou"><a href="{{ route('post.input') }}">投稿</a></div>
          <div class="posts_key">
              <!-- キーワードの検索欄 -->
              <input class="keyword"type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
              <input class="keyword_btn" type="submit" value="検索" form="postSearchRequest">
          </div>
          <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest">
          <input type="submit" name="my_posts" class="category_btn like_posts_btn" value="自分の投稿" form="postSearchRequest">
          <!-- サブカテゴリーの一覧表示 -->
          <div class="category_search">
                <p>サブカテゴリーの検索</p>
                <ul class="main_categories">
                    @foreach($main_categories as $main_category)
                        <li class="main_category_item">
                            <div class="main_category_label">
                                {{ $main_category->main_category }} <span class="arrow"></span>
                            </div>
                            <ul class="sub_categories">
                                @foreach($main_category->subCategories as $sub_category)
                                    <li class="sub_category_item">
                                        <a href="{{ route('post.show', ['sub_category_id' => $sub_category->id]) }}">{{ $sub_category->sub_category }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
      </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
  </div>
  <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mainCategoryLabels = document.querySelectorAll('.main_category_label');
            mainCategoryLabels.forEach(label => {
                label.addEventListener('click', function () {
                    const parentItem = this.parentElement;
                    parentItem.classList.toggle('open');
                });
            });
        });

    </script>

@endsection
