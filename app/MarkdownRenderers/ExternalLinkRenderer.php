<?php

namespace Zeropingheroes\Lanager\MarkdownRenderers;

use InvalidArgumentException;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;
use League\CommonMark\Util\Xml;

class ExternalLinkRenderer implements InlineRendererInterface
{
    private $host;

    /**
     * ExternalLinkRenderer constructor.
     *
     * @param $host
     */
    public function __construct($host)
    {
        $this->host = $host;
    }

    /**
     * Render external links to open in a new window.
     *
     * @param AbstractInline $inline
     * @param ElementRendererInterface $htmlRenderer
     * @return HtmlElement
     * @throws InvalidArgumentException
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (! ($inline instanceof Link)) {
            throw new InvalidArgumentException('Incompatible inline type: '.get_class($inline));
        }

        $attributes = [];

        $attributes['href'] = Xml::escape($inline->getUrl(), true);

        if (isset($inline->attributes['title'])) {
            $attributes['title'] = Xml::escape($inline->data['title'], true);
        }

        if ($this->isExternalUrl($inline->getUrl())) {
            $attributes['target'] = '_blank';
        }

        return new HtmlElement('a', $attributes, $htmlRenderer->renderInlines($inline->children()));
    }

    /**
     * Check if supplied URL is to another site.
     *
     * @param $url
     * @return bool
     */
    private function isExternalUrl($url)
    {
        return parse_url($url, PHP_URL_HOST) != null && parse_url($url, PHP_URL_HOST) !== $this->host;
    }
}
