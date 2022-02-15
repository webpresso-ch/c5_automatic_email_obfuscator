<?php 
namespace Concrete\Package\AutomaticEmailObfuscator\Src\EmailObfuscator;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Http\ResponseAssetGroup;

class HtmlObfuscator extends AbstractObfuscator
{

    public function registerViewAssets()
    {
        $al = AssetList::getInstance();
        $al->register(
            'javascript', 'automatic_email_obfuscator/html', 'js/email_deobfuscator_html.js', array(),
            'automatic_email_obfuscator'
        );

        $r = ResponseAssetGroup::get();
        $r->requireAsset('javascript', 'automatic_email_obfuscator/html');
    }

    public function obfuscateMail($email)
    {
        $ret = "";
        $email = str_replace("@", "(at)", $email);
        for ($i = 0; $i < strlen($email); $i++) {
            $ret .= "&#" . ord($email[$i]) . ";";
        }
        return $ret;
    }

    public function obfuscateMailtoLinkHref($href)
    {
        $href = $this->obfuscateMail(str_replace("mailto:", "", $href));
        return "#MAIL:" . $href;
    }

}