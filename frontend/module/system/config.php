<?php
$config->system->installldap = new stdclass;
$config->system->installldap->requiredFields = "source,extra[host],extra[port],extra[bindDN],extra[bindPass],extra[baseDN]";

$config->system->editldap = new stdclass;
$config->system->editldap->requiredFields = "source,extra[host],extra[port],extra[bindDN],extra[bindPass],extra[baseDN]";

