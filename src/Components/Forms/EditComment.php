<?php

namespace WireComments\Components\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class EditComment extends Form
{
    #[Validate('required')]
    public string $body = '';
}
