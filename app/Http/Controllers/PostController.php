<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; // Assurez-vous d'avoir un modèle Post

class PostController extends Controller
{
    public function index()
    {
        // $posts = Post::all();
        return view('posts.index');
    }

    public function create()
    {
        // Vérification supplémentaire dans le code
        $this->authorize('creer-articles');

        return view('posts.create');
    }

    public function destroy(Post $post)
    {
        // On vérifie si l'utilisateur a le droit de supprimer
        $this->authorize('supprimer-articles');

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Article supprimé');
    }
}
