---
title: "Eloquent Order by HasMany Relationship: Three Ways"
excerpt: "Imagine you want to load the Model with its related many models, but sort those related results by some column in that related DB table. How to do that?"
date: "2025-09-14"
author: "tuantq"
category: "Laravel"
tags: [eloquent]
image: "/prezet/img/laravel.jpg"
draft: false
---
Imagine you want to load the Model with its related many models, but sort those related results by some column in that related DB table. How to do that?

Let's make it even more fun and take a two-level relationship example.

Scenario: you have User -> hasMany Posts -> hasMany Comments and you want to load the users with their posts and comments but comments ordered by most upvotes, the DB field comments.likes_count.

Model structure:

**app/Models/User.php:**

```php
class User extends Authenticatable
{
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
```

**app/Models/Post.php:**

```php
class Post extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
```

**app/Http/Controllers/Api/UserController.php:**

```php
class UserController extends Controller
{
    public function index()
    {
        return User::with('posts.comments')->get();
    }
}
```

Let's seed the DB with 1 user, 1 post, and 2 comments:

This is the result JSON:

```json
[
    {
        "id": 1,
        "name": "Prof. Tito Macejkovic MD",
        "email": "charlene.olson@example.org",
        "posts": [
            {
                "title": "Post 1",
                "comments": [
                    {
                        "id": 1,
                        "comment_text": "First comment",
                        "likes_count": 1,
                        "created_at": "2023-01-11T19:11:45.000000Z",
                    },
                    {
                        "id": 2,
                        "comment_text": "Second comment",
                        "likes_count": 3,
                        "created_at": "2023-01-11T19:11:45.000000Z",
                    }
                ]
            }
        ]
    }
]
```

Now, how do we order by comments.likes_count instead of the default ordering by comments.id?

Three ways.

## Option 1. Callback Function with orderBy

When loading the relationship - doesn't matter one or two levels - you can put it into an array of key-value, where the key is the relationship name and the value is a callback function with extra conditions, like orderBy or whatever you want.

```php
class UserController extends Controller
{
    public function index()
    {
        return User::with(['posts.comments' => function($query) {
            $query->orderBy('comments.likes_count', 'desc');
        }])->get();
    }
}
```

Result:

```json
[
    {
        "id": 1,
        "name": "Prof. Tito Macejkovic MD",
        "email": "charlene.olson@example.org",
        "posts": [
            {
                "title": "Post 1",
                "comments": [
                    {
                        "id": 2,
                        "comment_text": "Second comment",
                        "likes_count": 3,
                        "created_at": "2023-01-11T19:11:45.000000Z",
                    },
                    {
                        "id": 1,
                        "comment_text": "First comment",
                        "likes_count": 1,
                        "created_at": "2023-01-11T19:11:45.000000Z",
                    }
                ]
            }
        ]
    }
]
```

## Option 2. Model: ALWAYS Order By Field X

Maybe you want that relationship to always be ordered by that field?

You can do this:

**app/Models/Post.php:**

```php
class Post extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('likes_count', 'desc');
    }
}
```

Then, in the Controller, you just leave it as it was, **User::with('posts.comments')->get()** and the results would be ordered correctly, anyway.

But if you do want to make an exception and order it differently in some cases, you can do it like this, in Controller:

```php
public function index()
{
    return User::with(['posts.comments' => function($query) {
        $query->reorder('comments.id', 'desc');
    }])->get();
}
```

## Option 3. Separate Ordered Relationship

If you want to use this ordering often but not always, another option is to create a separate relation function:

**app/Models/Post.php:**

```php
class Post extends Model
{
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentsByMostLikes()
    {
        return $this->hasMany(Comment::class)->orderBy('likes_count', 'desc');

        // Or alternatively, even...
        return $this->comments()->orderBy('likes_count', 'desc');
    }
}
```

**app/Http/Controllers/Api/UserController.php:**

```php
class UserController extends Controller
{
    public function index()
    {
        return User::with('posts.commentsByMostLikes')->get();
    }
}
```



