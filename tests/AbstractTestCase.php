<?php

declare(strict_types=1);

namespace EonX\EasyDoctrine\Tests;

use Closure;
use LogicException;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use ReflectionProperty;
use Symfony\Component\Filesystem\Filesystem;

/**
 * This class has for objective to provide common features to all tests without having to update
 * the class they all extend.
 */
abstract class AbstractTestCase extends TestCase
{
    use ProphecyTrait;

    /**
     * @var \Throwable|null
     */
    protected $thrownException = null;

    protected function tearDown(): void
    {
        $fs = new Filesystem();
        $var = __DIR__ . '/../var';

        if ($fs->exists($var)) {
            $fs->remove($var);
        }

        parent::tearDown();
    }

    /**
     * @throws \Exception
     */
    protected function assertThrownException(
        string $expectedException,
        int $code,
        ?string $previousException = null,
    ): void {
        self::assertNotNull($this->thrownException);

        if ($this->thrownException === null) {
            return;
        }

        if ($this->thrownException instanceof $expectedException === false) {
            throw $this->thrownException;
        }

        self::assertSame($code, $this->thrownException->getCode());

        if ($previousException === null) {
            self::assertNull($this->thrownException->getPrevious());
        }

        if ($previousException !== null) {
            self::assertTrue($this->thrownException->getPrevious() instanceof $previousException);
        }
    }

    protected function getPrivatePropertyValue(object $object, string $propertyName): mixed
    {
        $propertyReflection = $this->resolvePropertyReflection($object, $propertyName);
        $propertyReflection->setAccessible(true);

        return $propertyReflection->getValue($object);
    }

    protected function mock(mixed $target, ?callable $expectations = null): MockInterface
    {
        /** @var \Mockery\MockInterface $mock */
        $mock = Mockery::mock($target);

        if ($expectations !== null) {
            \call_user_func($expectations, $mock);
        }

        return $mock;
    }

    protected function safeCall(Closure $func): void
    {
        try {
            $func();
        } catch (\Throwable $exception) {
            $this->thrownException = $exception;
        }
    }

    protected function setPrivatePropertyValue(object $object, string $propertyName, mixed $value): void
    {
        $propertyReflection = $this->resolvePropertyReflection($object, $propertyName);
        $propertyReflection->setAccessible(true);
        $propertyReflection->setValue($object, $value);
    }

    private function resolvePropertyReflection(object $object, string $propertyName): ReflectionProperty
    {
        while (\property_exists($object, $propertyName) === false) {
            $object = \get_parent_class($object);

            if ($object === false) {
                throw new LogicException(\sprintf('The $%s property does not exist.', $propertyName));
            }
        }

        return new ReflectionProperty($object, $propertyName);
    }
}
