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
