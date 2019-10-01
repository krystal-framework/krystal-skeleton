<?php

namespace User\Collection;

use Krystal\Stdlib\ArrayCollection;

final class GenderCollection extends ArrayCollection
{
    /**
     * {@inheritDoc}
     */
    protected $collection = array(
        '0' => 'Prefer not to say',
        '1' => 'Male',
        '2' => 'Female'
    );
}
