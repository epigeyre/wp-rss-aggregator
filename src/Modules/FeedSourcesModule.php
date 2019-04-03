<?php

namespace RebelCode\Wpra\Core\Modules;

use Psr\Container\ContainerInterface;
use RebelCode\Wpra\Core\Modules\FeedSources\RenderFeedSourceContentHandler;
use RebelCode\Wpra\Core\Modules\Handlers\AddCapabilitiesHandler;
use RebelCode\Wpra\Core\Modules\Handlers\AddCptMetaCapsHandler;
use RebelCode\Wpra\Core\Modules\Handlers\MultiHandler;
use RebelCode\Wpra\Core\Modules\Handlers\RegisterCptHandler;

/**
 * The feed sources module for WP RSS Aggregator.
 *
 * @since [*next-version*]
 */
class FeedSourcesModule implements ModuleInterface
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getFactories()
    {
        return [
            /*
             * The name of the feed sources CPT.
             *
             * @since [*next-version*]
             */
            'wpra/feeds/sources/cpt/name' => function () {
                return 'wprss_feed';
            },
            /*
             * The labels for the feed sources CPT.
             *
             * @since [*next-version*]
             */
            'wpra/feeds/sources/cpt/labels' => function () {
                return [
                    'name' => __('Feed Sources', 'wprss'),
                    'singular_name' => __('Feed Source', 'wprss'),
                    'add_new' => __('Add New', 'wprss'),
                    'all_items' => __('Feed Sources', 'wprss'),
                    'add_new_item' => __('Add New Feed Source', 'wprss'),
                    'edit_item' => __('Edit Feed Source', 'wprss'),
                    'new_item' => __('New Feed Source', 'wprss'),
                    'view_item' => __('View Feed Source', 'wprss'),
                    'search_items' => __('Search Feeds', 'wprss'),
                    'not_found' => __('No Feed Sources Found', 'wprss'),
                    'not_found_in_trash' => __('No Feed Sources Found In Trash', 'wprss'),
                    'menu_name' => __('RSS Aggregator', 'wprss'),
                ];
            },
            /*
             * The capability for the feed sources CPT.
             *
             * @since [*next-version*]
             */
            'wpra/feeds/sources/cpt/capability' => function () {
                return 'feed_source';
            },
            /*
             * The full arguments for the feed sources CPT.
             *
             * @since [*next-version*]
             */
            'wpra/feeds/sources/cpt/args' => function (ContainerInterface $c) {
                return [
                    'exclude_from_search' => true,
                    'publicly_queryable' => false,
                    'show_in_nav_menus' => false,
                    'show_in_admin_bar' => true,
                    'public' => true,
                    'show_ui' => true,
                    'query_var' => 'feed_source',
                    'menu_position' => 100,
                    'show_in_menu' => true,
                    'rewrite' => [
                        'slug' => 'feeds',
                        'with_front' => false,
                    ],
                    'capability_type' => $c->get('wpra/feeds/sources/cpt/capability'),
                    'map_meta_cap' => true,
                    'supports' => ['title'],
                    'labels' => $c->get('wpra/feeds/sources/cpt/labels'),
                    'menu_icon' => 'dashicons-rss',
                ];
            },
            /*
             * The user roles that have the feed sources CPT capabilities.
             *
             * @since [*next-version*]
             */
            'wpra/feeds/sources/cpt/capability_roles' => function () {
                return ['administrator', 'editor'];
            },
            /*
             * The capability for the feed sources CPT admin menu.
             *
             * @since [*next-version*]
             */
            'wpra/feeds/sources/menu/capability' => function () {
                return 'manage_feed_settings';
            },
            /*
             * The user roles that have the feed sources CPT admin menu capabilities.
             *
             * @since [*next-version*]
             */
            'wpra/feeds/sources/menu/capability_roles' => function (ContainerInterface $c) {
                // Identical to CPT roles
                return $c->get('wpra/feeds/sources/cpt/capability_roles');
            },
            /*
             * The handler that registers the feed sources CPT.
             *
             * @since [*next-version*]
             */
            'wpra/feeds/sources/handlers/register_cpt' => function (ContainerInterface $c) {
                return new RegisterCptHandler(
                    $c->get('wpra/feeds/sources/cpt/name'),
                    $c->get('wpra/feeds/sources/cpt/args')
                );
            },
            /*
             * The handler that renders a feed source's content on the front-end.
             *
             * @since [*next-version*]
             */
            'wpra/feeds/sources/handlers/render_content' => function (ContainerInterface $c) {
                return new RenderFeedSourceContentHandler($c->get('wpra/templates/feeds/master_template'));
            },
            /*
             * The handler that adds the capability that allows users to see and access the admin menu.
             *
             * @since [*next-version*]
             */
            'wpra/feeds/sources/handlers/add_menu_capabilities' => function (ContainerInterface $c) {
                return new AddCapabilitiesHandler(
                    $c->get('wp/roles'),
                    $c->get('wpra/feeds/sources/menu/capability_roles'),
                    [$c->get('wpra/feeds/sources/menu/capability')]
                );
            },
            /*
             * The handler that adds the CPT's capabilities to the appropriate user roles.
             *
             * @since [*next-version*]
             */
            'wpra/feeds/sources/handlers/add_cpt_capabilities' => function (ContainerInterface $c) {
                return new AddCptMetaCapsHandler(
                    $c->get('wp/roles'),
                    $c->get('wpra/feeds/sources/cpt/capability_roles'),
                    $c->get('wpra/feeds/sources/cpt/capability')
                );
            },
            /*
             * The full handler for adding all capabilities related to the feed sources CPT.
             *
             * @since [*next-version*]
             */
            'wpra/feeds/sources/add_capabilities_handler' => function (ContainerInterface $c) {
                return new MultiHandler([
                    $c->get('wpra/feeds/sources/handlers/add_menu_capabilities'),
                    $c->get('wpra/feeds/sources/handlers/add_cpt_capabilities'),
                ]);
            }
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getExtensions()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function run(ContainerInterface $c)
    {
        add_action('init', $c->get('wpra/feeds/sources/handlers/register_cpt'));
        add_filter('the_content', $c->get('wpra/feeds/sources/handlers/render_content'));
        add_action('admin_init', $c->get('wpra/feeds/sources/add_capabilities_handler'));
    }
}
