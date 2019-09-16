<?php defined('C5_EXECUTE') or die("Access Denied.");
$al = Loader::helper('concrete/asset_library'); ?>
<div class="ccm-ui team-manager-block" data-bid="<?= $bID ?>">
    <fieldset>
        <div class="form-group">
            <label class="control-label">
                <?= t('Parent Group') ?>
            </label>
            <?=$form->select('gID', $allGroups, $gID);?>
            <p class="help-block">
                <?=t('Teams created by Users will be Subgroups of this group.')?>
            </p>
        </div>
        <div class="form-group">
            <label class="control-label">
                <?=$form->checkbox('allowTeamCreation', '1', ($allowTeamCreation == '1')?1:0 );?>
                <?= t('Allow Group Creation') ?>
            </label>
            <p class="help-block">
                <?=t('Allow Users to Create Teams')?>
            </p>
        </div>
    </fieldset>
</div>
