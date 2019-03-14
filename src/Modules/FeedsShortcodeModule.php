<?php

namespace RebelCode\Wpra\Core\Modules;

use Psr\Container\ContainerInterface;
use RebelCode\Wpra\Core\Shortcodes\FeedsShortcode;

/**
 * The feeds shortcode for WP RSS Aggregator.
 *
 * @since [*next-version*]
 */
class FeedsShortcodeModule implements ModuleInterface
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getServices()
    {
        return [
            'wpra/shortcode/feeds/handler' => function (ContainerInterface $c) {
                return new FeedsShortcode(
                    $c->get('wpra/settings/dataset'),
                    $c->get('wpra/templates/feeds/master_template')
                );
            },
            'wpra/shortcode/feeds/names' => function (ContainerInterface $c) {
                return [
                    'wp_rss_aggregator',
                    'wp-rss-aggregator'
                ];
            }
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function run(ContainerInterface $c)
    {
        $this->registerShortcode($c);
    }

    /**
     * Registers the shortcode with WordPress.
     *
     * @since [*next-version*]
     *
     * @param ContainerInterface $c The container.
     */
    protected function registerShortcode(ContainerInterface $c)
    {
        $handler = $c->get('wpra/shortcode/feeds/handler');

        foreach ($c->get('wpra/shortcode/feeds/names') as $name) {
            add_shortcode($name, $handler);
        }
    }
}
