<?php

namespace App\Entity;

use App\Entity\Website;

interface WebsiteAwareInterface
{
    public function getWebsite(): ?Website;
}
