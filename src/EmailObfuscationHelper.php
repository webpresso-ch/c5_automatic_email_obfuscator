<?php 
namespace Concrete\Package\AutomaticEmailObfuscator\Src;

use Core;
use Concrete\Core\Page\Page;
use Symfony\Component\EventDispatcher\GenericEvent;

class EmailObfuscationHelper
{

    public function registerViewAssets()
    {
        Core::make('automatic_email_obfuscator/obfuscator')->registerViewAssets();
    }

    /**
     * Handles the event's content
     * obfuscates emails if current page is not admin area
     *
     * @param GenericEvent $event
     * @return GenericEvent
     */
    public function handle(GenericEvent $event)
    {
        $viewText = $event->getArgument('contents');
        $p = Page::getCurrentPage();

        if (!$p->isAdminArea()) {
            $event->setArgument('contents', $this->findAndObfuscate($viewText));
        }

        return $event;
    }

    public function findAndObfuscate($content)
    {
        // Regexps modified from the MIT licenced "Transparent Email Obfuscation" add-on:
        // http://www.concrete5.org/marketplace/addons/transparent-email-obfuscation/
        $helper = $this;

        $content = preg_replace_callback('/(<a[^>]*)(href=")(mailto:)([^"]+)([^>]*>)/',
            function ($matches) use ($helper) {
                return $helper->obfuscateMailto($matches);
            },
            $content
        );

        $content = preg_replace_callback('/\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]+\b(?!([^<]+)?>)/i',
            function ($matches) use ($helper) {
                return $helper->obfuscateMail($matches);
            },
            $content
        );

        return $content;
    }

    public function obfuscateMailto($matches)
    {
        $parts = array($matches[1], $matches[2], $matches[3], $matches[4], $matches[5]);
        $obfuscator = Core::make('automatic_email_obfuscator/obfuscator');
        
        $text = $parts[2] . $parts[3];
        $obfuscated = $obfuscator->obfuscateMailtoLinkHref($text);
        if ($text != $obfuscated) {
            $linkCls = "obfuscated-link";
            $hasClass = false;
            if (strpos($parts[0], "class=\"") !== false) {
                $parts[0] = str_replace("class=\"", "class=\"" . $linkCls . " ", $parts[0]);
                $hasClass = true;
            }
            if (strpos($parts[4], "class=\"") !== false) {
                $parts[4] = str_replace("class=\"", "class=\"" . $linkCls . " ", $parts[4]);
                $hasClass = true;
            }
            if (!$hasClass) {
                $parts[0] = $parts[0] . 'class="' . $linkCls . '" ';
            }
            return $parts[0] . $parts[1] . $obfuscated . $parts[4];
        }
        return $matches[0];
    }

    public function obfuscateMail($matches)
    {
        $obfuscator = Core::make('automatic_email_obfuscator/obfuscator');

        $text = $matches[0];
        $obfuscated = $obfuscator->obfuscateMail($text);
        if ($text != $obfuscated) {
            return '<span class="obfuscated-link-text">' . $obfuscated . '</span>';
        }
        return $matches[0];
    }

}