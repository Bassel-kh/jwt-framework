<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2017 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Jose\Bundle\Signature\DependencyInjection\Source;

use Jose\Component\Signature\JWSBuilderFactory;
use Jose\Component\Signature\JWSBuilder as JWSBuilderService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class JWSBuilder.
 */
final class JWSBuilder extends AbstractSource
{
    /**
     * {@inheritdoc}
     */
    public function name(): string
    {
        return 'jws_builders';
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        foreach ($configs[$this->name()] as $name => $itemConfig) {
            $service_id = sprintf('jose.jws_builder.%s', $name);
            $definition = new Definition(JWSBuilderService::class);
            $definition
                ->setFactory([new Reference(JWSBuilderFactory::class), 'create'])
                ->setArguments([$itemConfig['signature_algorithms']])
                ->addTag('jose.jws_builder')
                ->setPublic($itemConfig['is_public']);

            $container->setDefinition($service_id, $definition);
        }
    }
}
