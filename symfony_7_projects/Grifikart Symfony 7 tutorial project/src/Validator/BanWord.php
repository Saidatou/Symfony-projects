<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]

final class BanWord extends Constraint
{
    // public $banWords = ['span', 'viagra'];

    // public string $message = 'The string "{{ string }}" contains an illegal character: it can only contain letters or numbers.';

    // You can use #[HasNamedArguments] to make some constraint options required.
    // All configurable options must be passed to the constructor.
    public function __construct(
        public string $message='This contains a banned word "{{banWord}}" ',
        public array $banWords = ['spam', 'viagra'],
        ?array $groups = null,
        mixed $payload = null

        // public string $mode = 'strict',
    ) 
    {
        parent::__construct([], $groups, $payload);
    }
}
