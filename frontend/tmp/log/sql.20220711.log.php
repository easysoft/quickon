<?php
 die();
?>
20220711 11:09:19: 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT acl FROM `q_group` WHERE name  = 'guest'
  SELECT module, method FROM `q_group` AS t1  LEFT JOIN `q_grouppriv` AS t2  ON t1.id = t2.`group`  WHERE t1.name  = 'guest'
  SELECT * FROM `q_config` WHERE owner IN ('system','guest') ORDER BY `id` 

20220711 11:11:19: 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT acl FROM `q_group` WHERE name  = 'guest'
  SELECT module, method FROM `q_group` AS t1  LEFT JOIN `q_grouppriv` AS t2  ON t1.id = t2.`group`  WHERE t1.name  = 'guest'
  SELECT * FROM `q_config` WHERE owner IN ('system','guest') ORDER BY `id` 

20220711 11:14:38: 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT * FROM `q_config` WHERE owner IN ('system','') ORDER BY `id` 

20220711 11:14:38: user-login-Lw==
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','') ORDER BY `id` 
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT * FROM `q_user` WHERE account  = '' LIMIT 1 

20220711 11:14:38: admin-init
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','') ORDER BY `id` 
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT * FROM `q_user` WHERE account  = '' LIMIT 1 

20220711 11:14:51: admin-init
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','') ORDER BY `id` 
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT * FROM `q_user` WHERE account  = '' LIMIT 1 
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  UPDATE `q_company` SET  `admins` = ',zhouyq,' WHERE id  = '1'
  REPLACE `q_user` SET `account` = 'zhouyq',`realname` = 'zhouyq',`password` = 'f4f5e816ce7a0393c57729dc160fce5f',`gender` = 'f'
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 

20220711 11:14:51: 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','') ORDER BY `id` 

20220711 11:14:51: user-login-Lw==
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','') ORDER BY `id` 
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT * FROM `q_user` WHERE account  = 'zhouyq' LIMIT 1 

20220711 11:14:52: misc-checkUpdate-
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','') ORDER BY `id` 

20220711 11:14:52: user-login-Lw==
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','') ORDER BY `id` 
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT * FROM `q_user` WHERE account  = 'zhouyq' LIMIT 1 

20220711 11:14:52: misc-checkUpdate-
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','') ORDER BY `id` 

20220711 11:15:03: user-refreshRandom
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','') ORDER BY `id` 

20220711 11:15:03: user-login
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','') ORDER BY `id` 
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT * FROM `q_user` WHERE account  = 'zhouyq' LIMIT 1 
  SELECT locked FROM `q_user` WHERE account  = 'zhouyq'
  SELECT * FROM `q_user` WHERE account  = 'zhouyq' AND  deleted  = '0'
  UPDATE `q_user` SET  visits = visits + 1, `ip` = '192.168.50.182', `last` = '1657509303' WHERE account  = 'zhouyq'
  UPDATE `q_user` SET  `fails` = '0', `locked` = '0000-00-00 00:00:00' WHERE account  = 'zhouyq'
  SELECT t1.acl FROM `q_group` AS t1  LEFT JOIN `q_usergroup` AS t2  ON t1.id=t2.`group`  WHERE t2.account  = 'zhouyq' AND  t1.vision  = 'rnd' AND  t1.role  != 'limited'
  SELECT module, method FROM `q_group` AS t1  LEFT JOIN `q_usergroup` AS t2  ON t1.id = t2.`group`  LEFT JOIN `q_grouppriv` AS t3  ON t2.`group` = t3.`group`  WHERE t2.account  = 'zhouyq' AND  t1.vision  = 'rnd'
  SELECT `group` FROM `q_usergroup` WHERE `account` = 'zhouyq' 
  INSERT INTO `q_action` SET `objectType` = 'user',`objectID` = '1',`actor` = 'zhouyq',`action` = 'login',`date` = '2022-07-11 11:15:03',`extra` = '',`vision` = 'rnd',`comment` = ''

