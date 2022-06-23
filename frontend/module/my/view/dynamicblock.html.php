<div class="cell dynamic-container">
  <h3 class='text-center'><?php echo $lang->my->latestDynamic;?></h3>
  <?php if(empty($actions)): ?>
  <div class='empty-tip'><?php echo $lang->my->emptyTip;?></div>
  <?php else:?>
  <div class='panel-body scrollbar-hover'>
    <ul class="timeline timeline-tag-left no-margin">
      <?php $users = $this->dao->select('account,realname')->from(TABLE_USER)->where('deleted')->eq(0)->andWhere('account')->in(array_column($actions, 'actor'))->fetchPairs('account', 'realname');?>
      <?php foreach($actions as $action):?>
      <?php
          $userRealname = zget($users, $action->actor);
          if($action->objectLink) $actionHtml = sprintf($lang->action->dynamicInfo, $action->date, $userRealname, $action->actionLabel, $action->objectLabel, $action->objectLink, $action->objectName, $action->objectName);
          if(!$action->objectLink) $actionHtml = sprintf($lang->action->noLinkDynamic, $action->date, $action->objectName, $userRealname, $action->actionLabel, $action->objectLabel, $action->objectName);
      ?>
      <li class="<?php echo $action->major ? 'active' : '';?>"><div><?php echo $actionHtml;?></div></li>
      <?php endforeach;?>
    </ul>
  </div>
  <?php endif;?>
</div>

