<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use App\Category;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Route;

class PostController extends Controller
{

    // public $validators = [
    //     'title'     => 'required|max:100',
    //     'content'   => 'required',
    //     'media'     => 'required|URL',

    // ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Post::where('title', 'gfwreisdhjb')->first());
        $posts = Post::paginate(5);

        // dd($posts);

        return view('admin.posts.index', compact('posts'));
    }

    public function myIndex()
    {
        $posts = Post::where('user_id', Auth::user()->id)->paginate(50);

        // dd(Auth::user()->id);

        return view('admin.posts.myIndex', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validators = [
            'title'     => 'required|max:100',
            'content'   => 'required',
            'media'     => 'required|URL',
            'slug'      => [
                'required',
                'max:100',
            ],
            'category_id'   => 'required|exists:App\Category,id',
            'tags'          => 'exists:App\Tag,id',
        ];

        $request->validate($validators);

        $formData = $request->all() + [
            'user_id' => Auth::user()->id
        ];

        preg_match_all('/#(\S*)/', $formData['content'], $tags_from_content);
        // TODO: gestire i tag già presenti nel database (evitare doppioni)
        $tagIds = [];
        foreach($tags_from_content[1] as $tag) {


            // dd($tag);
            if(!Tag::where('name', $tag)->first()) {

                $newTag = Tag::create([
                    'name'  => $tag,
                    //'slug'  => Str::slug($tag)
                    'slug'  => Post::generateSlug($tag)
                ]);

                $tagIds[] = $newTag->id;
            }else {
                $tagIds[] = Tag::where('name', $tag)->first()->id;
            }
        }

        $formData['tags'] = $tagIds;

        $post = Post::create($formData);

        $post->tags()->attach($formData['tags']);

       return redirect()->route('admin.posts.show', $post->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if (Auth::user()->id !== $post->user_id) abort(403);

        $categories = Category::all();

        return view('admin.posts.edit', ['post' => $post, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if (Auth::user()->id !== $post->user_id) abort(403);

        $validators = [
            'title'     => 'required|max:100',
            'content'   => 'required',
            'media'     => 'required|URL',
            'slug'      => [
                'required',
                Rule::unique('posts')->ignore($post),
                'max:100',
            ],
            'category_id'   => 'required|exists:App\Category,id',
            'tags'          => 'exists:App\Tag,id',
        ];

        $request->validate($validators);
        $formData = $request->all();

        preg_match_all('/#(\S*)/', $formData['content'], $tags_from_content);

        $tagIds = [];
        foreach($tags_from_content[1] as $tag) {

            if(!Tag::where('name', $tag)->first()) {

                $newTag = Tag::create([
                    'name'  => $tag,
                    'slug'  => Post::generateSlug($tag)
                ]);

                $tagIds[] = $newTag->id;
            }else {
                $tagIds[] = Tag::where('name', $tag)->first()->id;
            }
        }

        $formData['tags'] = $tagIds;


        $post->update($request->all());
        $post->tags()->sync($formData['tags']);

        return redirect()->route('admin.posts.show', $post->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (Auth::user()->id !== $post->user_id) abort(403);

        $post->tags()->detach();
        $post->delete();
        return redirect()->route('admin.posts.index');
    }
}