20220711 11:15:03: 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:15:04: my
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT COUNT(*) AS recTotal FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' 
  SELECT instance.* FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' ORDER BY instance.`id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')
  SELECT * FROM `q_action` WHERE 1  AND  date  > '2022-07-11' AND  date  < '2022-07-12' AND  action  NOT IN ('disconnectxuanxuan','loginxuanxuan') ORDER BY `date` desc 
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT id, account AS name FROM `q_user` WHERE id IN ('1')
  SELECT account,realname FROM `q_user` WHERE deleted  = '0' AND  account IN ('zhouyq')

20220711 11:15:07: store
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:15:08: store-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:15:14: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:15:19: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:15:24: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:15:29: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:15:34: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:15:39: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:15:44: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:15:49: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:15:54: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:15:59: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:16:01: my
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT COUNT(*) AS recTotal FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' 
  SELECT instance.* FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' ORDER BY instance.`id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')
  SELECT * FROM `q_action` WHERE 1  AND  date  > '2022-07-11' AND  date  < '2022-07-12' AND  action  NOT IN ('disconnectxuanxuan','loginxuanxuan') ORDER BY `date` desc 
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT id, account AS name FROM `q_user` WHERE id IN ('1')
  SELECT account,realname FROM `q_user` WHERE deleted  = '0' AND  account IN ('zhouyq')

20220711 11:16:01: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:16:02: my
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT COUNT(*) AS recTotal FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' 
  SELECT instance.* FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' ORDER BY instance.`id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')
  SELECT * FROM `q_action` WHERE 1  AND  date  > '2022-07-11' AND  date  < '2022-07-12' AND  action  NOT IN ('disconnectxuanxuan','loginxuanxuan') ORDER BY `date` desc 
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT id, account AS name FROM `q_user` WHERE id IN ('1')
  SELECT account,realname FROM `q_user` WHERE deleted  = '0' AND  account IN ('zhouyq')

20220711 11:16:05: store
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:16:06: store-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:16:12: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:16:17: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:16:22: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:16:27: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:16:32: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:16:37: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:16:42: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:16:47: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:16:52: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:16:57: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:17:02: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:17:07: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:17:12: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:17:17: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:17:22: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:17:25: 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT acl FROM `q_group` WHERE name  = 'guest'
  SELECT module, method FROM `q_group` AS t1  LEFT JOIN `q_grouppriv` AS t2  ON t1.id = t2.`group`  WHERE t1.name  = 'guest'
  SELECT * FROM `q_config` WHERE owner IN ('system','guest') ORDER BY `id` 

20220711 11:17:27: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:17:32: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:17:35: my
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT COUNT(*) AS recTotal FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' 
  SELECT instance.* FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' ORDER BY instance.`id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')
  SELECT * FROM `q_action` WHERE 1  AND  date  > '2022-07-11' AND  date  < '2022-07-12' AND  action  NOT IN ('disconnectxuanxuan','loginxuanxuan') ORDER BY `date` desc 
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT id, account AS name FROM `q_user` WHERE id IN ('1')
  SELECT account,realname FROM `q_user` WHERE deleted  = '0' AND  account IN ('zhouyq')

20220711 11:17:35: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:17:36: my
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT COUNT(*) AS recTotal FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' 
  SELECT instance.* FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' ORDER BY instance.`id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')
  SELECT * FROM `q_action` WHERE 1  AND  date  > '2022-07-11' AND  date  < '2022-07-12' AND  action  NOT IN ('disconnectxuanxuan','loginxuanxuan') ORDER BY `date` desc 
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT id, account AS name FROM `q_user` WHERE id IN ('1')
  SELECT account,realname FROM `q_user` WHERE deleted  = '0' AND  account IN ('zhouyq')

