<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
    public function index(User $user)
    //since we are importing \App\User
    {
      /*  $user=User::findOrFail($user);
        //dd($user);
        //dd->dump and die
        //echo out whatever user is and stop all operations.
        return view('profiles.index',
    ['user'=>$user]
    //here user is basically variable name inside home.blade.php
    */
/*
    $postCounts=Cache::remember('count.posts.' .$user->id, // count.posts is key.. key concatenate with user_id
    now()->addSeconds(30), //time
    function() use ($user) //callback
    {
        return $user->posts->count();
    });
// $postCounts is gonna be equal to a cache than gonna remember key.(whatever user_id)
//we gonna store it for now+30 seconds


    $followersCounts=Cache::remember('count.followers.' . $user->id,
    now()->addSeconds(30),
    function() use($user)
{
    return $user->profile->followers->count();
});

    $followingCounts=Cache::remember('count.following.' . $user->id,
    now()->addSeconds(30),
   function() use($user)
{
    return $user->following->count();
    });
*/
$postCounts=$user->posts->count();
$followersCounts=$user->profile->followers->count();
$followingCounts=$user->following->count();
    $follows=(auth()->user()) ? auth()->user()->following->contains($user->id) : false;
    //if the user is authenticated ? if authenticated user followings does that contains $user(that passed in parameter) : false
   // dd($follows);
    return view('profiles.index',compact('user','follows','postCounts','followersCounts','followingCounts'));
    //compact(user)->match user=$user
    }
    public function edit(User $user)
    {
        $this->authorize('update',$user->profile);
        //authorize('update',$user->profile) ////authorizing update on particular profile
        return view('profiles.edit', compact('user'));

    }
    public function update(User $user)
    {
        $this->authorize('update',$user->profile);
        //$this-> current class reference

        $data= request()->validate(
            [
                'title'=>'required',
                'description'=>'required',
                'url'=> 'url',
                'image'=> '',
                // 'title'(key)=>value()'required',
                //
            ]
            );
           // dd($data);
           auth()->user()->profile->update($data);
           if(request('image'))
           //if(request('image')): if the request has an image
           {

            $imagePath=request('image')->store('profile','public');
            $image=Image::make(public_path("storage/{$imagePath}"))->fit(1000,1000);
            //package::make(public_path("storage/{$imagePath}"))->fit(1200,1200);
            //make->wrap image file around intervention class
            $image->save();

            $imageArray=['image'=>$imagePath];

           }

          // dd($data);



           auth()->user()->profile->update(array_merge(
               $data,
               $imageArray ?? []
               //??<- null coalescing operator
               //return exp1 if exp1 exits and value is not null
               //otherwise return exp2
           ));
           return redirect("/profile/{$user->id}");

    }
}
