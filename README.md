# WireComments

WireComments is a Laravel package that provides a Livewire component for managing comments on any model using a `Commentable` trait. This package simplifies the process of displaying, creating, and paginating comments in a Laravel application.

## Installation

To install the WireComments package, you can use Composer:

```bash
composer require matildevoldsen/wire-comments
```

## Publishing Assets

After installing the package, you need to publish the configuration, migrations, and view files. You can do this using the following Artisan command:

```bash
 php artisan vendor:publish --provider="WireComments\WireCommentsServiceProvider"
```

This command will publish the following:

- Configuration file to `config/wire-comments.php`
- Migrations to `database/migrations`
- Views to `resources/views/vendor/wire-comments`

## Running Migrations

Run the migrations to create the necessary tables for the comments:

```bash
php artisan migrate
```

## Usage

To use the WireComments component, follow these steps:

### Setting Up the Model

Ensure your model uses the `Commentable` trait. For example, if you have a `Post` model:

```php
use Illuminate\Database\Eloquent\Model;
use WireComments\Traits\Commentable;

class Post extends Model
{
    use Commentable;

    // Other model methods and properties
}
```

### Including the Component in a View

You can include the `Comments` component in your Livewire views. For example, in a Blade view:

```blade
<livewire:comments :model="$post" />
```

Here, `$post` is an instance of your model that uses the `Commentable` trait.

### Customizing the Views

If you need to customize the views, you can modify the published views located in `resources/views/vendor/wire-comments`. The main component view is `components/comments.blade.php`.

### Using the Comment Form

The `Comments` component provides a form for creating new comments. Ensure that your application is set up to handle authentication and that users are logged in to post comments.

## Advanced Configuration

You can further configure the package by modifying the published configuration file located at `config/wire-comments.php`. This file contains various settings that you can adjust according to your application's needs.

## Example Blade View

Here is an example of how to include the `Comments` component in a Blade view for a `Post` model:

```blade
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <p>{{ $post->body }}</p>

        <livewire:comments :model="$post" />
    </div>
@endsection
```

## Directives

WireComments provides you with a alphine directive for date-time formatting. 

This directive uses the `dayjs` library to format the date-time. To use this directive, you need to include the `dayjs` library in your project.


```javascript
// app.js
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm.js';
import humanDate from "../../vendor/matildevoldsen/wire-comments/resources/js/directives/humanDate.js";

Alpine.directive('human-date', humanDate)

Livewire.start()
```

### Create your own custom directive

You can easily create your own custom directive to fit your needs. Here is an example on how to do it with `dayjs`.

```javascript
//humanDate.js

import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'

dayjs.extend(relativeTime)
dayjs.extend(utc)
dayjs.extend(timezone)

dayjs.tz.setDefault('UTC')

export default (el) => {
    let datetime = el.getAttribute('datetime')

    if (!datetime) {
        return
    }

    const setHumanTime = () => {
        el.innerText = dayjs().tz().to(dayjs.tz(datetime))
    }

    setHumanTime()
    setInterval(setHumanTime, 30000)
}
```

## Conclusion

WireComments makes it easy to add commenting functionality to your Laravel models using Livewire. By following the steps above, you can quickly integrate and customize this package in your Laravel application.

For more detailed information, refer to the [WireComments GitHub repository](https://github.com/Matildevoldsen/wire-comments).