20220711 11:17:39: store
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:17:40: store-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:17:43: my
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT COUNT(*) AS recTotal FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' 
  SELECT instance.* FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' ORDER BY instance.`id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')
  SELECT * FROM `q_action` WHERE 1  AND  date  > '2022-07-11' AND  date  < '2022-07-12' AND  action  NOT IN ('disconnectxuanxuan','loginxuanxuan') ORDER BY `date` desc 
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT id, account AS name FROM `q_user` WHERE id IN ('1')
  SELECT account,realname FROM `q_user` WHERE deleted  = '0' AND  account IN ('zhouyq')

20220711 11:17:43: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:17:44: my
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT COUNT(*) AS recTotal FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' 
  SELECT instance.* FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' ORDER BY instance.`id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')
  SELECT * FROM `q_action` WHERE 1  AND  date  > '2022-07-11' AND  date  < '2022-07-12' AND  action  NOT IN ('disconnectxuanxuan','loginxuanxuan') ORDER BY `date` desc 
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT id, account AS name FROM `q_user` WHERE id IN ('1')
  SELECT account,realname FROM `q_user` WHERE deleted  = '0' AND  account IN ('zhouyq')

20220711 11:17:49: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:17:54: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:17:59: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:18:04: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:18:09: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:18:14: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:18:19: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:18:24: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:18:29: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:18:34: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:18:39: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:18:44: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:18:49: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:18:54: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:18:59: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:19:04: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:19:09: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:19:14: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:19:19: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:19:24: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:19:29: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:19:34: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:19:39: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:19:44: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:19:49: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:19:54: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:19:59: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:20:04: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:20:09: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:20:14: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:20:19: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:20:24: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:20:29: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:20:34: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:20:39: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:20:42: 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT acl FROM `q_group` WHERE name  = 'guest'
  SELECT module, method FROM `q_group` AS t1  LEFT JOIN `q_grouppriv` AS t2  ON t1.id = t2.`group`  WHERE t1.name  = 'guest'
  SELECT * FROM `q_config` WHERE owner IN ('system','guest') ORDER BY `id` 

20220711 11:20:44: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:20:44: my
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT COUNT(*) AS recTotal FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' 
  SELECT instance.* FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' ORDER BY instance.`id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')
  SELECT * FROM `q_action` WHERE 1  AND  date  > '2022-07-11' AND  date  < '2022-07-12' AND  action  NOT IN ('disconnectxuanxuan','loginxuanxuan') ORDER BY `date` desc 
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT id, account AS name FROM `q_user` WHERE id IN ('1')
  SELECT account,realname FROM `q_user` WHERE deleted  = '0' AND  account IN ('zhouyq')

20220711 11:20:45: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:20:45: my
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT COUNT(*) AS recTotal FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' 
  SELECT instance.* FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' ORDER BY instance.`id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')
  SELECT * FROM `q_action` WHERE 1  AND  date  > '2022-07-11' AND  date  < '2022-07-12' AND  action  NOT IN ('disconnectxuanxuan','loginxuanxuan') ORDER BY `date` desc 
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT id, account AS name FROM `q_user` WHERE id IN ('1')
  SELECT account,realname FROM `q_user` WHERE deleted  = '0' AND  account IN ('zhouyq')

