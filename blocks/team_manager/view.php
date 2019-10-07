<?php defined('C5_EXECUTE') or die("Access Denied.");
$wh = Core::make('helper/form/user_selector');
$me = new User();
;?>

<div class="team-manager-block">
    <h3><?=t('My Teams')?></h3>
    <div class="team-manager-team-list">

        <?php foreach($myTeams as $key => $group):?>
            <div class="list-group team-list-item" data-gID="<?=$key?>">
                <a class="list-group-heading d-flex justify-content-between align-items-center"  data-toggle="collapse" href="#team-collapse-<?=$group->getGroupID();?>">
                    <span class="h4" title="<?=t('Show team members')?>"><?=$group->getGroupName();?> </span>&nbsp;
                </a>
                <div class="list-group-item-collapse-wrapper">

                    <div id="team-collapse-<?=$group->getGroupID()?>" class="list-group-item-collapse collapse">
                        <?php foreach($group->getGroupMembers() as $user): ?>
                            <div class="list-group-item">
                                <a data-toggle="modal" data-target="#modal" data-source="/members/profile/<?=$user->getUserID()?>"  class="btn btn-outline-primary btn-round"><i class="fa fa-user"></i>  <?=$user->getUserName()?></a>
                            </div>
                        <?php endforeach; ?>

                        <div class="list-group-footer">
                            <form class="team-manager-invite-form form-inline" action="<?=$this->action('inviteUser')?>" method="POST">
                                <input type="hidden" name="inviteGroup" value="<?=$group->getGroupID()?>">
                                <input type="hidden" name="ccm_token" value="<?=Core::make('token')->generate('inviteUser');?>"/>
                                <div class="input-group">
                                    <?=$wh->quickSelect('inviteUser',false, ['placeholder'=>t('Select User to Invite')]);?>
                                    <div class="input-group-append">
                                        <button class="input-group-append btn btn-primary pull-right" title="<?=t('Invite Member')?>"><i class="fa fa-user-plus"></i></button>
                                    </div>
                                </div>
                            </form>
                            <a class="btn btn-danger btn-sm pull-right color-danger" href="<?=$this->action('leaveTeam', [$me->getUserID(), $group->getGroupID(),Core::make('token')->generate('leaveUser')])?>"><i class="fa fa-close"></i> Leave Team</a>
                        </div>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
        <?php if($allowTeamCreation):?>
            <div class="list-group team-list-item create-item">
                <a class="list-group-heading d-flex justify-content-between align-items-center"  data-toggle="collapse" href="#team-collapse-create">
                    <span class="h5" title="<?=t('Show team members')?>"><?=t('Create Team')?> </span>
                </a>
                <div class="list-group-item-collapse-wrapper">
                    <div id="team-collapse-create" class="list-group-item-collapse collapse">
                        <div class="list-group-footer">
                            <form class="team-manager-team-form" action="<?=$this->action('createTeam')?>" method="POST">

                                <input type="hidden" name="ccm_token" value="<?=Core::make('token')->generate('createTeam');?>"/>
                                <div class="input-group">
                                    <?=$form->text('teamName', ['class'=>'input-group-prepend']);?>
                                    <button class="btn btn-primary input-group-button input-group-append" title="<?=t('Create Team')?>">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif;?>
        <?php if($errors):;?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong><br/>
                <?=$errors->output();?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>


