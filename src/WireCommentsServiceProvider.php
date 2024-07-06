<?php

namespace WireComments;

use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use WireComments\Commands\InstallCommand;

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
                '2024_06_15_create_comments_table',
                '2024_06_15_create_reactions_table',
                '2024_06_17_add_guest_to_comments_table',
                '2024_06_17_add_guest_to_reactions_table',
            ])
            ->hasCommand(InstallCommand::class);
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
