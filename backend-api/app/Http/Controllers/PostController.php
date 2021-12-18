<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//panggil model Post
use App\Models\Post;
//panggil model User
use App\Models\User;

class PostController extends Controller
{
    public function freePost(Request $request) 
    {
      return response()->json(Post::where('type', 0)->latest()->get());
    }
    
    public function premiumPost(Request $request)
    {
      return response()->json(Post::where('type', 1)->latest()->get());
    }
    
    public function detailPost($id, Request $request)
    {
      $post = Post::find($id);
      
      //jika type post adalah premium
      if($post->type === 1) {
        //cek type token
        if($request->user()->tokenCan('post:premium')) {
          return response()->json([
            'post' => $post
          ]);
        }
        //jika token bukan premium
        return response()->json(['notPremiumUser' => 'You are not premium user'], 403);
      }
      
      //jika post bukan premium
      return response()->json([
        'post' => $post
      ], 200);
    }
}
