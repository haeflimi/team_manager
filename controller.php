<?php
namespace Concrete\Package\TeamManager;

use Concrete\Core\View\View;
use Concrete\Core\Package\Package;
use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\User\Group\Group;
use Core;
use Concrete\Core\Support\Facade\Config;
use Concrete\Core\Support\Facade\Events;

class Controller extends Package
{
    protected $pkgHandle = 'team_manager';
    protected $appVersionRequired = '8.4';
    protected $pkgVersion = '0.91';

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

        // When there is a Invite Notification for the User, we want to show it until aknowledged/ responded
        /*$view->addFooterItem(
            Core::make('helper/concrete/ui')->notify(
                array(
                    // type
                    // - info: blue background and light blue text
                    // - success: green background and white text
                    // - error: red background and white text
                    'type' => 'info',
                    // icon
                    // - display Font Awesome icon
                    'icon' => 'fa fa-internet-explorer',
                    // title
                    // - h4 title text
                    'title' => t('Old Browser Alert'),
                )));*/
    }

    public function install()
    {
        $pkg = parent::install();
        $gName = $this->getPackageName();
        // Create Parent Group for creating Teams under
        $group = Group::getByName($gName);
        if(empty($group)){
            $group = Group::add($gName, t('Default Group for Team Manager Blocks'), $pkg);
        }

        $ci = new ContentImporter();
        $ci->importContentFile($pkg->getPackagePath() . '/install.xml');
    }

    public function upgrade()
    {
        parent::upgrade();
    }

    public function uninstall(){
        parent::uninstall();
    }
}