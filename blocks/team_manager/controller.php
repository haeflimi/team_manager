<?php
namespace Concrete\Package\TeamManager\Block\TeamManager;

use Concrete\Core\Block\BlockController;
use Concrete\Core\User\Group\GroupList;
use Concrete\Core\User\User;
use Concrete\Core\User\Group\Group;
use Concrete\Core\Package\Package;
use Core;


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
    protected $btHandle = 'team_manager';

    public function __construct($obj = null)
    {
        parent::__construct($obj);
        $app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
        // The EntityManager is used to work with Doctrine Entities
        $this->em = $app->make('Doctrine\ORM\EntityManager');
    }

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
        $this->prepForm();
    }

    public function edit()
    {
        $this->prepForm();
    }

    public function save($args)
    {
        parent::save($args);
    }

    public function action_createTeam()
    {
        // if the data is valid, we process it
        $errors = $this->validate($this->post(), 'createTeam');
        if ($errors === true) {
            $th = Core::make('helper/text');
            $parentGroup = Group::getByID($this->gID);
            $teamName = $th->sanitize($this->post('teamName'));
            $u = new User();
            $ui = $u->getUserInfoObject();
            $g = Group::add($teamName, t('Team generated by Team Manager'), $parentGroup, Package::getByHandle('team_manager'));
            $u->enterGroup($g);
        } else {
            $this->set('errors', $errors);
        };

        $this->view();
    }

    public function action_inviteUser()
    {
        $errors = $this->validate($this->post(), 'inviteUser');
        if ($errors === true) {
            $u = User::getByUserID($this->post('inviteUser'));
            $g = Group::getByID($this->post('inviteGroup'));
            // @todo for now we just add new users directly, it might be better to send invites to ask if they want to join
            $u->enterGroup($g);
        } else {
            $this->set('errors', $errors);
        };

        $this->view();
    }

    public function action_leaveTeam($uID, $gID, $token)
    {
        $errors = $this->validate([
            'uID' => $uID,
            'gID' => $gID,
            'ccm_token' => $token
        ], 'leaveTeam');

        if($errors === true){
            $u = User::getByUserID($uID);
            $g = Group::getByID($gID);
            $u->exitGroup($g);
        } else {
            $this->set('errors', $errors);
        };

        $this->view();
    }

    public function view()
    {
        $parentGroup = Group::getByID($this->gID);
        $allTeams = $parentGroup->getChildGroups();
        $myTeams = [];
        foreach($allTeams as $team){
            $myTeams[$team->getGroupID()] = $team;
        }

        $this->set('bID', $this->bID);
        $this->set('myTeams', $myTeams);
    }

    private function prepForm()
    {
        $gl = new GroupList;
        $allGroups = [];
        $allGroups[0] = t('All');
        foreach($gl->getResults() as $group){
            $allGroups[$group->getGroupID()] = $group->getGroupPath();
        }
        $this->set('allGroups', $allGroups);
    }

    /**
     * Run when the add or edit forms are submitted. This should return true if
     * validation is successful or a Concrete\Core\Error\Error() object if it fails.
     *
     * @param  $data
     * @return bool|Error
     */
    public function validate($data, $action = false)
    {
        $errors = new \Concrete\Core\Error\Error();

        // we want to use a token to validate each call in order to protect from xss and request forgery
        $token = \Core::make("token");
        if($action && !$token->validate($action)){
            $errors->add('Invalid Request, token must be valid.');
        }

        // validate the action addPonts
        if($action == 'createTeam'){
            if(empty($data['teamName'])){
                $errors->add('No Team Name set.');
            }
            if(Group::getByName($data['teamName'])){
                $errors->add('A User Group with that Name already exists.');
            }
        }

        if ($errors->has()) {
            return $errors;
        }

        return true;
    }
}