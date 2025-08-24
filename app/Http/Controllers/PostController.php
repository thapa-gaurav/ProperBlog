<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Plank\Mediable\Facades\MediaUploader;
use Plank\Mediable\Media;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::all();
        return new PostCollection($posts);
    }

    public function store(PostRequest $request)
    {
        $request->validated();
        $post =  Post::create($request->validated());
        if($request->file('image')){
            $media = MediaUploader::fromSource($request->file('image'))->withAltAttribute('alt images')->setAllowedAggregateTypes([Media::TYPE_IMAGE])->upload();
            $post->attachMedia($media,'image');
        }
        return \response()->json(['message'=>'New post created Successfully']);
    }

    public function show($id){
        $post = Post::findOrFail($id);
        return new PostResource($post);
    }

    public function update($id,PostRequest $request){
        $request->validated();
        if(auth()->user()->hasPermissionTo('update post')){
            $post = Post::findOrFail($id);
            $post->update($request->validated());
            return \response()->json(['message'=>'post edited successfully.']);
        }
        return response('Access denied.',Response::HTTP_FORBIDDEN);
    }

    public function destroy($id)
    {
            $post = Post::findOrFail($id);
            $post->delete();
            return response()->json(['msg'=>'Post deletion complete.']);

    }

    public function replaceImage($id,Request $request){
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);
            if($request->file('image')){
                $media = MediaUploader::fromSource($request->file('image'))->withAltAttribute('alt images')->setAllowedAggregateTypes([Media::TYPE_IMAGE])->upload();
                $post = Post::withMedia('image')->findOrFail($id);
                $post->syncMedia($media,'image');
                return \response()->json(['message'=>'Changed post image successfully.']);
            }
            return \response()->json(['message'=>'Unable to change post image']);
    }

    public function detachImage($id,Request $request)
    {
        $post = Post::withMedia('image')->findOrFail($id);
        $media = $post->getMedia('image')->first();
        $post->detachMedia($media,'image');
        return \response()->json(['message'=>'Image detached from post successfully']);
    }
}
