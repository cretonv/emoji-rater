<?php

namespace App\Entity;

interface ProductAwareInterface
{
    public function setProduct(?Product $product): self;
    public function getProduct(): ?Product;
}
