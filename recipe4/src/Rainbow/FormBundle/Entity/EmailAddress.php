<?php

namespace Rainbow\FormBundle\Entity;

final class EmailAddress
{
    private $localPart;
    private $domainPart;
    private $validFrom;

    public function __construct($localPart, $domainPart = 'hotmail.com', \DateTime $validFrom)
    {
        $this->localPart = $localPart;
        $this->domainPart = $domainPart;
        $this->validFrom = $validFrom;

        // Make sure we add a valid local and domain part by validating them.
        if (! filter_var($this->localPart . "@" . $this->domainPart, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Does not look like a valid email address");
        }
    }

    public function getDomainPart()
    {
        return $this->domainPart;
    }

    public function getLocalPart()
    {
        return $this->localPart;
    }

    public function getValidFrom()
    {
        return $this->validFrom;
    }
}
