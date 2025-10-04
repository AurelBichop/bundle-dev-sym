<?php

namespace Aurel\ObjectTranslationBundle\Tests\Unit;

use Aurel\ObjectTranslationBundle\TranslatedObject;
use PHPUnit\Framework\TestCase;

class TranslatedObjectTest extends TestCase
{
    public function testCanAccessUnderlyingObject()
    {
        $object = new TranslatedObject(new ObjectForTranslationStub());

        $this->assertSame('value1', $object->prop1);
        $this->assertTrue(isset($object->prop1), 'Public property should be accessible');
        $this->assertFalse(isset($object->prop2), 'Private properties should not be accessible');
        $this->assertSame('value2', $object->prop2());
        $this->assertSame('value3', $object->getProp3());
    }
}

class ObjectForTranslationStub
{
    public string $prop1='value1';
    private string $prop2='value2';
    private string $prop3='value3';

    public function prop2(): string
    {
        return $this->prop2;
    }

    public function getProp3(): string
    {
        return $this->prop3;
    }
}
