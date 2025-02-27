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

namespace ApiPlatform\Tests\Doctrine\Common\Filter;

/**
 * @author Julien Deniau <julien.deniau@mapado.com>
 * @author Vincent CHALAMON <vincentchalamon@gmail.com>
 */
trait SearchFilterTestTrait
{
    public function testGetDescription(): void
    {
        $filter = $this->buildSearchFilter($this->managerRegistry, [
            'id' => null,
            'name' => null,
            'alias' => null,
            'dummy' => null,
            'dummyDate' => null,
            'jsonData' => null,
            'arrayData' => null,
            'nameConverted' => null,
            'foo' => null,
            'relatedDummies.dummyDate' => null,
            'relatedDummy' => null,
        ]);

        $this->assertEquals([
            'id' => [
                'property' => 'id',
                'type' => 'int',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => false,
            ],
            'id[]' => [
                'property' => 'id',
                'type' => 'int',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => true,
            ],
            'name' => [
                'property' => 'name',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => false,
            ],
            'name[]' => [
                'property' => 'name',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => true,
            ],
            'alias' => [
                'property' => 'alias',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => false,
            ],
            'alias[]' => [
                'property' => 'alias',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => true,
            ],
            'dummy' => [
                'property' => 'dummy',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => false,
            ],
            'dummy[]' => [
                'property' => 'dummy',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => true,
            ],
            'dummyDate' => [
                'property' => 'dummyDate',
                'type' => \DateTimeInterface::class,
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => false,
            ],
            'dummyDate[]' => [
                'property' => 'dummyDate',
                'type' => \DateTimeInterface::class,
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => true,
            ],
            'jsonData' => [
                'property' => 'jsonData',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => false,
            ],
            'jsonData[]' => [
                'property' => 'jsonData',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => true,
            ],
            'arrayData' => [
                'property' => 'arrayData',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => false,
            ],
            'arrayData[]' => [
                'property' => 'arrayData',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => true,
            ],
            'name_converted' => [
                'property' => 'name_converted',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => false,
            ],
            'name_converted[]' => [
                'property' => 'name_converted',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => true,
            ],
            'relatedDummies.dummyDate' => [
                'property' => 'relatedDummies.dummyDate',
                'type' => \DateTimeInterface::class,
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => false,
            ],
            'relatedDummies.dummyDate[]' => [
                'property' => 'relatedDummies.dummyDate',
                'type' => \DateTimeInterface::class,
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => true,
            ],
            'relatedDummy' => [
                'property' => 'relatedDummy',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => false,
            ],
            'relatedDummy[]' => [
                'property' => 'relatedDummy',
                'type' => 'string',
                'required' => false,
                'strategy' => 'exact',
                'is_collection' => true,
            ],
        ], $filter->getDescription($this->resourceClass));
    }

