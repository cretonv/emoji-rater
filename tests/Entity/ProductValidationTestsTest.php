<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductValidationTestsTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();


        $rate = new Product();

        /** @var ValidatorInterface */
        $validator = $kernel->getContainer()->get('validator');
        $errors = $validator->validate($rate);

        dump(count($errors));

        $this->assertNotSame(0, count($errors));
        //$this->assertCount();
        //$routerService = static::getContainer()->get('router');
        //$myCustomService = static::getContainer()->get(CustomService::class);
    }
}
