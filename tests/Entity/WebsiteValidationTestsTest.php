<?php

namespace App\Tests\Entity;

use App\Entity\Website;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WebsiteValidationTestsTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();


        $rate = new Website();

        /** @var ValidatorInterface */
        $validator = $kernel->getContainer()->get('validator');
        $errors = $validator->validate($rate);

        dump(count($errors));

        $this->assertNotSame(0, count($errors));
    }
}