    private function provideApplyTestArguments(): array
    {
        return [
            'exact' => [
                [
                    'id' => null,
                    'name' => null,
                ],
                [
                    'name' => 'exact',
                ],
            ],
            'exact (case insensitive)' => [
                [
                    'id' => null,
                    'name' => 'iexact',
                ],
                [
                    'name' => 'exact',
                ],
            ],
            'exact (case insensitive, with special characters)' => [
                [
                    'id' => null,
                    'name' => 'iexact',
                ],
                [
                    'name' => 'exact (special)',
                ],
            ],
            'exact (multiple values)' => [
                [
                    'id' => null,
                    'name' => 'exact',
                ],
                [
                    'name' => [
                        'CaSE',
                        'SENSitive',
                    ],
                ],
            ],
            'exact (multiple values; case insensitive)' => [
                [
                    'id' => null,
                    'name' => 'iexact',
                ],
                [
                    'name' => [
                        'CaSE',
                        'inSENSitive',
                    ],
                ],
            ],
            'invalid property' => [
                [
                    'id' => null,
                    'name' => null,
                ],
                [
                    'foo' => 'exact',
                ],
            ],
            'invalid values for relations' => [
                [
                    'id' => null,
                    'name' => null,
                    'relatedDummy' => null,
                    'relatedDummies' => null,
                ],
                [
                    'name' => ['foo'],
                    'relatedDummy' => ['foo'],
                    'relatedDummies' => [['foo']],
                ],
            ],
            'partial' => [
                [
                    'id' => null,
                    'name' => 'partial',
                ],
                [
                    'name' => 'partial',
                ],
            ],
            'partial (case insensitive)' => [
                [
                    'id' => null,
                    'name' => 'ipartial',
                ],
                [
                    'name' => 'partial',
                ],
            ],
            'partial (multiple values)' => [
                [
                    'id' => null,
                    'name' => 'partial',
                ],
                [
                    'name' => [
                        'CaSE',
                        'SENSitive',
                    ],
                ],
            ],
            'partial (multiple values; case insensitive)' => [
                [
                    'id' => null,
                    'name' => 'ipartial',
                ],
                [
                    'name' => [
                        'CaSE',
                        'inSENSitive',
                    ],
                ],
            ],
            'partial (multiple almost same values; case insensitive)' => [
                [
                    'id' => null,
                    'name' => 'ipartial',
                ],
                [
                    'name' => [
                        'blue car',
                        'Blue Car',
                    ],
                ],
            ],
            'start' => [
                [
                    'id' => null,
                    'name' => 'start',
                ],
                [
                    'name' => 'partial',
                ],
            ],
            'start (case insensitive)' => [
                [
                    'id' => null,
                    'name' => 'istart',
                ],
                [
                    'name' => 'partial',
                ],
            ],
            'start (multiple values)' => [
                [
                    'id' => null,
                    'name' => 'start',
                ],
                [
                    'name' => [
                        'CaSE',
                        'SENSitive',
                    ],
                ],
            ],
            'start (multiple values; case insensitive)' => [
                [
                    'id' => null,
                    'name' => 'istart',
                ],
                [
                    'name' => [
                        'CaSE',
                        'inSENSitive',
                    ],
                ],
            ],
            'end' => [
                [
                    'id' => null,
                    'name' => 'end',
                ],
                [
                    'name' => 'partial',
                ],
            ],
            'end (case insensitive)' => [
                [
                    'id' => null,
                    'name' => 'iend',
                ],
                [
                    'name' => 'partial',
                ],
            ],
            'end (multiple values)' => [
                [
                    'id' => null,
                    'name' => 'end',
                ],
                [
                    'name' => [
                        'CaSE',
                        'SENSitive',
                    ],
                ],
            ],
            'end (multiple values; case insensitive)' => [
                [
                    'id' => null,
                    'name' => 'iend',
                ],
                [
                    'name' => [
                        'CaSE',
                        'inSENSitive',
                    ],
                ],
            ],
            'word_start' => [
                [
                    'id' => null,
                    'name' => 'word_start',
                ],
                [
                    'name' => 'partial',
                ],
            ],
            'word_start (case insensitive)' => [
                [
                    'id' => null,
                    'name' => 'iword_start',
                ],
                [
                    'name' => 'partial',
                ],
            ],
            'word_start (multiple values)' => [
                [
                    'id' => null,
                    'name' => 'word_start',
                ],
                [
                    'name' => [
                        'CaSE',
                        'SENSitive',
                    ],
                ],
            ],
            'word_start (multiple values; case insensitive)' => [
                [
                    'id' => null,
                    'name' => 'iword_start',
                ],
                [
                    'name' => [
                        'CaSE',
                        'inSENSitive',
                    ],
                ],
            ],
            'invalid value for relation' => [
                [
                    'id' => null,
                    'name' => null,
                    'relatedDummy' => null,
                ],
                [
                    'relatedDummy' => 'exact',
                ],
            ],
            'IRI value for relation' => [
                [
                    'id' => null,
                    'name' => null,
                    'relatedDummy.id' => null,
                ],
                [
                    'relatedDummy.id' => '/related_dummies/1',
                ],
            ],
            'mixed IRI and entity ID values for relations' => [
                [
                    'id' => null,
                    'name' => null,
                    'relatedDummy' => null,
                    'relatedDummies' => null,
                ],
                [
                    'relatedDummy' => ['/related_dummies/1', '2'],
                    'relatedDummies' => '1',
                ],
            ],
            'nested property' => [
                [
                    'id' => null,
                    'name' => null,
                    'relatedDummy.symfony' => null,
                ],
                [
                    'name' => 'exact',
                    'relatedDummy.symfony' => 'exact',
                ],
            ],
            'empty nested property' => [
                [
                    'relatedDummy.symfony' => null,
                ],
                [
                    'relatedDummy.symfony' => [],
                ],
            ],
            'integer value' => [
                [
                    'age' => 'exact',
                ],
                [
                    'age' => 46,
                ],
            ],
            'related owned one-to-one association' => [
                [
                    'id' => null,
                    'relatedOwnedDummy' => null,
                ],
                [
                    'relatedOwnedDummy' => 1,
                ],
            ],
            'related owning one-to-one association' => [
                [
                    'id' => null,
                    'relatedOwningDummy' => null,
                ],
                [
                    'relatedOwningDummy' => 1,
                ],
            ],
        ];
    }
}