20220711 11:20:47: store
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:20:48: store-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:20:48: space-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 
  INSERT INTO `q_space` SET `name` = '默认空间',`k8space` = 'default',`owner` = 'zhouyq',`default` = '1',`createdAt` = '2022-07-11 11:20:48'
  SELECT * FROM `q_space` WHERE id  = '1'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id  = '1'
  SELECT COUNT(*) AS recTotal FROM `q_instance` WHERE deleted  = '0' AND  space  = '1' 
  SELECT * FROM `q_instance` WHERE deleted  = '0' AND  space  = '1' ORDER BY `id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq'

20220711 11:20:51: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 11:20:53: store
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:20:53: store-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:20:55: store-appview-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:20:56: instance-install-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'dgad.*' AND  deleted  = '0'

20220711 11:20:58: instance-install-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'dgad.*' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 

20220711 11:22:58: store-appview-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:22:58: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:22:59: store-appview-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:23:06: instance-install-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'clng.*' AND  deleted  = '0'

20220711 11:23:08: instance-install-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'clng.*' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 

20220711 11:23:23: 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT acl FROM `q_group` WHERE name  = 'guest'
  SELECT module, method FROM `q_group` AS t1  LEFT JOIN `q_grouppriv` AS t2  ON t1.id = t2.`group`  WHERE t1.name  = 'guest'
  SELECT * FROM `q_config` WHERE owner IN ('system','guest') ORDER BY `id` 

20220711 11:23:34: store-appview-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:23:34: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:23:34: store-appview-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:23:36: instance-install-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'kj7r.*' AND  deleted  = '0'

20220711 11:23:38: instance-install-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'kj7r.*' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 

20220711 11:23:43: instance-install-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'kj7r.*' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 

20220711 11:24:50: store-appview-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:24:50: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:24:51: store-appview-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:24:52: instance-install-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'dg8k.*' AND  deleted  = '0'

20220711 11:24:53: instance-install-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'dg8k.*' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 

20220711 11:25:16: store-appview-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:25:17: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:25:17: store-appview-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:25:19: instance-install-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'wzim.*' AND  deleted  = '0'

20220711 11:25:20: instance-install-31
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'wzim.*' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 

20220711 11:25:44: 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT acl FROM `q_group` WHERE name  = 'guest'
  SELECT module, method FROM `q_group` AS t1  LEFT JOIN `q_grouppriv` AS t2  ON t1.id = t2.`group`  WHERE t1.name  = 'guest'
  SELECT * FROM `q_config` WHERE owner IN ('system','guest') ORDER BY `id` 

20220711 11:25:48: my
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT COUNT(*) AS recTotal FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' 
  SELECT instance.* FROM `q_instance` AS instance  LEFT JOIN `q_space` AS space  ON space.id=instance.space  WHERE instance.deleted  = '0' AND  space.owner  = 'zhouyq' ORDER BY instance.`id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')
  SELECT * FROM `q_action` WHERE 1  AND  date  > '2022-07-11' AND  date  < '2022-07-12' AND  action  NOT IN ('disconnectxuanxuan','loginxuanxuan') ORDER BY `date` desc 
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT id, account AS name FROM `q_user` WHERE id IN ('1')
  SELECT account,realname FROM `q_user` WHERE deleted  = '0' AND  account IN ('zhouyq')

20220711 11:25:50: store
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:25:50: store-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:25:52: store-appview-32
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:25:53: instance-install-32
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'js7d.*' AND  deleted  = '0'

20220711 11:25:56: instance-install-32
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'js7d.*' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 

20220711 11:27:51: 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT acl FROM `q_group` WHERE name  = 'guest'
  SELECT module, method FROM `q_group` AS t1  LEFT JOIN `q_grouppriv` AS t2  ON t1.id = t2.`group`  WHERE t1.name  = 'guest'
  SELECT * FROM `q_config` WHERE owner IN ('system','guest') ORDER BY `id` 

20220711 11:27:56: store-appview-32
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:27:56: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:27:57: store-appview-32
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:27:58: instance-install-32
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'wmhi.\"qc.yunop.com\"' AND  deleted  = '0'

20220711 11:28:31: 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT acl FROM `q_group` WHERE name  = 'guest'
  SELECT module, method FROM `q_group` AS t1  LEFT JOIN `q_grouppriv` AS t2  ON t1.id = t2.`group`  WHERE t1.name  = 'guest'
  SELECT * FROM `q_config` WHERE owner IN ('system','guest') ORDER BY `id` 

20220711 11:28:33: store-appview-32
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:28:34: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:28:34: store-appview-32
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:28:36: instance-install-32
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'ymrx.qc.yunop.com' AND  deleted  = '0'

