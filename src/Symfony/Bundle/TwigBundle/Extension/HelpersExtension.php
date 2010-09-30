<?php

namespace Symfony\Bundle\TwigBundle\Extension;

use Symfony\Component\Templating\Engine;
use Symfony\Bundle\TwigBundle\TokenParser\HelperTokenParser;
use Symfony\Bundle\TwigBundle\TokenParser\TransTokenParser;
use Symfony\Bundle\TwigBundle\TokenParser\TransChoiceTokenParser;

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class HelpersExtension extends \Twig_Extension
{
    /**
     * Returns the token parser instance to add to the existing list.
     *
     * @return array An array of Twig_TokenParser instances
     */
    public function getTokenParsers()
    {
        return array(
            // {% javascript 'bundles/blog/js/blog.js' %}
            new HelperTokenParser('javascript', '<js> [with <arguments:array>]', 'javascripts', 'add'),

            // {% javascripts %}
            new HelperTokenParser('javascripts', '', 'javascripts', 'render'),

            // {% stylesheet 'bundles/blog/css/blog.css' with ['media': 'screen'] %}
            new HelperTokenParser('stylesheet', '<css> [with <arguments:array>]', 'stylesheets', 'add'),

            // {% stylesheets %}
            new HelperTokenParser('stylesheets', '', 'stylesheets', 'render'),

            // {% asset 'css/blog.css' %}
            new HelperTokenParser('asset', '<location>', 'assets', 'getUrl'),

            // {% route 'blog_post' with ['id': post.id] %}
            new HelperTokenParser('route', '<route> [with <arguments:array>]', 'router', 'generate'),

            // {% render 'BlogBundle:Post:list' with ['limit': 2], ['alt': 'BlogBundle:Post:error'] %}
            new HelperTokenParser('render', '<template> [with <attributes:array>[, <options:array>]]', 'actions', 'render'),

            // {% flash 'notice' %}
            new HelperTokenParser('flash', '<name>', 'session', 'flash'),

            // {% trans "Symfony is great!" %}
            new TransTokenParser(),

            // {% transchoice count %}
            //     {0} There is no apples|{1} There is one apple|]1,Inf] There is {{ count }} apples
            // {% endtranschoice %}
            new TransChoiceTokenParser(),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'symfony.helpers';
    }
}