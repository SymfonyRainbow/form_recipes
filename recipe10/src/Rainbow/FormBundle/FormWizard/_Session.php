<?php

namespace Rainbow\FormBundle\FormWizard;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Session {

    /** @var SessionInterface */
    protected $session;

    function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

}
