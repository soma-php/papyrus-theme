<?php

namespace Papyrus\StarterTheme;

use SplFileInfo;
use DOMXPath;
use DOMDocument;
use Soma\Store;
use Papyrus\Content\Filter;
use Illuminate\Support\Str;

use Exception;

class GalleryFilter extends Filter
{
    public function before(string $markdown, Store &$meta, ?SplFileInfo $file)
    {
        // This could just as easily be set from a Page mixin
        $meta->set('gallery', Str::contains($markdown, ' data-gallery-shortcode '));

        return $markdown;
    }

    public function after(string $html, Store &$meta, ?SplFileInfo $file)
    {
        if ($meta->is('gallery')) {
            $dom = new DOMDocument;
            @ $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            // We must wrap every img with an anchor
            $finder = new DOMXPath($dom);
            $galleries = $finder->query("//div[@data-gallery-shortcode]");

            foreach ($galleries as $gallery) {
                $items = [];
                $definition = $finder->query('//div[contains(@class,"gallery-items")]', $gallery) ?: [];

                if ($definition === false || $definition->count() < 1) {
                    continue;
                }

                $definition = $definition->item(0);

                foreach($definition->getElementsByTagName('img') as $img){
                    $item = $dom->createElement('a');

                    $item->setAttribute('href', $img->getAttribute('src'));
                    $item->setAttribute('class', 'gallery-item');
                    $item->setAttribute('data-gallery', true);
                    $item->setAttribute('data-size', $img->getAttribute('data-size'));
                    $item->appendChild($img->cloneNode(true));

                    $items[] = $item;
                }

                // Empty and repopulate gallery
                // while ($gallery->hasChildNodes()) {
                //     $gallery->removeChild($gallery->firstChild);
                // }

                // query all child-nodes of $domElement
                $gallery->removeChild($definition);

                foreach ($items as $item) {
                    $gallery->appendChild($item);
                }
            }

            $html = @ $dom->saveHTML();
        }

        return $html;
    }
}