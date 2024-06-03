@extends('layouts.sidebar')

@section('content')
  <div class="board_area w-100 border m-auto d-flex">
    <div class="post_view w-75 mt-5">
      <p class="w-75 m-auto">投稿一覧</p>
      @foreach($posts as $post)
      <div class="post_area border w-75 m-auto p-3">
        <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
        <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
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
   <div class="other_area border w-25">
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
          <!-- <div class="category_search">
              <p>サブカテゴリーの検索</p>
              <ul>
                @foreach($main_categories as $main_category)
                    <li>
                        <span>{{ $main_category->main_category }}</span>
                        <ul>
                            @foreach($main_category->subCategories as $sub_category)
                            <li>
                                <a href="{{ route('post.show', ['sub_category_id' => $sub_category->id]) }}">{{ $sub_category->sub_category }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
              </ul>
          </div> -->
          <div class="category_search">
                <h3>サブカテゴリーの検索</h3>
                @foreach($main_categories as $main_category)
                    <div class="main_category_container">
                        <input type="checkbox" id="main_category_{{ $main_category->id }}" class="toggle_checkbox">
                        <label for="main_category_{{ $main_category->id }}" class="main_category_label">
                            {{ $main_category->main_category }}
                            <span class="arrow">&#9660;</span>
                        </label>
                        <div class="sub_categories">
                            @foreach($main_category->subCategories as $sub_category)
                                <label class="sub_category_label">
                                    <input type="radio" name="sub_category_id" value="{{ $sub_category->id }}" form="postSearchRequest">
                                    {{ $sub_category->sub_category }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
      </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
  </div>
@endsection
