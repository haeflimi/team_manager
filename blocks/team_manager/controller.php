<?php
namespace Concrete\Package\SeatMap\Block\TeamManager;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\User\Group\GroupList;
use File;
use Package;
use Concrete\Core\Attribute\Key\UserKey;
use Concrete\Core\Attribute\StandardSetManager;
use Concrete\Core\Attribute\SetFactory;
use UserList;
use User;
use Group;

class Controller extends BlockController
{
    protected $btTable = 'btTeamManager';
    protected $btInterfaceWidth = "1024";
    protected $btInterfaceHeight = "768";
    protected $btCacheBlockRecord = false;
    protected $btCacheBlockOutput = false;
    protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;
    protected $btCacheBlockOutputOnPost = false;
    protected $btCacheBlockOutputForRegisteredUsers = false;

    public function getBlockTypeName()
    {
        return t('Team Manager');
    }

    public function getBlockTypeDescription()
    {
        return t('Concrete5 Block that allows Users to create and manage teams using c5 groups.');
    }

    public function add()
    {
        $this->prepFormData();
    }

    public function edit()
    {
        $this->prepFormData();
    }

    public function action_claim_seat()
    {
        $currentUser = new User();
        $ui = $currentUser->getUserInfoObject();
        if(!empty($newSeatId = $this->post('claim_seat_id')) && $this->reservationAllowed()){
            $ui->setAttribute($this->class.'_reservation', $newSeatId);
        }
        $this->view();
    }

    public function save($args)
    {
        $pkg = Package::getByHandle('seat_map');
        $em = \ORM::entityManager();
        parent::save($args);
        if($args['class']){
            $service = $this->app->make('Concrete\Core\Attribute\Category\CategoryService');
            $categoryEntity = $service->getByHandle('user');
            $category = $categoryEntity->getController();
            $akHandle = $args['class'].'_reservation';
            $ak = $category->getByHandle($akHandle);
            $setHandle = 'seat_map_reservations';
            $sf = new SetFactory($em);
            $sm = new StandardSetManager($categoryEntity, $em);
            $set = $sf->getByHandle($setHandle);
            if(!is_object($set)){
                $sm->addSet($setHandle, t('Seat Map Reservations'), $pkg);
                $set = $sf->getByHandle($setHandle);
            }
            if(!is_object($ak)){
                $ak = new UserKey();
                $ak->setAttributeKeyHandle($akHandle);
                $ak->setAttributeKeyName(t('Seat Reservation Attribute for Map width Class: "%s"',$akHandle));
                $ak = $category->add('text', $ak, null, $pkg);
                $sm->addKey($set, $ak);
            }
        }
    }

    public function view()
    {

        $this->set('bID', $this->bID);
    }

    private function prepFormData()
    {
        $gl = new GroupList;
        $allGroups = [];
        $allGroups[0] = t('All');
        foreach($gl->getResults() as $group){
            $allGroups[$group->getGroupID()] = $group->getGroupPath();
        }
        $this->set('allGroups', $allGroups);
        $this->set('gID', $this->gID);
        $this->set('fID', $this->fID);
        $this->set('class', $this->class);
    }

    private function reservationAllowed()
    {
        $currentUser = new User();
        $group = Group::getByID($this->gID);
        $ui = $currentUser->getUserInfoObject();
        if( $currentUser->isLoggedIn() &&
            ($this->gID == 0 || $currentUser->inGroup($group))){
            return true;
        } else {
            return false;
        }
    }
}