20220711 11:28:39: instance-install-32
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'ymrx.qc.yunop.com' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 

20220711 11:29:12: instance-install-32
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'nkmh.qc.yunop.com' AND  deleted  = '0'

20220711 11:29:15: instance-install-32
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'nkmh.qc.yunop.com' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 

20220711 11:29:33: space-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id  = '1'
  SELECT COUNT(*) AS recTotal FROM `q_instance` WHERE deleted  = '0' AND  space  = '1' 
  SELECT * FROM `q_instance` WHERE deleted  = '0' AND  space  = '1' ORDER BY `id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq'

20220711 11:29:34: store
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:29:34: store-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:30:04: store-appview-34
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:30:05: instance-install-34
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'j1iv.qc.yunop.com' AND  deleted  = '0'

20220711 11:30:13: instance-install-34
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'j1iv.qc.yunop.com' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 

20220711 11:32:22: store-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:32:25: store-appview-46
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:32:27: instance-install-46
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'nvif.qc.yunop.com' AND  deleted  = '0'

20220711 11:32:30: instance-install-46
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'nvif.qc.yunop.com' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 

20220711 11:39:33: misc-ping
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('common') AND  section IN ('global') AND  `key` IN ('sn')
  REPLACE `q_config` SET `owner` = 'system',`module` = 'common',`section` = 'global',`key` = 'sn',`value` = '096af63b1f703e248f65e3b651af29c9'

20220711 11:42:26: misc-ping
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 11:42:28: misc-ping
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:00:14: store-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:00:18: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('')

20220711 12:10:15: misc-ping
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:10:35: store-appview-30
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:10:42: instance-install-30
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'b7xc.qc.yunop.com' AND  deleted  = '0'

20220711 12:10:50: instance-install-30
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'b7xc.qc.yunop.com' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 

20220711 12:20:36: misc-ping
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('common') AND  section IN ('global') AND  `key` IN ('sn')

20220711 12:20:43: misc-ping
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:25:13: 
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_company` ORDER BY `id`  LIMIT 1 
  SELECT acl FROM `q_group` WHERE name  = 'guest'
  SELECT module, method FROM `q_group` AS t1  LEFT JOIN `q_grouppriv` AS t2  ON t1.id = t2.`group`  WHERE t1.name  = 'guest'
  SELECT * FROM `q_config` WHERE owner IN ('system','guest') ORDER BY `id` 

20220711 12:25:27: store-appview-30
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:25:27: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:25:28: store-appview-30
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:25:29: store-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:25:31: store-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:25:31: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:25:32: store-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:25:33: store-appview-30
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:25:35: instance-install-30
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'zkgc.qc.yunop.com' AND  deleted  = '0'

