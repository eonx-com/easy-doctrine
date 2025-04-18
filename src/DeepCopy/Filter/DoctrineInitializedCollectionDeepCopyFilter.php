<?php
declare(strict_types=1);

namespace EonX\EasyDoctrine\DeepCopy\Filter;

use DeepCopy\Filter\Filter;
use DeepCopy\Reflection\ReflectionHelper;
use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\ArrayCollection;

final class DoctrineInitializedCollectionDeepCopyFilter implements Filter
{
    /**
     * @inheritdoc
     */
    public function apply($object, $property, $objectCopier): void
    {
        $reflectionProperty = ReflectionHelper::getProperty($object, $property);

        /** @var \Doctrine\Common\Collections\Collection<array-key, object> $oldCollection */
        $oldCollection = $reflectionProperty->getValue($object);
        $newCollection = new ArrayCollection();

        if ($oldCollection instanceof AbstractLazyCollection === false || $oldCollection->isInitialized()) {
            $newCollection = $oldCollection->map(static fn ($item) => $objectCopier($item));
        }

        $reflectionProperty->setValue($object, $newCollection);
    }
}
