<?php

namespace WireComments;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use WireComments\Commands\SkeletonCommand;

class WireCommentsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('wire-comments')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations([
                'create_comments_table',
                'create_reactions_table',
            ])
            ->hasCommand(SkeletonCommand::class);
    }

    public function packageBooted(): void
    {
        Livewire::component('comment-chunk', \WireComments\Components\CommentChunk::class);
        Livewire::component('comment-item', \WireComments\Components\CommentItem::class);
        Livewire::component('comments', \WireComments\Components\Comments::class);
        Livewire::component('comment-reactions', \WireComments\Components\CommentReactions::class);

        Blade::component('markdown-editor', \WireComments\Components\MarkdownEditor::class);
    }
}
