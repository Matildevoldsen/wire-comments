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
        $prefix = config('wire-comments.prefix');
        Livewire::component($prefix . 'comment-chunk', \WireComments\Components\CommentChunk::class);
        Livewire::component($prefix . 'comment-item', \WireComments\Components\CommentItem::class);
        Livewire::component($prefix . 'comments', \WireComments\Components\Comments::class);
        Livewire::component($prefix . 'comment-reactions', \WireComments\Components\CommentReactions::class);

        Blade::component($prefix . 'markdown-editor', \WireComments\Components\MarkdownEditor::class);
    }
}
