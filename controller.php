<?php
namespace Concrete\Package\TeamManager;

use Concrete\Core\View\View;
use Package,
    Concrete\Core\Backup\ContentImporter,
    Group,
    Core,
    Config,
    Events;

class Controller extends Package
{
    protected $pkgHandle = 'team_manager';
    protected $appVersionRequired = '8.4';
    protected $pkgVersion = '0.9';

    public function getPackageName()
    {
        return t('Team Manager');
    }

    public function getPackageDescription()
    {
        return t('Concrete5 Block that allows Users to create and manage teams (using Groups) and invite other Users.');
    }

    public function on_start()
    {
        // we need this Asset in order to be able to use Pnotify.
        // @todo mabe in the future there will be a sepparate asset for the notification system!? A lot overhead like this.
        $view = new View();
        $view->requireAsset('core/app');
    }

    public function install()
    {
        $pkg = parent::install();
        // Create Parent Group for creating Teams under
        $group = Group::getByName($pkg->getPackageName());
        if(empty($group)){
            $group = Group::add($pkg->getPackageName(), false, $pkg);
        }

        $ci = new ContentImporter();
        $ci->importContentFile($pkg->getPackagePath() . '/install.xml');
    }

    public function upgrade()
    {
        parent::upgrade();
    }

    public function uninstall(){

    }
}