<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use App\Http\Requests\SubCategoryRequest;
use Auth;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    public function show(Request $request){
        // 全ての投稿を取得
        $posts = Post::with('user', 'postComments')->get();
        // 全てのメインカテゴリーを取得
        $main_categories = MainCategory::get();

        // キーワードが入力された場合
        if(!empty($request->keyword)){
            $posts = Post::with('user', 'postComments')
                ->where('post_title', 'like', '%'.$request->keyword.'%')
                ->orWhere('post', 'like', '%'.$request->keyword.'%')->get();
        }
        // カテゴリーが選択された場合
        elseif($request->sub_category_id){
            $subCategoryId = $request->sub_category_id;
            $posts = Post::whereHas('subCategories', function($query) use ($subCategoryId){
                $query->where('sub_category_id', $subCategoryId);
            })->with('user', 'postComments')->get();
        }
        // いいねした投稿が選択された場合
        elseif($request->like_posts){
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'postComments')
                ->whereIn('id', $likes)->get();
        }
        // 自分の投稿が選択された場合
        elseif($request->my_posts){
            $posts = Post::with('user', 'postComments')
                ->where('user_id', Auth::id())->get();
        }

        return view('authenticated.bulletinboard.posts', compact('posts', 'main_categories'));
    }




    public function postDetail($post_id){
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        $main_categories = MainCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request){
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);

        // 投稿とサブカテゴリーの中間テーブルに関連を保存する
        if ($request->has('sub_category_ids')) {
            $post->subCategories()->attach($request->sub_category_ids);
        }

        return redirect()->route('post.show');
    }


    public function postEdit(Request $request){
        // バリデーションルールを定義
        $rules = [
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:5000',
        ];

        // バリデーションを実行
        $validator = Validator::make($request->all(), $rules);

        // バリデーションが失敗した場合
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }
    public function mainCategoryCreate(Request $request){

        // バリデーションルールを定義
        $rules = [
            'main_category_name' => 'required|string|max:100|unique:main_categories,main_category',
        ];

        // バリデータを作成
        $validator = Validator::make($request->all(), $rules);

        // バリデーションが失敗した場合
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    // 🌟name('sub.category.create')のルートを追加必要
    public function subCategoryCreate(SubCategoryRequest $request)
    {
        // バリデーションルールを定義
        $rules = [
            'main_category_id' => 'required|exists:main_categories,id',
            'sub_category_name' => 'required|string|max:100|unique:sub_categories,sub_category,NULL,id,main_category_id,',$request->input('main_category_id')
        ];
         // バリデータを作成
        $validator = Validator::make($request->all(), $rules);

        // バリデーションが失敗した場合
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        SubCategory::create([
            'main_category_id' => $request->input('main_category_id'), // メインカテゴリーIDを取得
            'sub_category' => $request->input('sub_category_name'),
        ]);

        return redirect()->back()->with('success', 'サブカテゴリーが登録されました');
    }

    public function commentCreate(Request $request){
        $rules = [
        'comment' => 'required|string|max:2500', // 必須項目、文字列型、最大2500文字
        ];

        // バリデータを作成
        $validator = Validator::make($request->all(), $rules);

        // バリデーションチェック
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput(); // エラーがある場合は入力値を保持して元のページにリダイレクト
        }
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard(){
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

        return response()->json();
    }
}