20220711 12:25:38: instance-install-30
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT id FROM `q_instance` WHERE domain  = 'zkgc.qc.yunop.com' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 
  INSERT INTO `q_instance` SET `appId` = '30',`appName` = '禅道旗舰版',`name` = '禅道旗舰版',`domain` = 'zkgc.qc.yunop.com',`logo` = '//img.qucheng.com/app/z/zentao-icon.svg',`desc` = '禅道旗舰版是最高级版本，在开源版与企业版的基础上，增加了流程控制、项目度量、问题管理、风险管控、项目报告等功能，并支持CMMI标准的落地。',`source` = 'cloud',`chart` = 'zentao-max',`appVersion` = '3.3',`version` = '0.1.7',`space` = '1',`k8name` = 'zentao-max-zhouyq-20220711122536',`status` = 'creating',`createdBy` = 'zhouyq',`createdAt` = '2022-07-11 12:25:38'
  SELECT * FROM `q_instance` WHERE id  = '1' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE id  = '1'
  INSERT INTO `q_action` SET `objectType` = 'instance',`objectID` = '1',`actor` = 'zhouyq',`action` = 'install',`date` = '2022-07-11 12:25:38',`extra` = '{\"result\":{\"code\":200,\"message\":\"\\u8bf7\\u6c42\\u6210\\u529f\",\"timestamp\":1657513538,\"traceId\":\"6be529df-e487-4232-b8ec-e00ec0f5d23c\"},\"app\":{\"id\":30,\"name\":\"zentao-max\",\"alias\":\"\\u7985\\u9053\\u65d7\\u8230\\u7248\",\"chart\":\"zentao-max\",\"desc\":\"\\u7985\\u9053\\u65d7\\u8230\\u7248\\u662f\\u6700\\u9ad8\\u7ea7\\u7248\\u672c\\uff0c\\u5728\\u5f00\\u6e90\\u7248\\u4e0e\\u4f01\\u4e1a\\u7248\\u7684\\u57fa\\u7840\\u4e0a\\uff0c\\u589e\\u52a0\\u4e86\\u6d41\\u7a0b\\u63a7\\u5236\\u3001\\u9879\\u76ee\\u5ea6\\u91cf\\u3001\\u95ee\\u9898\\u7ba1\\u7406\\u3001\\u98ce\\u9669\\u7ba1\\u63a7\\u3001\\u9879\\u76ee\\u62a5\\u544a\\u7b49\\u529f\\u80fd\\uff0c\\u5e76\\u652f\\u6301CMMI\\u6807\\u51c6\\u7684\\u843d\\u5730\\u3002\",\"logo\":\"\\/\\/img.qucheng.com\\/app\\/z\\/zentao-icon.svg\",\"app_version\":\"3.3\",\"version\":\"0.1.7\",\"change_log_url\":\"https:\\/\\/www.zentao.net\\/dynamic\\/max3.3-81023.html\",\"publish_time\":\"2022-07-06T15:23:42.406Z\",\"author\":\"\\u6613\\u8f6f\\u5929\\u521b\",\"categories\":[{\"id\":2,\"name\":\"pms\",\"alias\":\"\\u9879\\u76ee\\u7ba1\\u7406\"}],\"cpu\":0.200000000000000011102230246251565404236316680908203125,\"memory\":268435456}}',`vision` = 'rnd',`comment` = ''
  UPDATE `q_instance` SET `status` = 'initializing' WHERE id  = '1'

