<?php

namespace Gubarev\Bundle\FeedBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('rss_feader');

        $rootNode->children()
            ->integerNode('per_curl_request')
                ->defaultValue(10)
            ->end()
        ->end();

        return $treeBuilder;
    }
}
