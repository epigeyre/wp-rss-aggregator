<?php

namespace RebelCode\Wpra\Core\Modules;

use Psr\Container\ContainerInterface;
use RebelCode\Wpra\Core\Twig\WpraExtension;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extensions\DateExtension;
use Twig\Extensions\I18nExtension;
use Twig\Extensions\TextExtension;
use Twig\Loader\FilesystemLoader;

/**
 * The Twig module for WP RSS Aggregator.
 *
 * @since [*next-version*]
 */
class TwigModule implements ModuleInterface
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getServices()
    {
        return [
            /*
             * The template loader for Twig.
             *
             * @since [*next-version*]
             */
            'wpra/twig' => function (ContainerInterface $c) {
                return new Environment(
                    $c->get('wpra/twig/loader'),
                    $c->get('wpra/twig/paths')
                );
            },
            /*
             * The template loader for Twig.
             *
             * @since [*next-version*]
             */
            'wpra/twig/loader' => function (ContainerInterface $c) {
                return new FilesystemLoader($c->get('wpra/twig/paths'));
            },
            /*
             * The template paths for Twig.
             *
             * @since [*next-version*]
             */
            'wpra/twig/paths' => function (ContainerInterface $c) {
                return [WPRSS_TEMPLATES];
            },
            /*
             * The options for Twig.
             *
             * @since [*next-version*]
             */
            'wpra/twig/options' => function (ContainerInterface $c) {
                $debug = (defined('WP_DEBUG') && WP_DEBUG);

                return [
                    'debug' => $debug,
                    'cache' => $debug ? $c->get('wpra/twig/cache') : false,
                ];
            },
            /*
             * The path to the Twig cache.
             *
             * @since [*next-version*]
             */
            'wpra/twig/cache' => function (ContainerInterface $c) {
                return get_temp_dir() . 'wprss/twig-cache';
            },
            /*
             * The extensions to use for WPRA's Twig instance.
             *
             * @since [*next-version*]
             */
            'wpra/twig/extensions' => function (ContainerInterface $c) {
                return [
                    $c->get('wpra/twig/extensions/i18n'),
                    $c->get('wpra/twig/extensions/date'),
                    $c->get('wpra/twig/extensions/text'),
                    $c->get('wpra/twig/extensions/debug'),
                    $c->get('wpra/twig/extensions/wpra'),
                ];
            },
            /*
             * The i18n extension for Twig.
             *
             * @since [*next-version*]
             */
            'wpra/twig/extensions/i18n' => function (ContainerInterface $c) {
                return new I18nExtension();
            },
            /*
             * The date extension for Twig.
             *
             * @since [*next-version*]
             */
            'wpra/twig/extensions/date' => function (ContainerInterface $c) {
                return new DateExtension();
            },
            /*
             * The text extension for Twig.
             *
             * @since [*next-version*]
             */
            'wpra/twig/extensions/text' => function (ContainerInterface $c) {
                return new TextExtension();
            },
            /*
             * The debug extension for Twig.
             *
             * @since [*next-version*]
             */
            'wpra/twig/extensions/debug' => function (ContainerInterface $c) {
                return new DebugExtension();
            },
            /*
             * The custom WPRA extension for Twig.
             *
             * @since [*next-version*]
             */
            'wpra/twig/extensions/wpra' => function (ContainerInterface $c) {
                return new WpraExtension();
            },
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function run(ContainerInterface $c)
    {
        $twig = $c->get('wpra/twig');

        foreach ($c->get('wpra/twig/extensions') as $extension) {
            $twig->addExtension($extension);
        }
    }
}
