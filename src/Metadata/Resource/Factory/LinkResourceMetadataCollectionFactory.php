<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatform\Metadata\Resource\Factory;

use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Resource\ResourceMetadataCollection;

/**
 * @experimental
 */
final class LinkResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(private readonly LinkFactoryInterface $linkFactory, private readonly ?ResourceMetadataCollectionFactoryInterface $decorated = null)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $resourceMetadataCollection = new ResourceMetadataCollection($resourceClass);
        if ($this->decorated) {
            $resourceMetadataCollection = $this->decorated->create($resourceClass);
        }

        foreach ($resourceMetadataCollection as $i => $resource) {
            $graphQlOperations = [];
            foreach ($resource->getGraphQlOperations() ?? [] as $graphQlOperation) {
                $relationLinks = $this->linkFactory->createLinksFromRelations($graphQlOperation);
                $attributeLinks = $this->linkFactory->createLinksFromAttributes($graphQlOperation);
                $links = [];
                foreach ($attributeLinks as $link) {
                    $links[] = $this->linkFactory->completeLink($link);
                }
                $links = $this->mergeLinks($relationLinks, $links);

                $graphQlOperations[$graphQlOperation->getName()] = $graphQlOperation->withLinks($links);
            }

            $resource = $resource->withGraphQlOperations($graphQlOperations);
            $resourceMetadataCollection[$i] = $resource;
        }

        return $resourceMetadataCollection;
    }

    /**
     * @param Link[] $links
     * @param Link[] $toMergeLinks
     *
     * @return Link[]
     */
    private function mergeLinks(array $links, array $toMergeLinks): array
    {
        $classLinks = [];
        foreach ($links as $link) {
            $classLinks[$link->getToClass()][$link->getFromProperty()] = $link;
        }

        foreach ($toMergeLinks as $link) {
            if (null !== $prevLink = $classLinks[$link->getToClass()][$link->getFromProperty()] ?? null) {
                $classLinks[$link->getToClass()][$link->getFromProperty()] = $prevLink->withLink($link);

                continue;
            }
            $classLinks[$link->getToClass()][$link->getFromProperty()] = $link;
        }

        return array_reduce($classLinks, function (array $carry, array $item) {
            return array_merge($carry, array_values($item));
        }, []);
    }
}
