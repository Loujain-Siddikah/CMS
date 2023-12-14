<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ArticleRequest;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Validation\ValidationException;


class ArticleController extends Controller
{
    use JsonResponseTrait;
    
    public function add(ArticleRequest $request){
 
        if(Auth::user()->hasPermissionTo('add_article'))
        {
            try{ 
                $article = Article::create([
                'user_id' => Auth::user()->id,
                'title' => $request->title,
                'content' => $request->content,
                ]);
                return $this->jsonSuccessResponse('Article added successfully');
            }catch(ValidationException $e){
                return $this->jsonErrorResponse('Validation error',402,['errors' => $e->errors()]);
            }
        }else{
            return $this->jsonErrorResponse('you dont have the permission',403);
        }
    }


    public function update(ArticleRequest $request, $id){
        if(Auth::user()->hasPermissionTo('edit_article'))
        {
            try{
                $article = Article::findOrFail($id);
                $article->update([
                    'title' => $request->title,
                    'content' => $request->content, 
                ]);
                return $this->jsonSuccessResponse('Article edited successfully');
            }catch(ValidationException $e){
                return $this->jsonErrorResponse('Validation error',402,['errors' => $e->errors()]);
            }
        }else{
            return $this->jsonErrorResponse('you dont have the permission',403);
        }
    }

    public function delete($id){
        if(Auth::user()->hasPermissionTo('delete_article'))
        {
            $article = Article::findOrFail($id);
            $article->delete();
            return $this->jsonSuccessResponse('Article deleted successfully');
        }else{
            return $this->jsonErrorResponse('you dont have the permission',403);
        }
    }

    public function usersArticles(){
        $articles = Article::with('user')->get();
        return $this->jsonSuccessResponse('successful',['articles'=>$articles]);
    }

    public function userArticles($id){
        $user = User::find($id);
        if(!$user){
            return $this->jsonErrorResponse('user not found',404);

        }else{
            $userWithArticles = User::with('articles')->find($user->id);
            $userArticles = $userWithArticles->articles;
            return $this->jsonSuccessResponse('successful',['userArticles'=>$userArticles]);
        }
      
    }
}
