# WireComments

WireComments is a Laravel package that provides a Livewire component for managing comments on any model using a `Commentable` trait. This package simplifies the process of displaying, creating, and paginating comments in a Laravel application.

![WireComments](https://i.imgur.com/7wdnbPy.png)

## Compatibility

WireComments is compatible with the following versions and dependencies:

| Feature                   | Supported Version |
|---------------------------|------------------|
| **Livewire**              | ^3.1             |
| **Minimum Laravel Version** | 10.0             |
 | **PHP**                   | 8.2              |

## Installation

To install the WireComments package, you can use Composer:

```bash
composer require matildevoldsen/wire-comments
```

## Publishing Assets

To install the WireComments package, use the following Artisan command:

```bash
php artisan wire-comments:install
```

This command will:

- Publish the service provider & migrations.
- Prompt you to publish the view files
- Prompt you to run the migrations
- Publish the configuration file

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

### Layout File

In your `app.blade.php` file, you need to include:

``
@livewireScriptConfig
``

### Including the Component in a View

You can include the `Comments` component in your Livewire views. For example, in a Blade view:

```blade
<livewire:comments :model="$post" />
```

Here, `$post` is an instance of your model that uses the `Commentable` trait.

#### Adding Emoji's

To add emojis, simply pass an array of emojis to the Livewire component:

```bladehtml
<livewire:comments :model="$post" :emojis="['👍', '👎', '❤️', '😂', '😯', '😢', '😡']" />
```

If the emojis are not set, reactions will be disabled.

#### Allowing Guests

Allowing guest commenting is disabled by default. To enable guest comments, pass the allowGuests parameter:

```bladehtml
<livewire:comments allowGuests :model="$card"/>
```

#### Max Depth for replies

To set the maximum depth for replies, use the maxDepth attribute. There is no limit on the maximum depth:

```bladehtml
<livewire:comments maxDepth="3" :model="$card"/>
```

#### Article Limits

You can limit the number of articles chunked by setting the `articlesLimit` value.

```bladehtml
<livewire:comments :model="$post" articlesLimit="5"/>
```

### Customizing the Views

If you need to customize the views, you can modify the published views located in `resources/views/vendor/wire-comments`. The main component view is `components/comments.blade.php`.

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
        el.innerHTML = `<time title="${dayjs().tz().to(dayjs.tz(datetime))}" datetime="${dayjs().tz().to(dayjs.tz(datetime))}">${dayjs().tz().to(dayjs.tz(datetime))}</time>`
    }

    setHumanTime()
    setInterval(setHumanTime, 30000)
}
```

### Markdown Editor

This package also includes a basis, markdown editor component.

````bladehtml
<x-markdown-editor :options="['b', 'i', 'h1', 'h2', 'ul', 'ol']" wire:model="markdownEditor"/>
````

Currently it only supports the following options:

- Bold
- Italic
- Heading 1
- Heading 2
- Unordered List
- Ordered List

## Conclusion

WireComments makes it easy to add commenting functionality to your Laravel models using Livewire. By following the steps above, you can quickly integrate and customize this package in your Laravel application.

For more detailed information, refer to the [WireComments GitHub repository](https://github.com/Matildevoldsen/wire-comments).
