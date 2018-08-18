<?php

namespace Zeropingheroes\Lanager\MarkdownRenderers;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;
use League\CommonMark\Util\Xml;

class ResponsiveImageRenderer implements InlineRendererInterface
{
    private $classes;

    /**
     * ExternalLinkRenderer constructor
     *
     * @param $classes
     */
    public function __construct($classes)
    {
        $this->classes = $classes;
    }

    /**
     * Render images with responsive classes added
     *
     * @param AbstractInline $inline
     * @param ElementRendererInterface $htmlRenderer
     * @return HtmlElement
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!($inline instanceof Image)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . get_class($inline));
        }

        $attrs = array();

        $attrs['src'] = Xml::escape($inline->getUrl(), true);

        if ($inline->firstChild()->getContent()) {
            $attrs['alt'] = Xml::escape($inline->firstChild()->getContent(), true);
            $inline->firstChild()->detach();
        }

        $attrs['class'] = implode('', $this->classes);

        return new HtmlElement('img', $attrs, $htmlRenderer->renderInlines($inline->children()));
    }
}
