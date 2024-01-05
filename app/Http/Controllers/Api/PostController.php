<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Validator;

class PostController extends Controller
{
    public function allPosts()
    {
        $posts = Post::all();
        return PostResource::collection($posts);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'cover_image' => 'required|image|max:2048',
            'date_range' => 'required|string',
            'tags' => 'required|string',
            'images.*' => 'image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $postData = $validator->validated();
        [$startDate, $endDate] = explode(' - ', $postData['date_range']);
        $postData['start_date'] = date('Y-m-d', strtotime($startDate));
        $postData['end_date'] = date('Y-m-d', strtotime($endDate));
        unset($postData['date_range']);
        $postData['all_dates'] = $this->generateDateRange($postData['start_date'], $postData['end_date']);
        $postData['tags'] = explode(',', $postData['tags']);

        if ($request->hasfile('cover_image')) {
            $image = $request->file('cover_image');
            $name = uniqid() . 'img' . '.' . $image->getClientOriginalExtension();
            $destinationPath = 'post-images/';
            $image->move($destinationPath, $name);
            $postData['cover_image'] = $destinationPath . $name;
        }
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $image) {
                $name = uniqid() . 'img' . '.' . $image->getClientOriginalExtension();
                $destinationPath = 'post-images/';
                $image->move($destinationPath, $name);
                $data[] = $destinationPath . $name;
            }
            $postData['images'] = json_encode($data);
        }

        $post = Post::create($postData);
        return response()->json(['message' => 'Post created', 'post' => $post], 201);
    }
}
