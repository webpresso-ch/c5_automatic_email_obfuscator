<?php 
namespace Concrete\Package\AutomaticEmailObfuscator\Src\EmailObfuscator;

interface ObfuscatorInterface
{

    public function obfuscateMail($email);
    public function obfuscateMailtoLinkHref($href);

}