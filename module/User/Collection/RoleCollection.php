<?php

namespace User\Collection;

use Krystal\Stdlib\ArrayCollection;

final class RoleCollection extends ArrayCollection
{
    /* Most common roles */
    const ROLE_USER = 1;
    const ROLE_ADMIN = 2;
    const ROLE_MODERATOR = 3;

    /**
     * {@inheritDoc}
     */
    protected $collection = array(
        self::ROLE_USER => 'User',
        self::ROLE_ADMIN => 'Administrator',
        self::ROLE_MODERATOR => 'Moderator'
    );
}
