<?php 
namespace Concrete\Package\AutomaticEmailObfuscator\Src\EmailObfuscator;

use Core;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Http\ResponseAssetGroup;

class RtlObfuscator extends AbstractObfuscator
{

    public function registerViewAssets()
    {
        $al = AssetList::getInstance();
        $al->register(
            'javascript', 'automatic_email_obfuscator/rtl', 'js/email_deobfuscator_rtl.js', array(),
            'automatic_email_obfuscator'
        );

        $r = ResponseAssetGroup::get();
        $r->requireAsset('javascript', 'automatic_email_obfuscator/rtl');
    }

    public function obfuscateMail($email)
    {
        $ret = '<span style="unicode-bidi:bidi-override; direction: rtl;">';
        $ret .= strrev($email);
        $ret .= '</span>';
        return $ret;
    }

    public function obfuscateMailtoLinkHref($href)
    {
        return "#MAIL:" . strrev(str_replace("mailto:", "", $href));
    }

}
