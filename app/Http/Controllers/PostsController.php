<?php

namespace App\Http\Controllers;

use App\Post;

use Intervention\Image\Facades\Image;


class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //protecting the route. /p/create

    }
    public function index()
    {
        $users=auth()->user()->following()->pluck('profiles.user_id');
        //pluck: used to extract certain value from a collection(associative array,array of object)
        //dd($users);
        //$posts=Post::whereIn('user_id',$users)->latest()->get();
        // select all from post where user_id isn in $users(list of user we just grab)
        //latest:descending order->orderBy('created_at', 'DESC')
        // dd($posts);
        $posts=Post::whereIn('user_id',$users)->with('user')->latest()->paginate(5);
        //paginate(5): showing specific numbers of items from list of items in per page
       //with('user'): here user is user() in post.php
        return view('posts.index',compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }
    public function store()
    {
        $data=request()->validate([
            'caption'=>'required',
            'image' => ['required','image'],
            //image is an instance of UploadedFile(class)
        ]);
        // through the request, validate the request
        $imagePath=request('image')->store('upload','public');
        $image=Image::make(public_path("storage/{$imagePath}"))->fit(1200,1200);
        //package::make(public_path("storage/{$imagePath}"))->fit(1200,1200);
        //make->wrap image file around intervention class
        $image->save();

        auth()->user()->posts()->create(
            [
                'caption'=>$data['caption'],
                'image'=>$imagePath,
            ]
        );

        //\App\Post::create($data);
        //create post


        // dd(request()->all());
        return redirect('/profile/'.auth()->user()->id);

    }
    public function show(\App\Post $post)
    //route model binding
    {
        //dd($post);
        return view('posts.show',compact('post'));
        //compact(post)->match post=$post
    }
}


