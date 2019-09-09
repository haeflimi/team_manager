<?php defined('C5_EXECUTE') or die("Access Denied.");
$this->requireAsset('core/app');?>


<?php $this->addFooterItem(
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
        )));