20220711 12:25:38: space-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id  = '1'
  SELECT COUNT(*) AS recTotal FROM `q_instance` WHERE deleted  = '0' AND  space  = '1' 
  SELECT * FROM `q_instance` WHERE deleted  = '0' AND  space  = '1' ORDER BY `id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq'

20220711 12:25:43: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')
  UPDATE `q_instance` SET  `status` = 'starting', `version` = '0.1.7', `domain` = 'zkgc.qc.yunop.com' WHERE id  = '1'

20220711 12:25:48: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:25:48: space-browse
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq' ORDER BY `default` desc  LIMIT 1 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id  = '1'
  SELECT COUNT(*) AS recTotal FROM `q_instance` WHERE deleted  = '0' AND  space  = '1' 
  SELECT * FROM `q_instance` WHERE deleted  = '0' AND  space  = '1' ORDER BY `id` desc 
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  owner  = 'zhouyq'

20220711 12:25:49: instance-view-1
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id  = '1' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE id  = '1'
  SELECT id,name FROM `q_space` WHERE id  = '1'
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT COUNT(*) AS recTotal FROM `q_action` WHERE objectType  = 'instance' AND  objectID  = '1' 
  SELECT * FROM `q_action` WHERE objectType  = 'instance' AND  objectID  = '1' ORDER BY `date` desc 
  SELECT account,realname FROM `q_user` WHERE account IN ('zhouyq')

20220711 12:25:55: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:26:00: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')
  UPDATE `q_instance` SET  `status` = 'running', `version` = '0.1.7', `domain` = 'zkgc.qc.yunop.com' WHERE id  = '1'

20220711 12:26:05: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:26:05: instance-view-1
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id  = '1' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE id  = '1'
  SELECT id,name FROM `q_space` WHERE id  = '1'
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT COUNT(*) AS recTotal FROM `q_action` WHERE objectType  = 'instance' AND  objectID  = '1' 
  SELECT * FROM `q_action` WHERE objectType  = 'instance' AND  objectID  = '1' ORDER BY `date` desc 
  SELECT account,realname FROM `q_user` WHERE account IN ('zhouyq')

20220711 12:26:05: instance-view-1
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id  = '1' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE id  = '1'
  SELECT id,name FROM `q_space` WHERE id  = '1'
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT COUNT(*) AS recTotal FROM `q_action` WHERE objectType  = 'instance' AND  objectID  = '1' 
  SELECT * FROM `q_action` WHERE objectType  = 'instance' AND  objectID  = '1' ORDER BY `date` desc 
  SELECT account,realname FROM `q_user` WHERE account IN ('zhouyq')

20220711 12:26:05: index-index
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:26:05: instance-view-1
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id  = '1' AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE id  = '1'
  SELECT id,name FROM `q_space` WHERE id  = '1'
  SELECT commiter, account, realname FROM `q_user` WHERE commiter  != ''
  SELECT COUNT(*) AS recTotal FROM `q_action` WHERE objectType  = 'instance' AND  objectID  = '1' 
  SELECT * FROM `q_action` WHERE objectType  = 'instance' AND  objectID  = '1' ORDER BY `date` desc 
  SELECT account,realname FROM `q_user` WHERE account IN ('zhouyq')

20220711 12:26:11: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:26:16: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:26:21: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:26:27: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:26:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:26:36: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:26:41: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:26:47: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:26:51: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:26:57: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:27:01: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:27:06: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:27:11: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:27:16: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:27:21: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:27:26: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:27:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:27:36: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:27:41: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:27:46: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:27:51: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:27:56: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:28:01: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:28:06: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:28:11: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:28:17: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:28:21: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:28:26: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:28:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:28:37: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:28:41: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:28:47: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:28:51: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:28:56: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:29:01: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:29:06: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:29:11: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:29:16: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:29:21: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:29:26: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:29:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:29:37: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:29:41: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:29:46: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:29:51: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:29:56: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:30:01: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:30:06: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:30:11: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:30:16: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:30:21: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:30:27: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:30:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:30:36: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:30:41: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:30:47: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:30:51: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:30:56: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:31:01: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:31:06: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:31:11: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:31:17: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:31:21: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:31:27: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:31:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:31:37: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:31:41: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:31:46: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:31:51: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:31:57: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:32:01: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:32:07: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:32:11: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:32:16: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:32:21: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:32:26: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:32:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:32:37: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:32:41: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:32:46: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:32:51: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:33:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:34:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:35:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:36:06: misc-ping
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 

20220711 12:36:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:37:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:38:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:39:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:40:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:41:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:42:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:43:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:44:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:45:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:46:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:47:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:48:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:49:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:50:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:51:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:52:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:53:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:54:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:55:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:56:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:57:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:58:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 12:59:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:00:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:01:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:02:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:03:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:04:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:05:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:06:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:07:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:08:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:09:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:10:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:11:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:12:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:13:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:14:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:15:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:16:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:17:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:18:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:19:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:20:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:21:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:22:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:23:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:24:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:25:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:26:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:27:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:28:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:29:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:30:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:31:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:32:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:33:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:34:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:35:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:36:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:37:31: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:38:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:39:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

20220711 13:40:30: instance-ajaxStatus
  SELECT * FROM `q_config` WHERE 1 = 1  AND  owner IN ('system') AND  module IN ('sso') AND  `key` IN ('turnon')
  SELECT * FROM `q_config` WHERE owner IN ('system','zhouyq') ORDER BY `id` 
  SELECT * FROM `q_instance` WHERE id IN ('1') AND  deleted  = '0'
  SELECT * FROM `q_space` WHERE deleted  = '0' AND  id IN ('1')

