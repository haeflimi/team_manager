<?php defined('C5_EXECUTE') or die("Access Denied.");
$wh = Core::make('helper/form/user_selector')
;?>

<div class="team-manager-block">
    <h3><?=t('My Teams')?></h3>
    <div class="team-manager-team-list">

        <?php foreach($myTeams as $key => $group):?>
            <div class="list-group team-list-item" data-gID="<?=$key?>">
                <a class="list-group-heading d-flex justify-content-between align-items-center"  data-toggle="collapse" href="#team-collapse-<?=$group->getGroupID();?>">
                    <span class="h4" title="<?=t('Show team members')?>"><?=$group->getGroupName();?> </span>&nbsp;
                    <span class="badge badge-primary badge-pill"  title="<?=t('Number of team members')?>"><?=$group->getGroupMembersNum()?>&nbsp;<i class="fa fa-user"></i></span>
                    <span class="badge badge-success badge-pill"  title="<?=t('Wins')?>"><?=10?>&nbsp;<i class="fa fa-trophy"></i></span>
                    <span class="badge badge-danger badge-pill"  title="<?=t('Losses')?>"><?=10?>&nbsp;<i class="fa fa-close"></i></span>
                </a>
                <div class="list-group-item-collapse-wrapper">
                    <div id="team-collapse-<?=$group->getGroupID()?>" class="list-group-item-collapse collapse">
                        <?php foreach($group->getGroupMembers() as $user): ?>
                            <div class="list-group-item">
                                <a data-toggle="modal" data-target="#modal" data-source="/members/profile/<?=$user->getUserID()?>"  class="btn btn-outline-primary btn-round"><i class="fa fa-user"></i>  <?=$user->getUserName()?></a>
                            </div>
                        <?php endforeach; ?>
                        <div class="list-group-footer">
                            <form class="team-manager-invite-form form-inline" action="<?=$this->action('inviteUsers')?>" method="POST">
                                <input type="hidden" name="ccm_token" value="<?=Core::make('token')->generate('inviteUsers');?>"/>
                                <div class="input-group">
                                    <?=$wh->quickSelect('inviteUsers',false);?>
                                    <div class="input-group-append">
                                        <button class="input-group-append btn btn-primary pull-right" title="<?=t('Invite Member')?>"><i class="fa fa-user-plus"></i></button>
                                    </div>
                                </div>
                            </form>
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


