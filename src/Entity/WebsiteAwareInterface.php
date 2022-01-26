<?php

namespace App\Entity;

use App\Entity\Website;

interface WebsiteAwareInterface
{
    public function setWebsite(?Website $website): self;
    public function getWebsite(): ?Website;
}
