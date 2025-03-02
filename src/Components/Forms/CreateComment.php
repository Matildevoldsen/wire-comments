<?php

namespace WireComments\Components\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CreateComment extends Form
{
    #[Validate('required|string')]
    public string $body = '';
}
