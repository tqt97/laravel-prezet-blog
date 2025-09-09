<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Str::macro('readingTimeInMinutes', function (string $subject, $wordsPerMinute = 200) {
            $readTime = intval(ceil(Str::wordCount(strip_tags($subject)) / $wordsPerMinute));
            $wordCounts = Str::wordCount(strip_tags($subject));
            $pluralWordLabel = Str::plural('word', $wordCounts);
            $pluralTimeLabel = Str::plural('minute', $readTime);

            return $wordCounts.' '.$pluralWordLabel.' - '.$readTime.' '.$pluralTimeLabel.' to reading';
        });
    }
}
