<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogData;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LikeCounter;
use function MongoDB\BSON\toJSON;

class BlogDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $blogTypeId = $request->id;
        $currentUser = Auth::user();

        $data = BlogData::with('likeCounter', 'comments')->where(function ($query) use ($blogTypeId) {
            if ($blogTypeId) {
                $query->where('blog_type_id', $blogTypeId);
            }
        })->get();

        // Добавление информации о счетчиках лайков и дизлайков
        foreach ($data as $blog) {
            $likeCount = $blog->likeCounter->whereIn('like', [1])->count();
            $dislikeCount = $blog->likeCounter->whereIn('dislike', [1])->count();

            $blog->like_count = $likeCount;
            $blog->dislike_count = $dislikeCount;

            // Проверка, поставил ли текущий авторизованный пользователь лайк или дизлайк
            if ($currentUser) {
                $likeCounter = $blog->likeCounter->where('user_id', $currentUser->id)->first();
                if ($likeCounter) {
                    $blog->liked_by_user = $likeCounter->like == 1;
                    $blog->disliked_by_user = $likeCounter->dislike == 1;
                } else {
                    $blog->liked_by_user = false;
                    $blog->disliked_by_user = false;
                }
            } else {
                $blog->liked_by_user = false;
                $blog->disliked_by_user = false;
            }
        }

        return $data;
    }

    public function addLike(Request $request, $blogId)
    {
        // Получить текущего пользователя
        $user = Auth::user();

        // Проверить, существует ли уже запись для данного блога и пользователя
        $likeCounter = LikeCounter::where('blog_data_id', $blogId)
            ->where('user_id', $user->id)
            ->first();

        if ($likeCounter) {
            // Проверить, если запись уже лайкнута пользователем, то ничего не делать
            if ($likeCounter->like == 1) {
                $likeCounter->like = 0;
                $likeCounter->save();
                return response()->json(['message' => 'Вы удалили лайк'], 200);
            }

            // Обновить значение like в существующей записи
            $likeCounter->like = 1;
            $likeCounter->dislike = 0;
            $likeCounter->save();
        } else {
            // Создать новую запись в LikeCounter
            $newLikeCounter = new LikeCounter();
            $newLikeCounter->blog_data_id = $blogId;
            $newLikeCounter->user_id = $user->id;
            $newLikeCounter->like = 1;
            $newLikeCounter->dislike = 0;
            $newLikeCounter->save();
        }

        return response()->json(['message' => 'Лайк успешно добавлен'], 200);
    }

    public function addDislike(Request $request, $blogId)
    {
        // Получить текущего пользователя
        $user = Auth::user();

        // Проверить, существует ли уже запись для данного блога и пользователя
        $likeCounter = LikeCounter::where('blog_data_id', $blogId)
            ->where('user_id', $user->id)
            ->first();

        if ($likeCounter) {
            // Проверить, если запись уже дизлайкнута пользователем, то ничего не делать
            if ($likeCounter->dislike == 1) {
                $likeCounter->dislike = 0;
                $likeCounter->save();
                return response()->json(['message' => 'Вы удалили дизлайк'], 200);
            }

            // Обновить значение dislike в существующей записи
            $likeCounter->like = 0;
            $likeCounter->dislike = 1;
            $likeCounter->save();
        } else {
            // Создать новую запись в LikeCounter
            $newLikeCounter = new LikeCounter();
            $newLikeCounter->blog_data_id = $blogId;
            $newLikeCounter->user_id = $user->id;
            $newLikeCounter->like = 0;
            $newLikeCounter->dislike = 1;
            $newLikeCounter->save();
        }

        return response()->json(['message' => 'Дизлайк успешно добавлен'], 200);
    }

    public function addComment(Request $request, $blogId)
    {
        $user = Auth::user();
        $data = $request->input('data');
        $username = $request->input('username');
        if (isset($request->comment_id)) {
            $comments = Comments::findOrFail($request->comment_id);
            $comments->data = $data;
            $comments->save();
            return response()->json(['message' => 'Комментарий успешно изменен'], 200);
        } else {
            $newComment = new Comments();
            $newComment->blog_data_id = $blogId;
            $newComment->user_id = $user->id;
            $newComment->data = $data;
            $newComment->username = $username;
            $newComment->save();
            return response()->json(['message' => 'Комментарий успешно сохранен'], 200);
        };
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = new BlogData();
        $data->fill($request->all());
        $data->save();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = BlogData::findOrFail($id);
        $data->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = BlogData::findOrFail($id);
        $data->delete();
    }
}
