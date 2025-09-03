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
        if (auth()->user()->hasPermissionTo('create_post')) {
            $request->validated();
            $post = Post::create($request->validated());
            if ($request->file('image')) {
                $media = MediaUploader::fromSource($request->file('image'))->setAllowedAggregateTypes([Media::TYPE_IMAGE])->toDisk('minio')->toDirectory('uploads')->upload();
                $post->attachMedia($media, 'image');
            }
//        activity()->on($post)->by(auth()->user())->log('Created new post');
            return \response()->json(['message' => 'New post created Successfully']);
        }
        return response('Access denied.', Response::HTTP_FORBIDDEN);

    }

    public function show($id)
    {
        if (auth()->user()->hasPermissionTo('read_post')) {
            $post = Post::findOrFail($id);
            activity()->on($post)->by(auth()->user())->log('Viewed one post.');
            return new PostResource($post);
        }
        return response('Access denied.', Response::HTTP_FORBIDDEN);

    }

    public function update($id, PostRequest $request)
    {
        $request->validated();

        if (auth()->user()->hasPermissionTo('edit_post')) {
            $post = Post::findOrFail($id);
            $post->update($request->validated());
//            activity()->on($post)->by(auth()->user())->log('Update one post.');
            return \response()->json(['message' => 'post edited successfully.']);
        }
        return response('Access denied.', Response::HTTP_FORBIDDEN);
    }

    public function destroy($id)
    {
        if (auth()->user()->hasPermissionTo('delete_post')) {
            $post = Post::findOrFail($id);
            $post->delete();
//            activity()->on($post)->by(auth()->user())->log('Deleted one post.');
            return response()->json(['msg' => 'Post deletion complete.']);
        }
        return response('Access denied.', Response::HTTP_FORBIDDEN);
    }

    public function replaceImage($id, Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        if ($request->file('image')) {
            $media = MediaUploader::fromSource($request->file('image'))->setAllowedAggregateTypes([Media::TYPE_IMAGE])->toDisk('minio')->toDirectory('uploads')->upload();
            $post = Post::withMedia('image')->findOrFail($id);
            $post->syncMedia($media, 'image');
//                activity()->on($post)->by(auth()->user())->log('Changed image of post.');
            return \response()->json(['message' => 'Changed post image successfully.']);
        }
        return \response()->json(['message' => 'Unable to change post image']);
    }

    public function detachImage($id, Request $request)
    {
        $post = Post::withMedia('image')->findOrFail($id);
        $media = $post->getMedia('image')->first();
        $post->detachMedia($media, 'image');
        return \response()->json(['message' => 'Image detached from post successfully']);
    }
}
