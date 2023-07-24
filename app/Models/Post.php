<?php

namespace App\Models;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Cache;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class Post
{
    public $title;

    public $excerpt;

    public $date;

    public $body;

    public $slug;

    public function __construct($title, $excerpt, $date, $body, $slug)
    {
        $this->title = $title;
        $this->excerpt = $excerpt;
        $this->date = $date;
        $this->body = $body;
        $this->slug = $slug;
    }

    public static function all()
    {

        return cache()->rememberForever('posts.all', function () {

            return collect(File::files(resource_path("posts")))
            ->map(fn($file) => YamlFrontMatter::parseFile($file))
            ->map(fn($document) => new Post(
                $document->matter('title'),
                $document->matter('excerpt'),
                    $document->matter('date'),
                    $document->body(),
                    $document->matter('slug')
                    ))
                    ->sortByDesc('date');
                    
        });
                    
    }

    public static function find($slug)
    {
        return static::all()->firstWhere('slug', $slug);
    }

    public static function findOrFail($slug)
    {
        // of all the blog posts find the one with a slug that matchs the one that was requested
        $post = static::all()->firstWhere('slug', $slug);

        if (! $post ) {
            throw new ModelNotFoundException();

        }

        return $post;

    }
}