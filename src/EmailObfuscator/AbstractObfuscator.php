<?php 
namespace Concrete\Package\AutomaticEmailObfuscator\Src\EmailObfuscator;

abstract class AbstractObfuscator implements ObfuscatorInterface
{

    public function registerViewAssets()
    {
        // Register assets if needed
    }

    public function obfuscateMail($email)
    {
        return $email;
    }

    public function obfuscateMailtoLinkHref($href)
    {
        return $href;
    }

}