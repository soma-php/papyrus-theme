<?php

namespace Papyrus\StarterTheme;

use Soma\Repository;
use Soma\ServiceProvider;
use Psr\Container\ContainerInterface;

use Papyrus\ShortcodeManager;
use Papyrus\Content\Page;
use Papyrus\Content\Image;
use Papyrus\Content\Shortcode;
use Papyrus\Content\Filesystem;
use Papyrus\StarterTheme\GalleryFilter;

class StarterThemeProvider extends ServiceProvider
{
    public function getExtensions() : array
    {
        return [
            'config' => function(Repository $config, ContainerInterface $c) {
                if (! $cacheConf = $config->get('cache', false)) {
                    $config->set('cache', [
                        'default' => 'files',
                        'stores' => [
                            'files' => [
                                'driver' => 'filesystem',
                                'directory' => ensure_dir_exists($c->get('paths')->get('cache').'/symfony'),
                            ],
                        ],
                    ]);
                }

                return $config;
            },
            'content.shortcodes' => function(ShortcodeManager $manager, ContainerInterface $c) {
                $manager->register(new Shortcode('gallery', ['type'=>'mosaic'], function($content = null, array $atts = []) {
                    $html = '<div data-gallery-shortcode class="gallery '.$atts['type'].'">';
                    $html .= '<div class="gallery-items" markdown="1">'.$content.'</div>';
                    $html .= '</div>';

                    return $html;
                }));

                return $manager;
            },
            'content.filesystem' => function(Filesystem $files, ContainerInterface $c) {
                Page::addFilter('gallery', new GalleryFilter());

                Image::addFilter(function (Image $image) {
                    $image->data['src'] = $image->src;

                    if ($image->srcset) {
                        $image->data['srcset'] = $image->srcset;
                        $image->data['sizes'] = 'auto';
                    }

                    if ($image->isLocal()) {
                        $image->srcset = $image->getSize('10');
                    }
                    else {
                        $image->srcset = "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==";
                    }

                    $image->sizes = false;
                    $image->class = trim($image->class." lazyload blur-up");                    

                    return $image;
                });

                return $files;
            },
        ];
    }
}

