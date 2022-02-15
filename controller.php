<?php 
namespace Concrete\Package\AutomaticEmailObfuscator;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Core;
use Concrete\Core\Package\Package;
use Concrete\Package\AutomaticEmailObfuscator\Src\PackageServiceProvider;

class Controller extends Package
{

    protected $pkgHandle = 'automatic_email_obfuscator';
    protected $appVersionRequired = '5.7.3.1';
    protected $pkgVersion = '2.0.1';

    public function getPackageName()
    {
        return t("Automatic Email Obfuscator");
    }

    public function getPackageDescription()
    {
        return t("Automatically obfuscates all the e-mail addresses on your site to a form that most spambots cannot read.");
    }

    public function install()
    {
        parent::install();
    }

    public function on_start()
    {
        $app = Core::getFacadeApplication();
        $sp = new PackageServiceProvider($app);
        $sp->register();
        $sp->registerEvents();
    }

}