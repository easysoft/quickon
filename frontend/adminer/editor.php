<?php
/** Adminer Editor - Compact database editor
* @link https://www.adminer.org/
* @author Jakub Vrana, https://www.vrana.cz/
* @copyright 2009 Jakub Vrana
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 4.8.1
*/function
adminer_errors($bc,$cc){return!!preg_match('~^(Trying to access array offset on value of type null|Undefined array key)~',$cc);}error_reporting(6135);set_error_handler('adminer_errors',E_WARNING);$tc=!preg_match('~^(unsafe_raw)?$~',ini_get("filter.default"));if($tc||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$X){$Hg=filter_input_array(constant("INPUT$X"),FILTER_UNSAFE_RAW);if($Hg)$$X=$Hg;}}if(function_exists("mb_internal_encoding"))mb_internal_encoding("8bit");function
connection(){global$h;return$h;}function
adminer(){global$b;return$b;}function
version(){global$ca;return$ca;}function
idf_unescape($u){if(!preg_match('~^[`\'"]~',$u))return$u;$_d=substr($u,-1);return
str_replace($_d.$_d,$_d,substr($u,1,-1));}function
escape_string($X){return
substr(q($X),1,-1);}function
number($X){return
preg_replace('~[^0-9]+~','',$X);}function
number_type(){return'((?<!o)int(?!er)|numeric|real|float|double|decimal|money)';}function
remove_slashes($Te,$tc=false){if(function_exists("get_magic_quotes_gpc")&&get_magic_quotes_gpc()){while(list($y,$X)=each($Te)){foreach($X
as$sd=>$W){unset($Te[$y][$sd]);if(is_array($W)){$Te[$y][stripslashes($sd)]=$W;$Te[]=&$Te[$y][stripslashes($sd)];}else$Te[$y][stripslashes($sd)]=($tc?$W:stripslashes($W));}}}}function
bracket_escape($u,$Ga=false){static$ug=array(':'=>':1',']'=>':2','['=>':3','"'=>':4');return
strtr($u,($Ga?array_flip($ug):$ug));}function
min_version($Tg,$Ld="",$i=null){global$h;if(!$i)$i=$h;$Bf=$i->server_info;if($Ld&&preg_match('~([\d.]+)-MariaDB~',$Bf,$A)){$Bf=$A[1];$Tg=$Ld;}return(version_compare($Bf,$Tg)>=0);}function
charset($h){return(min_version("5.5.3",0,$h)?"utf8mb4":"utf8");}function
script($Kf,$tg="\n"){return"<script".nonce().">$Kf</script>$tg";}function
script_src($Mg){return"<script src='".h($Mg)."'".nonce()."></script>\n";}function
nonce(){return' nonce="'.get_nonce().'"';}function
target_blank(){return' target="_blank" rel="noreferrer noopener"';}function
h($P){return
str_replace("\0","&#0;",htmlspecialchars($P,ENT_QUOTES,'utf-8'));}function
nl_br($P){return
str_replace("\n","<br>",$P);}function
checkbox($B,$Y,$Ua,$wd="",$me="",$Xa="",$xd=""){$H="<input type='checkbox' name='$B' value='".h($Y)."'".($Ua?" checked":"").($xd?" aria-labelledby='$xd'":"").">".($me?script("qsl('input').onclick = function () { $me };",""):"");return($wd!=""||$Xa?"<label".($Xa?" class='$Xa'":"").">$H".h($wd)."</label>":$H);}function
optionlist($C,$wf=null,$Pg=false){$H="";foreach($C
as$sd=>$W){$re=array($sd=>$W);if(is_array($W)){$H.='<optgroup label="'.h($sd).'">';$re=$W;}foreach($re
as$y=>$X)$H.='<option'.($Pg||is_string($y)?' value="'.h($y).'"':'').(($Pg||is_string($y)?(string)$y:$X)===$wf?' selected':'').'>'.h($X);if(is_array($W))$H.='</optgroup>';}return$H;}function
html_select($B,$C,$Y="",$le=true,$xd=""){if($le)return"<select name='".h($B)."'".($xd?" aria-labelledby='$xd'":"").">".optionlist($C,$Y)."</select>".(is_string($le)?script("qsl('select').onchange = function () { $le };",""):"");$H="";foreach($C
as$y=>$X)$H.="<label><input type='radio' name='".h($B)."' value='".h($y)."'".($y==$Y?" checked":"").">".h($X)."</label>";return$H;}function
select_input($Ba,$C,$Y="",$le="",$Ke=""){$cg=($C?"select":"input");return"<$cg$Ba".($C?"><option value=''>$Ke".optionlist($C,$Y,true)."</select>":" size='10' value='".h($Y)."' placeholder='$Ke'>").($le?script("qsl('$cg').onchange = $le;",""):"");}function
confirm($Td="",$xf="qsl('input')"){return
script("$xf.onclick = function () { return confirm('".($Td?js_escape($Td):lang(0))."'); };","");}function
print_fieldset($t,$Bd,$Wg=false){echo"<fieldset><legend>","<a href='#fieldset-$t'>$Bd</a>",script("qsl('a').onclick = partial(toggle, 'fieldset-$t');",""),"</legend>","<div id='fieldset-$t'".($Wg?"":" class='hidden'").">\n";}function
bold($Na,$Xa=""){return($Na?" class='active $Xa'":($Xa?" class='$Xa'":""));}function
odd($H=' class="odd"'){static$s=0;if(!$H)$s=-1;return($s++%2?$H:'');}function
js_escape($P){return
addcslashes($P,"\r\n'\\/");}function
json_row($y,$X=null){static$uc=true;if($uc)echo"{";if($y!=""){echo($uc?"":",")."\n\t\"".addcslashes($y,"\r\n\t\"\\/").'": '.($X!==null?'"'.addcslashes($X,"\r\n\"\\/").'"':'null');$uc=false;}else{echo"\n}\n";$uc=true;}}function
ini_bool($jd){$X=ini_get($jd);return(preg_match('~^(on|true|yes)$~i',$X)||(int)$X);}function
sid(){static$H;if($H===null)$H=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$H;}function
set_password($Sg,$M,$V,$E){$_SESSION["pwds"][$Sg][$M][$V]=($_COOKIE["adminer_key"]&&is_string($E)?array(encrypt_string($E,$_COOKIE["adminer_key"])):$E);}function
get_password(){$H=get_session("pwds");if(is_array($H))$H=($_COOKIE["adminer_key"]?decrypt_string($H[0],$_COOKIE["adminer_key"]):false);return$H;}function
q($P){global$h;return$h->quote($P);}function
get_vals($F,$e=0){global$h;$H=array();$G=$h->query($F);if(is_object($G)){while($I=$G->fetch_row())$H[]=$I[$e];}return$H;}function
get_key_vals($F,$i=null,$Ef=true){global$h;if(!is_object($i))$i=$h;$H=array();$G=$i->query($F);if(is_object($G)){while($I=$G->fetch_row()){if($Ef)$H[$I[0]]=$I[1];else$H[]=$I[0];}}return$H;}function
get_rows($F,$i=null,$n="<p class='error'>"){global$h;$kb=(is_object($i)?$i:$h);$H=array();$G=$kb->query($F);if(is_object($G)){while($I=$G->fetch_assoc())$H[]=$I;}elseif(!$G&&!is_object($i)&&$n&&defined("PAGE_HEADER"))echo$n.error()."\n";return$H;}function
unique_array($I,$w){foreach($w
as$v){if(preg_match("~PRIMARY|UNIQUE~",$v["type"])){$H=array();foreach($v["columns"]as$y){if(!isset($I[$y]))continue
2;$H[$y]=$I[$y];}return$H;}}}function
escape_key($y){if(preg_match('(^([\w(]+)('.str_replace("_",".*",preg_quote(idf_escape("_"))).')([ \w)]+)$)',$y,$A))return$A[1].idf_escape(idf_unescape($A[2])).$A[3];return
idf_escape($y);}function
where($Z,$p=array()){global$h,$x;$H=array();foreach((array)$Z["where"]as$y=>$X){$y=bracket_escape($y,1);$e=escape_key($y);$H[]=$e.($x=="sql"&&is_numeric($X)&&preg_match('~\.~',$X)?" LIKE ".q($X):($x=="mssql"?" LIKE ".q(preg_replace('~[_%[]~','[\0]',$X)):" = ".unconvert_field($p[$y],q($X))));if($x=="sql"&&preg_match('~char|text~',$p[$y]["type"])&&preg_match("~[^ -@]~",$X))$H[]="$e = ".q($X)." COLLATE ".charset($h)."_bin";}foreach((array)$Z["null"]as$y)$H[]=escape_key($y)." IS NULL";return
implode(" AND ",$H);}function
where_check($X,$p=array()){parse_str($X,$Sa);remove_slashes(array(&$Sa));return
where($Sa,$p);}function
where_link($s,$e,$Y,$oe="="){return"&where%5B$s%5D%5Bcol%5D=".urlencode($e)."&where%5B$s%5D%5Bop%5D=".urlencode(($Y!==null?$oe:"IS NULL"))."&where%5B$s%5D%5Bval%5D=".urlencode($Y);}function
convert_fields($f,$p,$K=array()){$H="";foreach($f
as$y=>$X){if($K&&!in_array(idf_escape($y),$K))continue;$za=convert_field($p[$y]);if($za)$H.=", $za AS ".idf_escape($y);}return$H;}function
cookie($B,$Y,$Ed=2592000){global$aa;return
header("Set-Cookie: $B=".urlencode($Y).($Ed?"; expires=".gmdate("D, d M Y H:i:s",time()+$Ed)." GMT":"")."; path=".preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]).($aa?"; secure":"")."; HttpOnly; SameSite=lax",false);}function
restart_session(){if(!ini_bool("session.use_cookies"))session_start();}function
stop_session($zc=false){$Og=ini_bool("session.use_cookies");if(!$Og||$zc){session_write_close();if($Og&&@ini_set("session.use_cookies",false)===false)session_start();}}function&get_session($y){return$_SESSION[$y][DRIVER][SERVER][$_GET["username"]];}function
set_session($y,$X){$_SESSION[$y][DRIVER][SERVER][$_GET["username"]]=$X;}function
auth_url($Sg,$M,$V,$l=null){global$Mb;preg_match('~([^?]*)\??(.*)~',remove_from_uri(implode("|",array_keys($Mb))."|username|".($l!==null?"db|":"").session_name()),$A);return"$A[1]?".(sid()?SID."&":"").($Sg!="server"||$M!=""?urlencode($Sg)."=".urlencode($M)."&":"")."username=".urlencode($V).($l!=""?"&db=".urlencode($l):"").($A[2]?"&$A[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($Gd,$Td=null){if($Td!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($Gd!==null?$Gd:$_SERVER["REQUEST_URI"]))][]=$Td;}if($Gd!==null){if($Gd=="")$Gd=".";header("Location: $Gd");exit;}}function
query_redirect($F,$Gd,$Td,$df=true,$gc=true,$mc=false,$jg=""){global$h,$n,$b;if($gc){$Qf=microtime(true);$mc=!$h->query($F);$jg=format_time($Qf);}$Nf="";if($F)$Nf=$b->messageQuery($F,$jg,$mc);if($mc){$n=error().$Nf.script("messagesPrint();");return
false;}if($df)redirect($Gd,$Td.$Nf);return
true;}function
queries($F){global$h;static$Xe=array();static$Qf;if(!$Qf)$Qf=microtime(true);if($F===null)return
array(implode("\n",$Xe),format_time($Qf));$Xe[]=(preg_match('~;$~',$F)?"DELIMITER ;;\n$F;\nDELIMITER ":$F).";";return$h->query($F);}function
apply_queries($F,$S,$dc='table'){foreach($S
as$Q){if(!queries("$F ".$dc($Q)))return
false;}return
true;}function
queries_redirect($Gd,$Td,$df){list($Xe,$jg)=queries(null);return
query_redirect($Xe,$Gd,$Td,$df,false,!$df,$jg);}function
format_time($Qf){return
lang(1,max(0,microtime(true)-$Qf));}function
relative_uri(){return
str_replace(":","%3a",preg_replace('~^[^?]*/([^?]*)~','\1',$_SERVER["REQUEST_URI"]));}function
remove_from_uri($Be=""){return
substr(preg_replace("~(?<=[?&])($Be".(SID?"":"|".session_name()).")=[^&]*&~",'',relative_uri()."&"),0,-1);}function
pagination($D,$zb){return" ".($D==$zb?$D+1:'<a href="'.h(remove_from_uri("page").($D?"&page=$D".($_GET["next"]?"&next=".urlencode($_GET["next"]):""):"")).'">'.($D+1)."</a>");}function
get_file($y,$Cb=false){$rc=$_FILES[$y];if(!$rc)return
null;foreach($rc
as$y=>$X)$rc[$y]=(array)$X;$H='';foreach($rc["error"]as$y=>$n){if($n)return$n;$B=$rc["name"][$y];$qg=$rc["tmp_name"][$y];$qb=file_get_contents($Cb&&preg_match('~\.gz$~',$B)?"compress.zlib://$qg":$qg);if($Cb){$Qf=substr($qb,0,3);if(function_exists("iconv")&&preg_match("~^\xFE\xFF|^\xFF\xFE~",$Qf,$ef))$qb=iconv("utf-16","utf-8",$qb);elseif($Qf=="\xEF\xBB\xBF")$qb=substr($qb,3);$H.=$qb."\n\n";}else$H.=$qb;}return$H;}function
upload_error($n){$Qd=($n==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($n?lang(2).($Qd?" ".lang(3,$Qd):""):lang(4));}function
repeat_pattern($He,$Cd){return
str_repeat("$He{0,65535}",$Cd/65535)."$He{0,".($Cd%65535)."}";}function
is_utf8($X){return(preg_match('~~u',$X)&&!preg_match('~[\0-\x8\xB\xC\xE-\x1F]~',$X));}function
shorten_utf8($P,$Cd=80,$Wf=""){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{10FFFF}]",$Cd).")($)?)u",$P,$A))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$Cd).")($)?)",$P,$A);return
h($A[1]).$Wf.(isset($A[2])?"":"<i>â€¦</i>");}function
format_number($X){return
strtr(number_format($X,0,".",lang(5)),preg_split('~~u',lang(6),-1,PREG_SPLIT_NO_EMPTY));}function
friendly_url($X){return
preg_replace('~[^a-z0-9_]~i','-',$X);}function
hidden_fields($Te,$ad=array(),$Oe=''){$H=false;foreach($Te
as$y=>$X){if(!in_array($y,$ad)){if(is_array($X))hidden_fields($X,array(),$y);else{$H=true;echo'<input type="hidden" name="'.h($Oe?$Oe."[$y]":$y).'" value="'.h($X).'">';}}}return$H;}function
hidden_fields_get(){echo(sid()?'<input type="hidden" name="'.session_name().'" value="'.h(session_id()).'">':''),(SERVER!==null?'<input type="hidden" name="'.DRIVER.'" value="'.h(SERVER).'">':""),'<input type="hidden" name="username" value="'.h($_GET["username"]).'">';}function
table_status1($Q,$nc=false){$H=table_status($Q,$nc);return($H?$H:array("Name"=>$Q));}function
column_foreign_keys($Q){global$b;$H=array();foreach($b->foreignKeys($Q)as$Cc){foreach($Cc["source"]as$X)$H[$X][]=$Cc;}return$H;}function
enum_input($T,$Ba,$o,$Y,$Xb=null){global$b;preg_match_all("~'((?:[^']|'')*)'~",$o["length"],$Nd);$H=($Xb!==null?"<label><input type='$T'$Ba value='$Xb'".((is_array($Y)?in_array($Xb,$Y):$Y===0)?" checked":"")."><i>".lang(7)."</i></label>":"");foreach($Nd[1]as$s=>$X){$X=stripcslashes(str_replace("''","'",$X));$Ua=(is_int($Y)?$Y==$s+1:(is_array($Y)?in_array($s+1,$Y):$Y===$X));$H.=" <label><input type='$T'$Ba value='".($s+1)."'".($Ua?' checked':'').'>'.h($b->editVal($X,$o)).'</label>';}return$H;}function
input($o,$Y,$r){global$U,$b,$x;$B=h(bracket_escape($o["field"]));echo"<td class='function'>";if(is_array($Y)&&!$r){$xa=array($Y);if(version_compare(PHP_VERSION,5.4)>=0)$xa[]=JSON_PRETTY_PRINT;$Y=call_user_func_array('json_encode',$xa);$r="json";}$jf=($x=="mssql"&&$o["auto_increment"]);if($jf&&!$_POST["save"])$r=null;$Ic=(isset($_GET["select"])||$jf?array("orig"=>lang(8)):array())+$b->editFunctions($o);$Ba=" name='fields[$B]'";if($o["type"]=="enum")echo
h($Ic[""])."<td>".$b->editInput($_GET["edit"],$o,$Ba,$Y);else{$Pc=(in_array($r,$Ic)||isset($Ic[$r]));echo(count($Ic)>1?"<select name='function[$B]'>".optionlist($Ic,$r===null||$Pc?$r:"")."</select>".on_help("getTarget(event).value.replace(/^SQL\$/, '')",1).script("qsl('select').onchange = functionChange;",""):h(reset($Ic))).'<td>';$ld=$b->editInput($_GET["edit"],$o,$Ba,$Y);if($ld!="")echo$ld;elseif(preg_match('~bool~',$o["type"]))echo"<input type='hidden'$Ba value='0'>"."<input type='checkbox'".(preg_match('~^(1|t|true|y|yes|on)$~i',$Y)?" checked='checked'":"")."$Ba value='1'>";elseif($o["type"]=="set"){preg_match_all("~'((?:[^']|'')*)'~",$o["length"],$Nd);foreach($Nd[1]as$s=>$X){$X=stripcslashes(str_replace("''","'",$X));$Ua=(is_int($Y)?($Y>>$s)&1:in_array($X,explode(",",$Y),true));echo" <label><input type='checkbox' name='fields[$B][$s]' value='".(1<<$s)."'".($Ua?' checked':'').">".h($b->editVal($X,$o)).'</label>';}}elseif(preg_match('~blob|bytea|raw|file~',$o["type"])&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$B'>";elseif(($gg=preg_match('~text|lob|memo~i',$o["type"]))||preg_match("~\n~",$Y)){if($gg&&$x!="sqlite")$Ba.=" cols='50' rows='12'";else{$J=min(12,substr_count($Y,"\n")+1);$Ba.=" cols='30' rows='$J'".($J==1?" style='height: 1.2em;'":"");}echo"<textarea$Ba>".h($Y).'</textarea>';}elseif($r=="json"||preg_match('~^jsonb?$~',$o["type"]))echo"<textarea$Ba cols='50' rows='12' class='jush-js'>".h($Y).'</textarea>';else{$Sd=(!preg_match('~int~',$o["type"])&&preg_match('~^(\d+)(,(\d+))?$~',$o["length"],$A)?((preg_match("~binary~",$o["type"])?2:1)*$A[1]+($A[3]?1:0)+($A[2]&&!$o["unsigned"]?1:0)):($U[$o["type"]]?$U[$o["type"]]+($o["unsigned"]?0:1):0));if($x=='sql'&&min_version(5.6)&&preg_match('~time~',$o["type"]))$Sd+=7;echo"<input".((!$Pc||$r==="")&&preg_match('~(?<!o)int(?!er)~',$o["type"])&&!preg_match('~\[\]~',$o["full_type"])?" type='number'":"")." value='".h($Y)."'".($Sd?" data-maxlength='$Sd'":"").(preg_match('~char|binary~',$o["type"])&&$Sd>20?" size='40'":"")."$Ba>";}echo$b->editHint($_GET["edit"],$o,$Y);$uc=0;foreach($Ic
as$y=>$X){if($y===""||!$X)break;$uc++;}if($uc)echo
script("mixin(qsl('td'), {onchange: partial(skipOriginal, $uc), oninput: function () { this.onchange(); }});");}}function
process_input($o){global$b,$m;$u=bracket_escape($o["field"]);$r=$_POST["function"][$u];$Y=$_POST["fields"][$u];if($o["type"]=="enum"){if($Y==-1)return
false;if($Y=="")return"NULL";return+$Y;}if($o["auto_increment"]&&$Y=="")return
null;if($r=="orig")return(preg_match('~^CURRENT_TIMESTAMP~i',$o["on_update"])?idf_escape($o["field"]):false);if($r=="NULL")return"NULL";if($o["type"]=="set")return
array_sum((array)$Y);if($r=="json"){$r="";$Y=json_decode($Y,true);if(!is_array($Y))return
false;return$Y;}if(preg_match('~blob|bytea|raw|file~',$o["type"])&&ini_bool("file_uploads")){$rc=get_file("fields-$u");if(!is_string($rc))return
false;return$m->quoteBinary($rc);}return$b->processInput($o,$Y,$r);}function
fields_from_edit(){global$m;$H=array();foreach((array)$_POST["field_keys"]as$y=>$X){if($X!=""){$X=bracket_escape($X);$_POST["function"][$X]=$_POST["field_funs"][$y];$_POST["fields"][$X]=$_POST["field_vals"][$y];}}foreach((array)$_POST["fields"]as$y=>$X){$B=bracket_escape($y,1);$H[$B]=array("field"=>$B,"privileges"=>array("insert"=>1,"update"=>1),"null"=>1,"auto_increment"=>($y==$m->primary),);}return$H;}function
search_tables(){global$b,$h;$_GET["where"][0]["val"]=$_POST["query"];$zf="<ul>\n";foreach(table_status('',true)as$Q=>$R){$B=$b->tableName($R);if(isset($R["Engine"])&&$B!=""&&(!$_POST["tables"]||in_array($Q,$_POST["tables"]))){$G=$h->query("SELECT".limit("1 FROM ".table($Q)," WHERE ".implode(" AND ",$b->selectSearchProcess(fields($Q),array())),1));if(!$G||$G->fetch_row()){$Re="<a href='".h(ME."select=".urlencode($Q)."&where[0][op]=".urlencode($_GET["where"][0]["op"])."&where[0][val]=".urlencode($_GET["where"][0]["val"]))."'>$B</a>";echo"$zf<li>".($G?$Re:"<p class='error'>$Re: ".error())."\n";$zf="";}}}echo($zf?"<p class='message'>".lang(9):"</ul>")."\n";}function
dump_headers($Yc,$Xd=false){global$b;$H=$b->dumpHeaders($Yc,$Xd);$ye=$_POST["output"];if($ye!="text")header("Content-Disposition: attachment; filename=".$b->dumpFilename($Yc).".$H".($ye!="file"&&preg_match('~^[0-9a-z]+$~',$ye)?".$ye":""));session_write_close();ob_flush();flush();return$H;}function
dump_csv($I){foreach($I
as$y=>$X){if(preg_match('~["\n,;\t]|^0|\.\d*0$~',$X)||$X==="")$I[$y]='"'.str_replace('"','""',$X).'"';}echo
implode(($_POST["format"]=="csv"?",":($_POST["format"]=="tsv"?"\t":";")),$I)."\r\n";}function
apply_sql_function($r,$e){return($r?($r=="unixepoch"?"DATETIME($e, '$r')":($r=="count distinct"?"COUNT(DISTINCT ":strtoupper("$r("))."$e)"):$e);}function
get_temp_dir(){$H=ini_get("upload_tmp_dir");if(!$H){if(function_exists('sys_get_temp_dir'))$H=sys_get_temp_dir();else{$q=@tempnam("","");if(!$q)return
false;$H=dirname($q);unlink($q);}}return$H;}function
file_open_lock($q){$Gc=@fopen($q,"r+");if(!$Gc){$Gc=@fopen($q,"w");if(!$Gc)return;chmod($q,0660);}flock($Gc,LOCK_EX);return$Gc;}function
file_write_unlock($Gc,$_b){rewind($Gc);fwrite($Gc,$_b);ftruncate($Gc,strlen($_b));flock($Gc,LOCK_UN);fclose($Gc);}function
password_file($ub){$q=get_temp_dir()."/adminer.key";$H=@file_get_contents($q);if($H||!$ub)return$H;$Gc=@fopen($q,"w");if($Gc){chmod($q,0660);$H=rand_string();fwrite($Gc,$H);fclose($Gc);}return$H;}function
rand_string(){return
md5(uniqid(mt_rand(),true));}function
select_value($X,$_,$o,$hg){global$b;if(is_array($X)){$H="";foreach($X
as$sd=>$W)$H.="<tr>".($X!=array_values($X)?"<th>".h($sd):"")."<td>".select_value($W,$_,$o,$hg);return"<table cellspacing='0'>$H</table>";}if(!$_)$_=$b->selectLink($X,$o);if($_===null){if(is_mail($X))$_="mailto:$X";if(is_url($X))$_=$X;}$H=$b->editVal($X,$o);if($H!==null){if(!is_utf8($H))$H="\0";elseif($hg!=""&&is_shortable($o))$H=shorten_utf8($H,max(0,+$hg));else$H=h($H);}return$b->selectVal($H,$_,$o,$X);}function
is_mail($Ub){$_a='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$Lb='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$He="$_a+(\\.$_a+)*@($Lb?\\.)+$Lb";return
is_string($Ub)&&preg_match("(^$He(,\\s*$He)*\$)i",$Ub);}function
is_url($P){$Lb='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return
preg_match("~^(https?)://($Lb?\\.)+$Lb(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$P);}function
is_shortable($o){return
preg_match('~char|text|json|lob|geometry|point|linestring|polygon|string|bytea~',$o["type"]);}function
count_rows($Q,$Z,$pd,$Jc){global$x;$F=" FROM ".table($Q).($Z?" WHERE ".implode(" AND ",$Z):"");return($pd&&($x=="sql"||count($Jc)==1)?"SELECT COUNT(DISTINCT ".implode(", ",$Jc).")$F":"SELECT COUNT(*)".($pd?" FROM (SELECT 1$F GROUP BY ".implode(", ",$Jc).") x":$F));}function
slow_query($F){global$b,$sg,$m;$l=$b->database();$kg=$b->queryTimeout();$Hf=$m->slowQuery($F,$kg);if(!$Hf&&support("kill")&&is_object($i=connect())&&($l==""||$i->select_db($l))){$vd=$i->result(connection_id());echo'<script',nonce(),'>
var timeout = setTimeout(function () {
	ajax(\'',js_escape(ME),'script=kill\', function () {
	}, \'kill=',$vd,'&token=',$sg,'\');
}, ',1000*$kg,');
</script>
';}else$i=null;ob_flush();flush();$H=@get_key_vals(($Hf?$Hf:$F),$i,false);if($i){echo
script("clearTimeout(timeout);");ob_flush();flush();}return$H;}function
get_token(){$af=rand(1,1e6);return($af^$_SESSION["token"]).":$af";}function
verify_token(){list($sg,$af)=explode(":",$_POST["token"]);return($af^$_SESSION["token"])==$sg;}function
lzw_decompress($La){$Jb=256;$Ma=8;$Za=array();$lf=0;$mf=0;for($s=0;$s<strlen($La);$s++){$lf=($lf<<8)+ord($La[$s]);$mf+=8;if($mf>=$Ma){$mf-=$Ma;$Za[]=$lf>>$mf;$lf&=(1<<$mf)-1;$Jb++;if($Jb>>$Ma)$Ma++;}}$Ib=range("\0","\xFF");$H="";foreach($Za
as$s=>$Ya){$Tb=$Ib[$Ya];if(!isset($Tb))$Tb=$fh.$fh[0];$H.=$Tb;if($s)$Ib[]=$fh.$Tb[0];$fh=$Tb;}return$H;}function
on_help($fb,$Ff=0){return
script("mixin(qsl('select, input'), {onmouseover: function (event) { helpMouseover.call(this, event, $fb, $Ff) }, onmouseout: helpMouseout});","");}function
edit_form($Q,$p,$I,$Kg){global$b,$x,$sg,$n;$ag=$b->tableName(table_status1($Q,true));page_header(($Kg?lang(10):lang(11)),$n,array("select"=>array($Q,$ag)),$ag);$b->editRowPrint($Q,$p,$I,$Kg);if($I===false)echo"<p class='error'>".lang(12)."\n";echo'<form action="" method="post" enctype="multipart/form-data" id="form">
';if(!$p)echo"<p class='error'>".lang(13)."\n";else{echo"<table cellspacing='0' class='layout'>".script("qsl('table').onkeydown = editingKeydown;");foreach($p
as$B=>$o){echo"<tr><th>".$b->fieldName($o);$Db=$_GET["set"][bracket_escape($B)];if($Db===null){$Db=$o["default"];if($o["type"]=="bit"&&preg_match("~^b'([01]*)'\$~",$Db,$ef))$Db=$ef[1];}$Y=($I!==null?($I[$B]!=""&&$x=="sql"&&preg_match("~enum|set~",$o["type"])?(is_array($I[$B])?array_sum($I[$B]):+$I[$B]):(is_bool($I[$B])?+$I[$B]:$I[$B])):(!$Kg&&$o["auto_increment"]?"":(isset($_GET["select"])?false:$Db)));if(!$_POST["save"]&&is_string($Y))$Y=$b->editVal($Y,$o);$r=($_POST["save"]?(string)$_POST["function"][$B]:($Kg&&preg_match('~^CURRENT_TIMESTAMP~i',$o["on_update"])?"now":($Y===false?null:($Y!==null?'':'NULL'))));if(!$_POST&&!$Kg&&$Y==$o["default"]&&preg_match('~^[\w.]+\(~',$Y))$r="SQL";if(preg_match("~time~",$o["type"])&&preg_match('~^CURRENT_TIMESTAMP~i',$Y)){$Y="";$r="now";}input($o,$Y,$r);echo"\n";}if(!support("table"))echo"<tr>"."<th><input name='field_keys[]'>".script("qsl('input').oninput = fieldChange;")."<td class='function'>".html_select("field_funs[]",$b->editFunctions(array("null"=>isset($_GET["select"]))))."<td><input name='field_vals[]'>"."\n";echo"</table>\n";}echo"<p>\n";if($p){echo"<input type='submit' value='".lang(14)."'>\n";if(!isset($_GET["select"])){echo"<input type='submit' name='insert' value='".($Kg?lang(15):lang(16))."' title='Ctrl+Shift+Enter'>\n",($Kg?script("qsl('input').onclick = function () { return !ajaxForm(this.form, '".lang(17)."â€¦', this); };"):"");}}echo($Kg?"<input type='submit' name='delete' value='".lang(18)."'>".confirm()."\n":($_POST||!$p?"":script("focus(qsa('td', qs('#form'))[1].firstChild);")));if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo'<input type="hidden" name="referer" value="',h(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"]),'">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="',$sg,'">
</form>
';}if(isset($_GET["file"])){if($_SERVER["HTTP_IF_MODIFIED_SINCE"]){header("HTTP/1.1 304 Not Modified");exit;}header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: immutable");if($_GET["file"]=="favicon.ico"){header("Content-Type: image/x-icon");echo
lzw_decompress("\0\0\0` \0„\0\n @\0´C„è\"\0`EãQ¸àÿ‡?ÀtvM'”JdÁd\\Œb0\0Ä\"™ÀfÓˆ¤îs5›ÏçÑAXPaJ“0„¥‘8„#RŠT©‘z`ˆ#.©ÇcíXÃşÈ€?À-\0¡Im? .«M¶€\0È¯(Ì‰ıÀ/(%Œ\0");}elseif($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo
lzw_decompress("\n1Ì‡“ÙŒŞl7œ‡B1„4vb0˜Ífs‘¼ên2BÌÑ±Ù˜Şn:‡#(¼b.\rDc)ÈÈa7E„‘¤Âl¦Ã±”èi1Ìs˜´ç-4™‡fÓ	ÈÎi7†³¹¤Èt4…¦ÓyèZf4°i–AT«VVéf:Ï¦,:1¦Qİ¼ñb2`Ç#ş>:7Gï—1ÑØÒs°™L—XD*bv<ÜŒ#£e@Ö:4ç§!fo·Æt:<¥Üå’¾™oâÜ\niÃÅğ',é»a_¤:¹iï…´ÁBvø|Nû4.5Nfi¢vpĞh¸°l¨ê¡ÖšÜO¦‰î= £OFQĞÄk\$¥Óiõ™ÀÂd2Tã¡pàÊ6„‹ş‡¡-ØZ€ƒ Ş6½£€ğh:¬aÌ,£ëî2#8Ğ±#’˜6nâî†ñJˆ¢h«t…Œ±Šä4O42ô½okŞ¾*r ©€@p@†!Ä¾ÏÃôş?Ğ6À‰r[ğLÁğ‹:2Bˆj§!HbóÃPä=!1V‰\"ˆ²0…¿\nSÆÆÏD7ÃìDÚ›ÃC!†!›à¦GÊŒ§ È+’=tCæ©.C¤À:+ÈÊ=ªªº²¡±å%ªcí1MR/”EÈ’4„© 2°ä± ã`Â8(áÓ¹[WäÑ=‰ySb°=Ö-Ü¹BS+É¯ÈÜı¥ø@pL4Ydã„qŠøã¦ğê¢6£3Ä¬¯¸AcÜŒèÎ¨Œk‚[&>ö•¨ZÁpkm]—u-c:Ø¸ˆNtæÎ´pÒŒŠ8è=¿#˜á[.ğÜŞ¯~ mËy‡PPá|IÖ›ùÀìQª9v[–Q•„\n–Ùrô'g‡+áTÑ2…­VÁõzä4£8÷(	¾Ey*#j¬2]­•RÒÁ‘¥)ƒÀ[N­R\$Š<>:ó­>\$;–> Ì\r»„ÎHÍÃTÈ\nw¡N åwØ£¦ì<ïËGwàöö¹\\Yó_ Rt^Œ>\r}ŒÙS\rzé4=µ\nL”%Jã‹\",Z 8¸™i÷0u©?¨ûÑô¡s3#¨Ù‰ :ó¦ûã½–ÈŞE]xİÒs^8£K^É÷*0ÑŞwŞàÈŞ~ãö:íÑiØşv2w½ÿ±û^7ãò7£cİÑu+U%{PÜ*4Ì¼éLX./!¼‰1CÅßqx!H¹ãFdù­L¨¤¨Ä Ï`6ëè5®™f€¸Ä†¨=Høl ŒV1“›\0a2×;Ô6†àöş_Ù‡Ä\0&ôZÜS d)KE'’€nµ[X©³\0ZÉŠÔF[P‘Ş˜@àß!‰ñYÂ,`É\"Ú·Â0Ee9yF>ËÔ9bº–ŒæF5:üˆ”\0}Ä´Š‡(\$Ó‡ë€37Hö£è M¾A°²6R•ú{Mqİ7G ÚC™Cêm2¢(ŒCt>[ì-tÀ/&C›]êetGôÌ¬4@r>ÇÂå<šSq•/åú”QëhmšÀĞÆôãôLÀÜ#èôKË|®™„6fKPİ\r%tÔÓV=\" SH\$} ¸)w¡,W\0F³ªu@Øb¦9‚\rr°2Ã#¬DŒ”Xƒ³ÚyOIù>»…n†Ç¢%ãù'‹İ_Á€t\rÏ„zÄ\\1˜hl¼]Q5Mp6k†ĞÄqhÃ\$£H~Í|Òİ!*4ŒñòÛ`Sëı²S tíPP\\g±è7‡\n-Š:è¢ªp´•”ˆl‹B¦î”7Ó¨cƒ(wO0\\:•Ğw”Áp4ˆ“ò{TÚújO¤6HÃŠ¶rÕ¥q\n¦É%%¶y']\$‚”a‘ZÓ.fcÕq*-êFWºúk„zƒ°µj‘°lgáŒ:‡\$\"ŞN¼\r#ÉdâÃ‚ÂÿĞscá¬Ì „ƒ\"jª\rÀ¶–¦ˆÕ’¼Ph‹1/‚œDA) ²İ[ÀknÁp76ÁY´‰R{áM¤Pû°ò@\n-¸a·6şß[»zJH,–dl B£ho³ìò¬+‡#Dr^µ^µÙeš¼E½½– ÄœaP‰ôõJG£zàñtñ 2ÇXÙ¢´Á¿V¶×ßàŞÈ³‰ÑB_%K=E©¸bå¼¾ßÂ§kU(.!Ü®8¸œüÉI.@KÍxnş¬ü:ÃPó32«”míH		C*ì:vâTÅ\nR¹ƒ•µ‹0uÂíƒæîÒ§]Î¯˜Š”P/µJQd¥{L–Ş³:YÁ2b¼œT ñÊ3Ó4†—äcê¥V=¿†L4ÎĞrÄ!ßBğY³6Í­MeLŠªÜçœöùiÀoĞ9< G”¤Æ•Ğ™Mhm^¯UÛNÀŒ·òTr5HiM”/¬nƒí³T [-<__î3/Xr(<‡¯Š†®Éô“ÌuÒ–GNX20å\r\$^‡:'9è¶O…í;×k¼†µf –N'a¶”Ç­bÅ,ËV¤ô…«1µïHI!%6@úÏ\$ÒEGÚœ¬1(mUªå…rÕ½ïßå`¡ĞiN+Ãœñ)šœä0lØÒf0Ã½[UâøVÊè-:I^ ˜\$Øs«b\re‡‘ugÉhª~9Ûßˆb˜µôÂÈfä+0¬Ô hXrİ¬©!\$—e,±w+„÷ŒëŒ3†Ì_âA…kšù\nkÃrõÊ›cuWdYÿ\\×={.óÄ˜¢g»‰p8œt\rRZ¿vJ:²>ş£Y|+Å@À‡ƒÛCt\r€jt½6²ğ%Â?àôÇñ’>ù/¥ÍÇğÎ9F`×•äòv~K¤áöÑRĞW‹ğz‘êlmªwLÇ9Y•*q¬xÄzñèSe®İ›³è÷£~šDàÍá–÷x˜¾ëÉŸi7•2ÄøÑOİ»’û_{ñú53âút˜›_ŸõzÔ3ùd)‹C¯Â\$?KÓªP%ÏÏT&ş˜&\0P×NA^­~¢ƒ pÆ öÏœ“Ôõ\r\$ŞïĞÖìb*+D6ê¶¦ÏˆŞíJ\$(ÈolŞÍh&”ìKBS>¸‹ö;z¶¦xÅoz>íœÚoÄZğ\nÊ‹[Ïvõ‚ËÈœµ°2õOxÙVø0fû€ú¯Ş2BlÉbkĞ6ZkµhXcdê0*ÂKTâ¯H=­•Ï€‘p0ŠlVéõèâ\r¼Œ¥nm¦ï)( ú");}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress("f:›ŒgCI¼Ü\n8œÅ3)°Ë7œ…†81ĞÊx:\nOg#)Ğêr7\n\"†è´`ø|2ÌgSi–H)N¦S‘ä§\r‡\"0¹Ä@ä)Ÿ`(\$s6O!ÓèœV/=Œ' T4æ=„˜iS˜6IO G#ÒX·VCÆs¡ Z1.Ğhp8,³[¦Häµ~Cz§Éå2¹l¾c3šÍés£‘ÙI†bâ4\néF8Tà†I˜İ©U*fz¹är0EÆÀØy¸ñfY.:æƒIŒÊ(Øc·áÎ‹!_l™í^·^(¶šN{S–“)rËqÁY“–lÙ¦3Š3Ú\n˜+G¥Óêyºí†Ëi¶ÂîxV3w³uhã^rØÀº´aÛ”ú¹cØè\r“¨ë(.ÂˆºChÒ<\r)èÑ£¡`æ7£íò43'm5Œ£È\nPÜ:2£P»ª‹q òÿÅC“}Ä«ˆúÊÁê38‹BØ0hR‰Èr(œ0¥¡b\\0ŒHr44ŒÁB!¡pÇ\$rZZË2Ü‰.Éƒ(\\5Ã|\nC(Î\"€P…ğø.ĞNÌRTÊÎ“Àæ>HN…8HPá\\¬7Jp~„Üû2%¡ĞOC¨1ã.ƒ§C8Î‡HÈò*ˆj°…á÷S(¹/¡ì¬6KUœÊ‡¡<2‰pOI„ôÕ`Ôäâ³ˆdOH Ş5-üÆ4ŒãpX25-Ò¢òÛˆ°z7£¸\"(°P \\32:]UÚèíâß…!]¸<·AÛÛ¤’ĞßiÚ°‹l\rÔ\0v²Î#J8«ÏwmíÉ¤¨<ŠÉ æü%m;p#ã`XDŒø÷iZøN0Œ•È9ø¨å Áè`…wJD¿¾2Ò9tŒ¢*øÎyìËNiIh\\9ÆÕèĞ:ƒ€æáxï­µyl*šÈˆÎæY Ü‡øê8’W³â?µŞ›3ÙğÊ!\"6å›n[¬Ê\r­*\$¶Æ§¾nzxÆ9\rì|*3×£pŞï»¶:(p\\;ÔËmz¢ü§9óĞÑÂŒü8N…Áj2½«Î\rÉHîH&Œ²(Ãz„Á7iÛk£ ‹Š¤‚c¤‹eòı§tœÌÌ2:SHóÈ Ã/)–xŞ@éåt‰ri9¥½õëœ8ÏÀËïyÒ·½°VÄ+^WÚ¦­¬kZæY—l·Ê£Œ4ÖÈÆ‹ª¶À¬‚ğ\\EÈ{î7\0¹p†€•D€„i”-TæşÚû0l°%=Á ĞËƒ9(„5ğ\n\n€n,4‡\0èa}Üƒ.°öRsï‚ª\02B\\Ûb1ŸS±\0003,ÔXPHJspåd“Kƒ CA!°2*WŸÔñÚ2\$ä+Âf^\n„1Œ´òzEƒ Iv¤\\äœ2É .*A°™”E(d±á°ÃbêÂÜ„Æ9‡‚â€ÁDh&­ª?ÄH°sQ˜2’x~nÃJ‹T2ù&ãàeRœ½™GÒQTwêİ‘»õPˆâã\\ )6¦ôâœÂòsh\\3¨\0R	À'\r+*;RğHà.“!Ñ[Í'~­%t< çpÜK#Â‘æ!ñlßÌğLeŒ³œÙ,ÄÀ®&á\$	Á½`”–CXš‰Ó†0Ö­å¼û³Ä:Méh	çÚœGäÑ!&3 D<!è23„Ã?h¤J©e Úğhá\r¡m•˜ğNi¸£´’†ÊNØHl7¡®v‚êWIå.´Á-Ó5Ö§ey\rEJ\ni*¼\$@ÚRU0,\$U¿E†¦ÔÔÂªu)@(tÎSJkáp!€~­‚àd`Ì>¯•\nÃ;#\rp9†jÉ¹Ü]&Nc(r€ˆ•TQUª½S·Ú\08n`«—y•b¤ÅLÜO5‚î,¤ò‘>‚†xââ±fä´’âØ+–\"ÑI€{kMÈ[\r%Æ[	¤eôaÔ1! èÿí³Ô®©F@«b)RŸ£72ˆî0¡\nW¨™±L²ÜœÒ®tdÕ+íÜ0wglø0n@òêÉ¢ÕiíM«ƒ\nA§M5nì\$E³×±NÛál©İŸ×ì%ª1 AÜûºú÷İkñrîiFB÷Ïùol,muNx-Í_ Ö¤C( fél\r1p[9x(i´BÒ–²ÛzQlüº8CÔ	´©XU Tb£İIİ`•p+V\0î‹Ñ;‹CbÎÀXñ+Ï’sïü]H÷Ò[ák‹x¬G*ô†]·awnú!Å6‚òâÛĞmSí¾“IŞÍKË~/Ó¥7ŞùeeNÉòªS«/;dåA†>}l~Ïê ¨%^´fçØ¢pÚœDEîÃa·‚t\nx=ÃkĞ„*dºêğT—ºüûj2ŸÉjœ\n‘ É ,˜e=‘†M84ôûÔa•j@îTÃsÔänf©İ\nî6ª\rdœ¼0ŞíôYŠ'%Ô“íŞ~	Ò¨†<ÖË–Aî‹–H¿G‚8ñ¿Îƒ\$z«ğ{¶»²u2*†àa–À>»(wŒK.bP‚{…ƒoı”Â´«zµ#ë2ö8=É8>ª¤³A,°e°À…+ìCè§xõ*ÃáÒ-b=m‡™Ÿ,‹a’Ãlzkï\$Wõ,mJiæÊ§á÷+‹èı0°[¯ÿ.RÊsKùÇäXçİZLËç2`Ì(ïCàvZ¡ÜİÀ¶è\$×¹,åD?H±ÖNxXôó)’îM¨‰\$ó,Í*\nÑ£\$<qÿÅŸh!¿¹S“âƒÀŸxsA!˜:´K¥Á}Á²“ù¬£œRşšA2k·Xp\n<÷ş¦ıëlì§Ù3¯ø¦È•VV¬}£g&Yİ!†+ó;<¸YÇóŸYE3r³Ùñ›Cío5¦Åù¢Õ³Ïkkş…ø°ÖÛ£«Ït÷’Uø…­)û[ıßÁî}ïØu´«lç¢:DŸø+Ï _oãäh140ÖáÊ0ø¯bäK˜ã¬’ öşé»lGª„#ªš©ê†¦©ì|Udæ¶IK«êÂ7à^ìà¸@º®O\0HÅğHiŠ6\r‡Û©Ü\\cg\0öãë2BÄ*eà\n€š	…zr!nWz& {H–ğ'\$X  w@Ò8ëDGr*ëÄİHå'p#Ä®€¦Ô\ndü€÷,ô¥—,ü;g~¯\0Ğ#€Ì²EÂ\rÖI`œî'ƒğ%EÒ. ]`ÊĞ›…î%&Ğîm°ı\râŞ%4S„vğ#\n fH\$%ë-Â#­ÆÑqBâíæ ÀÂQ-ôc2Š§‚&ÂÀÌ]à™ èqh\rñl]à®s ĞÑhä7±n#±‚‚Ú-àjE¯Frç¤l&dÀØÙåzìF6¸ˆÁ\" “|¿§¢s@ß±®åz)0rpÚ\0‚X\0¤Ùè|DL<!°ôo„*‡D¶{.B<Eª‹‹0nB(ï |\r\nì^©à h³!‚Öêr\$§’(^ª~èŞÂ/pq²ÌB¨ÅOšˆğú,\\µ¨#RRÎ%ëäÍdĞHjÄ`Â ô®Ì­ Vå bS’d§iE‚øïoh´r<i/k\$-Ÿ\$o”¼+ÆÅ‹ÎúlÒŞO³&evÆ’¼iÒjMPA'u'Î’( M(h/+«òWD¾So·.n·.ğn¸ìê(œ(\"­À§hö&p†¨/Ë/1DÌŠçjå¨¸EèŞ&â¦€,'l\$/.,Äd¨…‚W€bbO3óB³sH :J`!“.€ª‚‡Àû¥ ,FÀÑ7(‡ÈÔ¿³û1Šlås ÖÒ‘²—Å¢q¢X\rÀš®ƒ~Ré°±`®Òó®Y*ä:R¨ùrJ´·%LÏ+n¸\"ˆø\r¦ÎÍ‡H!qb¾2âLi±%ÓŞÎ¨Wj#9ÓÔObE.I:…6Á7\0Ë6+¤%°.È…Ş³a7E8VSå?(DG¨Ó³Bë%;ò¬ùÔ/<’´ú¥À\r ì´>ûMÀ°@¶¾€H DsĞ°Z[tH£Enx(ğŒ©R xñû@¯şGkjW”>ÌÂÚ#T/8®c8éQ0Ëè_ÔIIGII’!¥ğŠYEdËE´^tdéthÂ`DV!Cæ8¥\r­´Ÿb“3©!3â@Ù33N}âZBó3	Ï3ä30ÚÜM(ê>‚Ê}ä\\Ñtê‚f fŒËâI\r®€ó337 XÔ\"tdÎ,\nbtNO`Pâ;­Ü•Ò­ÀÔ¯\$\n‚ßäZÑ­5U5WUµ^hoıàætÙPM/5K4Ej³KQ&53GX“Xx)Ò<5D…\rûVô\nßr¢5bÜ€\\J\">§è1S\r[-¦ÊDuÀ\rÒâ§Ã)00óYõÈË¢·k{\nµÄ#µŞ\r³^·‹|èuÜ»Uå_nïU4ÉUŠ~YtÓ\rIšÃ@ä³™R ó3:ÒuePMSè0TµwW¯XÈòòD¨ò¤KOUÜà•‡;Uõ\n OYéYÍQ,M[\0÷_ªDšÍÈW ¾J*ì\rg(]à¨\r\"ZC‰©6uê+µYóˆY6Ã´0ªqõ(Ùó8}ó3AX3T h9j¶jàfõMtåPJbqMP5>ğÈø¶©Y‡k%&\\‚1d¢ØE4À µYnÊí\$<¥U]Ó‰1‰mbÖ¶^Òõš ê\"NVéßp¶ëpõ±eMÚŞ×WéÜ¢î\\ä)\n Ë\nf7\n×2´õr8‹—=Ek7tVš‡µ7P¦¶LÉía6òòv@'‚6iàïj&>±â;­ã`Òÿa	\0pÚ¨(µJÑë)«\\¿ªnûòÄ¬m\0¼¨2€ôeqJö­PôtŒë±fjüÂ\"[\0¨·†¢X,<\\Œî¶×â÷æ·+md†å~âàš…Ñs%o°´mn×),×„æÔ‡²\r4¶Â8\r±Î¸×mE‚H]‚¦˜üÖHW­M0Dïß€—å~Ë˜K˜îE}ø¸´à|fØ^“Ü×\r>Ô-z]2s‚xD˜d[s‡tS¢¶\0Qf-K`­¢‚tàØ„wT¯9€æZ€à	ø\nB£9 Nb–ã<ÚBşI5o×oJñpÀÏJNdåË\rhŞÃ2\"àxæHCàİ–:øı9Yn16Æôzr+z±ùş\\’÷•œôm Ş±T öò ÷@Y2lQ<2O+¥%“Í.Óƒhù0AŞñ¸ŠÃZ‹2R¦À1£Š/¯hH\r¨X…ÈaNB&§ ÄM@Ö[xŒ‡Ê®¥ê–â8&LÚVÍœvà±*šj¤ÛšGHåÈ\\Ù®	™²¶&sÛ\0Qš \\\"èb °	àÄ\rBs›Éw‚	ÙáBN`š7§Co(ÙÃà¨\nÃ¨“¨1š9Ì*E˜ ñS…ÓU0Uº tš'|”m™°Ş?h[¢\$.#É5	 å	p„àyBà@Rô]£…ê@|„§{™ÀÊP\0xô/¦ w¢%¤EsBd¿§šCUš~O×·àPà@Xâ]Ô…¨Z3¨¥1¦¥{©eLY‰¡ŒÚ¢\\’(*R` 	à¦\n…ŠàºÌQCFÈ*¹¹àéœ¬Úp†X|`N¨‚¾\$€[†‰’@ÍU¢àğ¦¶àZ¥`Zd\"\\\"…‚¢£)«‡Iˆ:ètšìoDæ\0[²¨à±‚-©“ gí³‰™®*`hu%£,€”¬ãIµ7Ä«²Hóµm¤6Ş}®ºNÖÍ³\$»MµUYf&1ùÀ›e]pz¥§ÚI¤Åm¶G/£ ºw Ü!•\\#5¥4I¥d¹EÂhq€å¦÷Ñ¬kçx|Úk¥qDšb…z?§º‰>úƒ¾:†“[èLÒÆ¬Z°Xš®:¹„·ÚÇjßw5	¶Y¾0 ©Â“­¯\$\0C¢†dSg¸ë‚ {@”\n`	ÀÃüC ¢·»Mºµâ»²# t}xÎN„÷º‡{ºÛ°)êûCƒÊFKZŞj™Â\0PFY”BäpFk–›0<Ú>ÊD<JE™šg\rõ.“2–ü8éU@*Î5fkªÌJDìÈÉ4•TDU76É/´è¯@·‚K+„ÃöJ®ºÃÂí@Ó=ŒÜWIOD³85MšNº\$Rô\0ø5¨\ràù_ğªœìEœñÏI«Ï³Nçl£Òåy\\ô‘ˆÇqU€ĞQû ª\n@’¨€ÛºÃpš¬¨PÛ±«7Ô½N\rıR{*qmİ\$\0R”×Ô“ŠÅåqĞÃˆ+U@ŞB¤çOf*†CË¬ºMCä`_ èüò½ËµNêæTâ5Ù¦C×»© ¸à\\WÃe&_XŒ_Øhå—ÂÆBœ3ÀŒÛ%ÜFW£û|™GŞ›'Å[¯Å‚À°ÙÕV Ğ#^\rç¦GR€¾˜€P±İFg¢ûî¯ÀYi û¥Çz\nâ¨Ş+ß^/“¨€‚¼¥½\\•6èßb¼dmh×â@qíÕAhÖ),J­×W–Çcm÷em]ÓeÏkZb0ßåşYñ]ymŠè‡fØe¹B;¹ÓêOÉÀwŸapDWûŒÉÜÓ{›\0˜À-2/bN¬sÖ½Ş¾Ra“Ï®h&qt\n\"ÕiöRmühzÏeø†àÜFS7µĞPPòä–¤âÜ:B§ˆâÕsm¶­Y düŞò7}3?*‚túòéÏlTÚ}˜~€„€ä=cı¬ÖŞÇ	Ú3…;T²LŞ5*	ñ~#µA•¾ƒ‘sx-7÷f5`Ø#\"NÓb÷¯G˜Ÿ‹õ@Üeü[ïø¤Ìs‘˜€¸-§˜M6§£qqš h€e5…\0Ò¢À±ú*àbøISÜÉÜFÎ®9}ıpÓ-øı`{ı±É–kP˜0T<„©Z9ä0<Õš\r­€;!Ãˆgº\r\nKÔ\n•‡\0Á°*½\nb7(À_¸@,îe2\rÀ]–K…+\0Éÿp C\\Ñ¢,0¬^îMĞ§šº©“@Š;X\r•ğ?\$\r‡j’+ö/´¬BöæP ½‰ù¨J{\"aÍ6˜ä‰œ¹|å£\n\0»à\\5“Ğ	156ÿ† .İ[ÂUØ¯\0dè²8Yç:!Ñ²‘=ºÀX.²uCªŠŒö!Sº¸‡o…pÓBİüÛ7¸­Å¯¡Rh­\\h‹E=úy:< :u³ó2µ80“si¦ŸTsBÛ@\$ Íé@Çu	ÈQº¦.ô‚T0M\\/ê€d+Æƒ\n‘¡=Ô°dŒÅëA¢¸¢)\r@@Âh3€–Ù8.eZa|.â7YkĞcÀ˜ñ–'D#‡¨Yò@Xq–=M¡ï44šB AM¤¯dU\"‹Hw4î(>‚¬8¨²ÃC¸?e_`ĞÅX:ÄA9Ã¸™ôp«GĞä‡Gy6½ÃF“Xr‰¡l÷1¡½Ø»B¢Ã…9Rz©õhB„{€™\0ëå^‚Ã-â0©%Dœ5F\"\"àÚÜÊÂ™úiÄ`ËÙnAf¨ \"tDZ\"_àV\$Ÿª!/…D€áš†ğ¿µ‹´ˆÙ¦¡Ì€F,25Éj›Tëá—y\0…N¼x\rçYl¦#‘ÆEq\nÍÈB2œ\nìà6·…Ä4Ó×”!/Â\nóƒ‰Q¸½*®;)bR¸Z0\0ÄCDoŒË48À•´µ‡Ğe‘\nã¦S%\\úPIk‡(0ÁŒu/™‹G²Æ¹ŠŒ¼\\Ë} 4Fp‘Gû_÷G?)gÈotº[vÖ\0°¸?bÀ;ªË`(•ÛŒà¶NS)\nãx=èĞ+@êÜ7ƒjú0—,ğ1Ã…z™“­>0ˆ‰GcğãL…VXôƒ±ÛğÊ%À…Á„Q+øéoÆFõÈéÜ¶Ğ>Q-ãc‘ÚÇl‰¡³¤wàÌz5G‘ê‚@(h‘cÓHõÇr?ˆšNbş@É¨öÇø°îlx3‹U`„rwª©ÔUÃÔôtØ8Ô=Àl#òõlÿä¨‰8¥E\"Œƒ˜™O6\n˜Â1e£`\\hKf—V/Ğ·PaYKçOÌı éàx‘	‰Oj„ór7¥F;´êB»‘ê£íÌ’‡¼>æĞ¦²V\rÄ–Ä|©'Jµz«¼š”#’PBä’Y5\0NC¤^\n~LrR’Ô[ÌŸRÃ¬ñgÀeZ\0x›^»i<Qã/)Ó%@Ê’™fB²HfÊ{%Pà\"\"½ø@ªş)ò’‘“DE(iM2‚S’*ƒyòSÁ\"âñÊeÌ’1Œ«×˜\n4`Ê©>¦Q*¦Üy°n”’¥TäuÔâä”Ñ~%+W²XK‹Œ£Q¡[Ê”àlPYy#DÙ¬D<«FLú³Õ@Á6']Æ‹‡û\rFÄ`±!•%\n0cĞôÀË©%c8WrpGƒ.TœDo¾UL2Ø*é|\$¬:èœr˜½@æñè&Ò4‹HŠ> ‘ %0*ŒZc(@Ü]óÌQ:*¬“â(&\"x'JO³1¹º`>7	#Ù\"O4PXü±”|B4»é‰[Ê˜éÙ˜\$nïˆ1`ôêGSAõÖËAH» \"†)ğà©ãS¨ûf”É¦Áº-\"ËWú+É–º\0s-[”foÙ§ÍD«ğxóæ¸õ¾=Cš.õ“9³­ÎfïÀcÁ\0Â‹7¡?Ã“95´Ö¦ZÇ0­îfì­¨àøëH?R'q>oÚÊ@aDŸùG[;G´D¹BBdÄ¡—q –¥2¤|1¹ìq™²äÀÎå²w<Ü#ª§EY½^š§ ­Q\\ë[XñåèÅ>?vï[ ‡æŠIÉÍÑ „™œÌg\0Ç)´…®g…uŒĞg42jÃº'óTä„‹Ívy,u’ÛD†=pH\\ƒ”^bäìqØ„ÄitÃÅğX…À£FPÉ@Pú¥TŠ¾i2#°g€—Dá®™ñ%9™@‚");}elseif($_GET["file"]=="jush.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress('');}else{header("Content-Type: image/gif");switch($_GET["file"]){case"plus.gif":echo'';break;case"cross.gif":echo'';break;case"up.gif":echo'';break;case"down.gif":echo'';break;case"arrow.gif":echo'';break;}}exit;}if($_GET["script"]=="version"){$Gc=file_open_lock(get_temp_dir()."/adminer.version");if($Gc)file_write_unlock($Gc,serialize(array("signature"=>$_POST["signature"],"version"=>$_POST["version"])));exit;}global$b,$h,$m,$Mb,$Rb,$Zb,$n,$Ic,$Mc,$aa,$kd,$x,$ba,$zd,$ke,$Je,$Tf,$Qc,$sg,$wg,$U,$Jg,$ca;if(!$_SERVER["REQUEST_URI"])$_SERVER["REQUEST_URI"]=$_SERVER["ORIG_PATH_INFO"];if(!strpos($_SERVER["REQUEST_URI"],'?')&&$_SERVER["QUERY_STRING"]!="")$_SERVER["REQUEST_URI"].="?$_SERVER[QUERY_STRING]";if($_SERVER["HTTP_X_FORWARDED_PREFIX"])$_SERVER["REQUEST_URI"]=$_SERVER["HTTP_X_FORWARDED_PREFIX"].$_SERVER["REQUEST_URI"];$aa=($_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off"))||ini_bool("session.cookie_secure");@ini_set("session.use_trans_sid",false);if(!defined("SID")){session_cache_limiter("");session_name("adminer_sid");$Ce=array(0,preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]),"",$aa);if(version_compare(PHP_VERSION,'5.2.0')>=0)$Ce[]=true;call_user_func_array('session_set_cookie_params',$Ce);session_start();}remove_slashes(array(&$_GET,&$_POST,&$_COOKIE),$tc);if(function_exists("get_magic_quotes_runtime")&&get_magic_quotes_runtime())set_magic_quotes_runtime(false);@set_time_limit(0);@ini_set("zend.ze1_compatibility_mode",false);@ini_set("precision",15);$zd=array('en'=>'English','ar'=>'Ø§Ù„Ø¹Ø±Ø¨ÙŠØ©','bg'=>'Ğ‘ÑŠĞ»Ğ³Ğ°Ñ€ÑĞºĞ¸','bn'=>'à¦¬à¦¾à¦‚à¦²à¦¾','bs'=>'Bosanski','ca'=>'CatalÃ ','cs'=>'ÄŒeÅ¡tina','da'=>'Dansk','de'=>'Deutsch','el'=>'Î•Î»Î»Î·Î½Î¹ÎºÎ¬','es'=>'EspaÃ±ol','et'=>'Eesti','fa'=>'ÙØ§Ø±Ø³ÛŒ','fi'=>'Suomi','fr'=>'FranÃ§ais','gl'=>'Galego','he'=>'×¢×‘×¨×™×ª','hu'=>'Magyar','id'=>'Bahasa Indonesia','it'=>'Italiano','ja'=>'æ—¥æœ¬èª','ka'=>'áƒ¥áƒáƒ áƒ—áƒ£áƒšáƒ˜','ko'=>'í•œêµ­ì–´','lt'=>'LietuviÅ³','ms'=>'Bahasa Melayu','nl'=>'Nederlands','no'=>'Norsk','pl'=>'Polski','pt'=>'PortuguÃªs','pt-br'=>'PortuguÃªs (Brazil)','ro'=>'Limba RomÃ¢nÄƒ','ru'=>'Ğ ÑƒÑÑĞºĞ¸Ğ¹','sk'=>'SlovenÄina','sl'=>'Slovenski','sr'=>'Ğ¡Ñ€Ğ¿ÑĞºĞ¸','sv'=>'Svenska','ta'=>'à®¤â€Œà®®à®¿à®´à¯','th'=>'à¸ à¸²à¸©à¸²à¹„à¸—à¸¢','tr'=>'TÃ¼rkÃ§e','uk'=>'Ğ£ĞºÑ€Ğ°Ñ—Ğ½ÑÑŒĞºĞ°','vi'=>'Tiáº¿ng Viá»‡t','zh'=>'ç®€ä½“ä¸­æ–‡','zh-tw'=>'ç¹é«”ä¸­æ–‡',);function
get_lang(){global$ba;return$ba;}function
lang($u,$fe=null){if(is_string($u)){$Me=array_search($u,get_translations("en"));if($Me!==false)$u=$Me;}global$ba,$wg;$vg=($wg[$u]?$wg[$u]:$u);if(is_array($vg)){$Me=($fe==1?0:($ba=='cs'||$ba=='sk'?($fe&&$fe<5?1:2):($ba=='fr'?(!$fe?0:1):($ba=='pl'?($fe%10>1&&$fe%10<5&&$fe/10%10!=1?1:2):($ba=='sl'?($fe%100==1?0:($fe%100==2?1:($fe%100==3||$fe%100==4?2:3))):($ba=='lt'?($fe%10==1&&$fe%100!=11?0:($fe%10>1&&$fe/10%10!=1?1:2)):($ba=='bs'||$ba=='ru'||$ba=='sr'||$ba=='uk'?($fe%10==1&&$fe%100!=11?0:($fe%10>1&&$fe%10<5&&$fe/10%10!=1?1:2)):1)))))));$vg=$vg[$Me];}$xa=func_get_args();array_shift($xa);$Ec=str_replace("%d","%s",$vg);if($Ec!=$vg)$xa[0]=format_number($fe);return
vsprintf($Ec,$xa);}function
switch_lang(){global$ba,$zd;echo"<form action='' method='post'>\n<div id='lang'>",lang(19).": ".html_select("lang",$zd,$ba,"this.form.submit();")," <input type='submit' value='".lang(20)."' class='hidden'>\n","<input type='hidden' name='token' value='".get_token()."'>\n";echo"</div>\n</form>\n";}if(isset($_POST["lang"])&&verify_token()){cookie("adminer_lang",$_POST["lang"]);$_SESSION["lang"]=$_POST["lang"];$_SESSION["translations"]=array();redirect(remove_from_uri());}$ba="en";if(isset($zd[$_COOKIE["adminer_lang"]])){cookie("adminer_lang",$_COOKIE["adminer_lang"]);$ba=$_COOKIE["adminer_lang"];}elseif(isset($zd[$_SESSION["lang"]]))$ba=$_SESSION["lang"];else{$ra=array();preg_match_all('~([-a-z]+)(;q=([0-9.]+))?~',str_replace("_","-",strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"])),$Nd,PREG_SET_ORDER);foreach($Nd
as$A)$ra[$A[1]]=(isset($A[3])?$A[3]:1);arsort($ra);foreach($ra
as$y=>$We){if(isset($zd[$y])){$ba=$y;break;}$y=preg_replace('~-.*~','',$y);if(!isset($ra[$y])&&isset($zd[$y])){$ba=$y;break;}}}$wg=$_SESSION["translations"];if($_SESSION["translations_version"]!=4240734095){$wg=array();$_SESSION["translations_version"]=4240734095;}function
get_translations($yd){switch($yd){case"en":$g="A9D“yÔ@s:ÀGà¡(¸ffƒ‚Š¦ã	ˆÙ:ÄS°Şa2\"1¦..L'ƒI´êm‘#Çs,†KƒšOP#IÌ@%9¥i4Èo2ÏÆó €Ë,9%ÀPÀb2£a¸àr\n2›NCÈ(Şr4™Í1C`(:Ebç9AÈi:‰&ã™”åy·ˆFó½ĞY‚ˆ\r´\n– 8ZÔS=\$Aœ†¤`Ñ=ËÜŒ²‚0Ê\nÒãdFé	ŒŞn:ZÎ°)­ãQ¦ÕÈmwÛø€İO¼êmfpQËÎ‚‰†qœêaÊÄ¯±#q®–w7SX3” ‰œŠ˜o¢\n>Z—M„ziÃÄs;ÙÌ’‚„_Å:øõğ#|@è46ƒÃ:¾\r-z| (j*œ¨Œ0¦:-hæé/Ì¸ò8)+r^1/Ğ›¾Î·,ºZÓˆKXÂ9,¢pÊ:>#Öã(Ş6ÅqBô7ÃØ4µ¨È2¶LtÃ.ËÂÏ\nH¤h\n|Z29CzÔ7I‹ğÚæH\nj=)­Ã(î/\nŒCË:„³\$Í0Ê–ˆZs˜jÔ±8Æ4N`ò;ÀPö9Ikl Œm‹_<\"ôH\"…²ºLÌÚÔ£Ô2ÑÂè¸Ïq£a	Šr¡4©ÔˆÂ“1OAH<²M	ËU\$ÉóÕVÊúØ%¸\$	Ğš&‡B˜¦cÍœ<‹´ˆÚŒ’…KF©¬Úââ§­~Ÿ,êr(‹ J\0ApÜÎ€9À«âP&'°h6B;¿ƒ0Í·í\"üÆRˆÎ‚¯\" ŞJãpò¯ƒ¨Æ1©#˜Ì:¶İ…Œ¼P‘õ[²µ§éÚÙ3µ¦\r„OØb‡â8+‹¯˜Ì!…ºôÀìÕÜ:³¬0™)`ˆ>ˆx–(”\nŒ[ªŸ×òá„É/ØË]Gã@\$cB3¡ÑO˜t…ã¾Ô\$£º³Œá|€¨è¥7á}º³xŒ!õı¢İ°Xà4ÇØÊ;<¼y†9%	R£\n½Ú,ŸÃq	¨]­ëºşÃ±›.Ï´í{jÖ¿Û†å¼]P#_¼¨ğ’±\"õé¿ğ6øA?ãzBÌJãZxöÊÒÅ<p~„õ=Ûò¦Ã—DùZJ0Ñ<(î“b¾Œ09i£”hŒ#3;­ez&—ÊY¶ÿ²ğ0Æ\0ÇK2ØÃ0=mØ¢Ëy¿\\çùù“Ö_É¨ \n ( ´ÿËà \0’\"H‘É&4D !šó~öNi—#FD4™2.eIA©€¥ÂbLÜÓÕP\$ğŸ0æ{˜ƒ7~\$ğ7xdqò\n\0004§·vZ[¯3Ä1†òhÑº9Gd„!…0¤¡Â\rÎmRšÈvI90f¥­È’²ZKÍòB§ö˜HIN8d'Å=ˆW5±ã-oİÆ.SH ÅÜueù  !ÄÙ•H¥š‹Stíhÿ«ÆG‰'Ç¸Ê´†«ËL\n	áL*CÀæÂ‘5aÑ.SÎÖ£Ù>ñ}s5rJsƒ“}yEÜ€½“JA‰§dâ@H¥\0gL(„ÂH‚Œ™#ÁRœÃtXix‘èÍY&\0JI&,Ÿ+ÓÈqÑ\\1œ%…VÎPÊ¬HâÚS‰sNH”¬MÙ~3¯QT&Æ«§3åÊr}«Iû8•Ñ¸ +ê‚C†„`+’ñ5®s²Hâ‘³„NíßÌÂËÂ	'ºG=@B F áqâîsIB¨0i~{Nb[\nœÒµ0F„«ÉŞÇæÄz„Mo‚ú¶\"ùq\\„¦ÑğÃ›5‡JÙ.‹á`.åä*3j–‹]x5…É AFbƒ?˜á´³”cÀ”ITI§F«•Ã@o'„/DÇNÃ½:¦j’t(’j‚7ê<<B¢õPN\nyQm6AR‹›Õ4UVWù_˜YOSŒò„ğ—A‰\rŸ-ZÑ;HHm0.";break;case"ar":$g="ÙC¶P‚Â²†l*„\r”,&\nÙA¶í„ø(J.™„0Se\\¶\r…ŒbÙ@¶0´,\nQ,l)ÅÀ¦Âµ°¬†Aòéj_1CĞM…«e€¢S™\ng@ŸOgë¨ô’XÙDMë)˜°0Œ†cA¨Øn8Çe*y#au4¡ ´Ir*;rSÁUµdJ	}‰ÎÑ*zªU@¦ŠX;ai1l(nóÕòıÃ[Óy™dŞu'c(€ÜoF“±¤Øe3™Nb¦ êp2NšS¡ Ó³:LZúz¶PØ\\bæ¼uÄ.•[¶Q`u	!Š­Jyµˆ&2¶(gTÍÔSÑšMÆxì5g5¸K®K¦Â¦àØ÷á—0Ê€(ª7\rm8î7(ä9\rã’f\"7NÂ9´£ ŞÙ4Ãxè¶ã„xæ;Á#\"¸¿…Š´¥2É°W\"J\nî¦¬Bê'hkÀÅ«b¦Diâ\\@ªêÊp¬•êyf ­’9ÊÚV¨?‘TXW¡‰¡FÃÇ{â¹3)\"ªW9Ï|Á¨eRhU±¬Òªû1ÆÁPˆ>¨ê„\"o|Ù7£éÚLQi\\¬ H\"¨¤ª#¨›1ê£ÄÅ‹ï#ÅòƒÜ‡Jrª >³J­ÑÈsŞœ:îšã?P]<­T(\"'?°nœpSJ©SZªÉ»¬‰\"Ã\"T(üÌ<¶@SN¨^v8bšW‰±Vµ#Ï«¢3ÒhÃDËÚ>T&‰´´êµ´Lğ¾ñe´ÈSµx“£å|úÆ'È±@I«²ƒËwº²[IÏl~È!TÙl¨tK•=ë®œ›¨)uÒÛ„¨83¨Q_@	¢ht)Š`PÔ5ãhÚ‹cT0‹©¢CÓìğ OhH\"7î^¥¶pL\n7gM*˜gÚÊ<7cpæ4úRg‚:Å`BCè6Ló@0M(Ş3ÃcÔ2ÑQ[!*j•=A@º‘bO!B ŞÔ\r£Ü<„®”:Œcd9ŒÃ¨Ø\rƒxÎõach9oÎ0½A\"×CkÔ:·a@æ°¸c\rO±5ƒ·/•Û¾­…jf*4Pf3gÃ®–õ„ÉË\rĞ71éºXÉàÂĞë£0z\r è8aĞ^şˆ\\0öûŒáxÊ7ğ–€:iàD{ÍÓÔ3‡xÂ)îò€!3êp¹NÆÁ«ÈZS~^(\$®HWsjt)õUªRfPy»F¥\"\0ä®š8p\r-u<†+Çy/-æ¼÷¢Ş›Õw/\\9=—¶öšCÛim4‚\"˜‚Hm¼6½°èú_[<ÔŞ†õtmaØa\rf”4¡Äİ]Èn…<ı“ã® Ì©)?j¤Æ‹„\\üŠ	!.¬4PÂßMÜ×80ÄiC‚vÊ« Â Ro­ıÀ¸7\náĞ\$d7¦Ò.‘°Ã\rA\0c‚Ø4†ØÑê6G%ø·RJ¾‘:¸a§ñ¶v\0jC@pSD4‰Ÿ³CEJ‚‘ÉH‘6Úk¹ŒœÚƒTk\rq°ªé\r5cfoĞªÎ;Ë8¸¢!³xaÍ·ç\$\rìD\rÁÁÌ@Àæ‡Ñc7ÁŒ4C°ÒŞH ŒñğÒ†0Âì›!4\$ä4Y\0†ÂF”J‚+EFê¤ÙmHhÁ‡—ú’_y\r-ˆšP¢U2\\İ ‰iäø¡Àê\nØ²~'R\0HÅFAŠE(çÈåˆ(BÔ!ZFjŒ“Å'ğÏ	˜I#AäÏ‚\0ÈUÑ§Bè@7Có|lÚèq¦É†d]¬\"x%7\n„#ä¸C¦Å§Øš 'Óñ\n<)…BDYœü/èäº,¤Uá{¢Ñ8¢BlNŠ\$¨ÄºOÉrsêº»Èª–ÉMUŒL ‡D¡h¢ºÚ[Sã\n (ÚK7!·p¯P<0Å5Á\0S\n!0ib\rkÃÁP(XZZä¡’rTÖ›‘´iSR\$ØXõF~ÈB´.uHŸ£¬Û-:³\$–±5ÆMÓí¨PÖ­—\"ØlY­ª0Š”ƒ¸å	˜CÈ6º|Ckhˆá‡i\rLål;‡Á¤37ˆŠLÂ5\r®ÉÍ·\"B F áŸ»ÊNzí¢)}ôf·1{Å½x#ªøÃ’5\n[5w¿åÀ*PnİİalÖ¶Dæ\\’#\0ZéaĞ¨ò@½İr¶êğ[‚\0DuR¢¡D-.aH¨!É\0STXëÊf}„\r¨% ĞŒ£ç&…”¨„·@\$rq3T¾ÏÕ«†‹”Çàl_•Ã)¯ŒÍ&dÁ]„Õ¦!\$˜YsÛu¾³FÀ«UÕQ˜€«I¦ÍQ+ßŒ·ˆ Pd";break;case"bg":$g="ĞP´\r›EÑ@4°!Awh Z(&‚Ô~\n‹†faÌĞNÅ`Ñ‚şDˆ…4ĞÕü\"Ğ]4\r;Ae2”­a°µ€¢„œ.aÂèúrpº’@×“ˆ|.W.X4òå«FPµ”Ìâ“Ø\$ªhRàsÉÜÊ}@¨Ğ—pÙĞ”æB¢4”sE²Î¢7fŠ&EŠ, Ói•X\nFC1 Ôl7còØMEo)_G×ÒèÎ_<‡GÓ­}†Íœ,kë†ŠqPX”}F³+9¤¬7i†£Zè´šiíQ¡³_a·–—ZŠË*¨n^¹ÉÕS¦Ü9¾ÿ£YŸVÚ¨~³]ĞX\\Ró‰6±õÔ}±jâ}	¬lê4v±ø=ˆè†3	´\0ù@D|ÜÂ¤‰³[€’ª’^]#ğs.Õ3d\0*ÃXÜ7ãp@2CŞ9(‚ Â:#Â9Œ¡\0È7Œ£˜Aˆèê8\\z8Fc˜ïŒ‹ŠŒä—m XúÂÉ4™;¦rÔ'HS†˜¹2Ë6A>éÂ¦”6Ëÿ5	êÜ¸®kJ¾®&êªj½\"Kºüª°Ùß9‰{.äÎ-Ê^ä:‰*U?Š+*>SÁ3z>J&SKêŸ&©›ŞhR‰»’Ö&³:ŠãÉ’>I¬J–ªLãHÑ,«Êã¥/µ\r/¸ÍSYF.°Rc[?ILÎØ6M¢)£äıVÑ5Ô°ĞšRf„ÊeƒrhªñiĞÊW4²¬&+Å’Ø¯«\\´A#\"Õ-(˜İÔUâ­£ïÒ?	„ˆZwøj”Ká\0’+@Á\"M*EV\nCàè¤ÊïbM‹ór¢´šÁ+Ï)º‰YNJbËBXèŠ£Ò6#äò'‚,}“äé¤ÌC2Š ¾§è–R*ZWE*ª¼ Ë²”­x§×êùÁN}ŞÒ[4ü’çú{^a\ní¬hR8th(ÄæÎ€” P¶®³Ûˆº´ÅæèÙÛvŠ½“Êš¾²VõD\"\r#œb6F«pAÄãwÄÇ\\g2 Ê7cHßÌ.(¨¾? PØ:TFŸO2ò7ÊÈW;ƒ;k¼=ÁöË“¼“î6ø‡á)R¥»Löà\$pRM¤Ö¨k’ùâÎ®´ÁvÎ<¯yŠ±¢?}EĞã„L±¤jŞ£h÷Jfw£ø\n÷„¼ø´7‘3ùT5w:Èo¡mz~]ëQÂ`öãÜ{Å‰-v,\\PÑ«BÌ`Ô4rôAŞ1'3*€ÂˆQĞfqÕÌ†àÎd\r¡¤7\"„ÜÛ™ˆ ÀÂ@r¡˜‚ Ğ p`è‚ğïÁpa„P‘äVÁ{—èİÇG4x\"Ñ1Â@ÎxaÎt¿ !¥ù*jğ˜ñ`İù^I¢=é“xÎú_@ªx¸ªˆÌa	ä`\$ËN5<òDœËayAjÌÙ“À]a|1†pÖÃ˜waøwˆ1¢Èbƒ”rÎb'ÅÜ¢\$Gá,Eˆ´÷QÑ¦UáçÃôO„ye>GM‘=un^Ö9Ã!rd­£B\râ¥g%nY=¤ğKÏ²ˆ8f¨îÙ^tN™S”…\\¬Á@ĞCc‰Îlv^\0bF¡ÁA äC*î!™Ë\$|Ã9a˜:Î\0ØÃ<\$›¡¤:€A?Q«†\0€1Ã(V¢pa\rÌæ6­4J²Q)®B—#‰xVêĞZ;JwŸ™:\n (&-DS+½?À€àR^d	Y5lé¨\0 †æá,ÚŠÓü7‚\0àƒHveá”3Îš|‘Ó…Ga½Ğ©îçLÖEÖ¥Bğæ‘'ˆ ŸT­ààREHáÉwpĞCş„aÎkWaƒ•>•…d Ó1>L„ü—(*\"Hle\"8šHvôòÍáM\$OõF&¸eRÃ×uhtİ§¸ÖTQ¨;QĞ¢›DRŸ2g4]j%fˆ”åY\\K-6» )¨/l2K.±°Ÿ'´ãLÓ›×Md|H×‡4KK±í8—SÂ˜Ÿ¢•\\&ô®ZÂ¥\$¤&•¦¡OK\$xŒ¼É0NÑxÏ¬ƒ^»Mjç;·Ï^¾p+×ÒB·Ÿ4«KÍ âL[×.ßÚY wÁÛsÊÎ¯”¨¶·¨Æã¹_î\$®¢E€¤1dùgNJ¥À.éš.¥I­!!¯\nÙ–@ÂˆL,åĞµ“CŒ-Á\0F\n”•L÷ÖKÊZR˜Y®Ñ”À^İ“±*Å\nÀÔ/cªyé¬ÅŞ?È0Jù–8ÂrF>LÆÕÅ6°¶S»bÆ<ÀuÚ¹ò“	£Y\nˆ¥“¬ØLö]j/yF³\\§12´_ËMŒÓqÁDn6²útÉŠâ;ëš\\â„S¥Âizo×\nZ;†ª™İ!M˜Ó¬É–o¦sZN€€*…@ŒAÅ—É–¿2rˆĞÎ0´]ù1(c(mnæk]Ú¥­®Ö­2b¸.WWÚñè‰N´÷1¾±‰ŠšTUÿ<é¥›ÚQy.•2µv«i¬ã24‰„=ØÁC§‘pØJÁ?‘y´ÈúeÒªW ‹Z³[­Â¥{gšW\0à\røÒHR_vÿy2~\0Ó.	m…*¢U.ù¢¢…D7ãÍ´q’\n€&Í„³v!\0Eêa+NË&–gª7RãNråcn/Høš+ç&Ñrz*…JDC¬ÍÚjîf»ƒvM„à_nü	µÇ*'‘Ô°Í:3<ä}+JüÉ+é»ŞÍiÀ2#";break;case"bn":$g="àS)\nt]\0_ˆ 	XD)L¨„@Ğ4l5€ÁBQpÌÌ 9‚ \n¸ú\0‡€,¡ÈhªSEÀ0èb™a%‡. ÑH¶\0¬‡.bÓÅ2n‡‡DÒe*’D¦M¨ŠÉ,OJÃ°„v§˜©”Ñ…\$:IK“Êg5U4¡Lœ	Nd!u>Ï&¶ËÔöå„Òa\\­@'Jx¬ÉS¤Ñí4ĞP²D§±©êêzê¦.SÉõE<ùOS«éékbÊOÌafêhb\0§Bïğør¦ª)—öªå²QŒÁWğ²ëE‹{K§ÔPP~Í9\\§ël*‹_W	ãŞ7ôâÉ¼ê 4NÆQ¸Ş 8'cI°Êg2œÄO9Ôàd0<‡CA§ä:#Üº¸%3–©5Š!n€nJµmk”Åü©,qŸÁî«@á­‹œ(n+Lİ9ˆx£¡ÎkŠIÁĞ2ÁL\0I¡Î#VÜ¦ì#`¬æ¬‡B›Ä4Ã:Ğ ª,X‘¶í2À§§Î,(_)ìã7*¬\n£pÖóãp@2CŞ9.¢#ó\0Œ#›È2\rï‹Ê7‰ì8Móèá:c¼Ş2@LŠÚ ÜS6Ê\\4ÙGÊ‚\0Û/n:&Ú.Ht½·Ä¼/­”0˜¸2î´”ÉTgPEtÌ¥LÕ,L5HÁ§­ÄLŒ¶G«ãjß%±ŒÒR±t¹ºÈÁ-IÔ04=XK¶\$Gf·Jzº·R\$a`(„ªçÙ+b0ÖÊzÊ5qLâ/\n¼SÒ5\"ˆPœ«„–Ä(]x€WË}ÁYT¶ºğW5eäŞµ}*P©ñ9/Vu*Rª·¤€‡bX¥µ«Ôe¢İ”Ğ^5hÖÙŒ”õ¬O!.[8¶ä\nĞ@åÈ<¾ SÌ°\\šbÑ¶r‰8§ÈŠE(é­xºÙmÆ+Ä¼+¤^,@'nE)\\İtW¯Zù\$zÂ·+Â/\$D¶¬\$8Z¯­Ëqd§ã“ZCÚ·FLOØÑNC	Y…şâ”da™!ËsAº­AB‰ä19~Ç+gâë*\r¿Y(Õ¡I˜ækÉMÇÕ‰W\$Sr—jÌ_œF¦˜sÅ6ÄÛô.ÚŸG4’@\$Bhš\nb˜:Ãh\\-^hÔ.©èM¼ËìMÑ³æ›TmGÇºĞˆÿ@/râùMóXİğ¼”ïËNŒ£Ãô7cHßøQ>’ñËTÒàØ:@SºwÃr<¼3`Ø“*‰*+ÙJ¥•HÄ\\™\0%‚™€¸\0¨Ï8m!¸<‚\0êü¨cgÄ9†`ê\0l\rá&0X|Ã”!œ0¤ÀA\rœ`mIÔı€æ\nTKĞN\n­5*q\né…:Š‰†;ƒc¡áLÇÈ3>PêüRh !’äÙÃó~!‘6\0xOÀô€è€s@¼‡xìƒ\\	Á7†p^Cp/Oœ:?)ôƒ?)03ƒÀ^Aô\n6êm¬v\nê²*D\$8‰«!ní]» (ğ¾	=”fÇ ‹¦‰oqvÓS©úG¡B(`äË_hp\r0	DFhĞ#TlÑÂ9GHìãÄz‹Ñğ9Géßt€~/Ì‚\"ä‚Hm¸6È\0é#¤ƒãtşöZ}'a\rg4¨\$Ù¢ğnyX·)¹VTW;Øzol¨¸DÉ5bø•³I:—Í”P\r0†9\nüÁ\0w=° 1@à›\"¨r›¬´0†idŸ¡\$„Ğ¢BÄÕDáó¡d€0ÍĞ@åäŞ\r!„65tÚŸÅELâ•%ÙJ;GDÙ@ ‚ÓCŒ.‚Ê£Zú§ªhÀ‚äì{Š‰ªIPeèõÒù)Á\rùÅê#™ç='¬öğÊËT\0r‡Èÿ'´ûM!Pw­åÖ(¦ÉØ|£@sPp¦£ù;pp‡’Ğ9¨UDOèc\rŒ4†xÜ(­*<Œ0ÅxSÊÚ¡â «\0†ÂFXj-½Êêª˜q…ÔhªG2ì‘}b´õšÂˆ™%¯,ì‘+RgT	±ÕŸM¤«„„@E«‚šĞ½&I?]³TË‡9BÜîkDl…Ê6ÄfS!s‰â\0dL)W7T\nV¢3Høy;À€2–Zy“êv\rÓœşŸ(C©ñO¡™7Øµ2£-‡MA'jU]TğQ+!(”h¾0¼@'…0¨C±xa¥7ÅësÔR#¤Ø_D2@-\rMm7±G”«‡*`dõ¨\$b}êË¯qÆR·ÈìJÄ®ÿé|ƒÓl7ÇA•L(„À@«h =‘ #J“Ùhi›Jà¼HaãN\rõ’7öhÊL1!É@€;’Ì•Q ¦(LjQ:,è@3³((„=JZ²)—Â²÷Òéè:¦¬İÓ4ÎµlÜç”gÁg8Á\"*É­sÚïÄòœã¹\rÓZ#N+§ÜÊ•5p;YW¥İÈ\noğ6¼(CkB4ji‚kLãœÁ¤3AÙÚ]B6a\r±]>Ù˜lB F áóFú“K®°RÉK-‚êõ§g5aÇ/Å>´ÛĞ:Z':'H°ïš^×ÏxıæãZé˜,;ãJPH™«3î®)ú\\ÜÜG¬Ù¯QÅmz¡Wç–u*‹% À(&í=«Q5Ó]Ğmx£>ìgÒâfåx^ó²îÉ>{jvÜë³Iôô–Ü§W÷êó8¿X4¨]epä‹¤˜V6U8&eVZSzwN`+ªÇ™ÂrK&0®meîÛ–D¸·åÜÓQ[ŸÕil,íßu@¾:/÷ÿîñœ¤Ê{¨uoå+>qŒP/\níÊAº}íÂÊ#€Ö*ËŠ—®>²Jê\\LÛùUZÕ{º1&«»x¢ŠˆªR";break;case"bs":$g="D0ˆ\r†‘Ìèe‚šLçS‘¸Ò?	EÃ34S6MÆ¨AÂt7ÁÍpˆtp@u9œ¦Ãx¸N0šÆV\"d7Æódpİ™ÀØˆÓLüAH¡a)Ì….€RL¦¸	ºp7Áæ£L¸X\nFC1 Ôl7AG‘„ôn7‚ç(UÂlŒ§¡ĞÂb•˜eÄ“Ñ´Ó>4‚Š¦Ó)Òy½ˆFYÁÛ\n,›Î¢A†f ¸-†“±¤Øe3™NwÓ|œáH„\r]øÅ§—Ì43®XÕİ£w³ÏA!“D‰–6eào7ÜY>9‚àqÃ\$ÑĞİiMÆpVÅtb¨q\$«Ù¤Ö\n%Üö‡LITÜk¸ÍÂ)Èä¹ªúş0hèŞÕ4	\n\n:\nÀä:4P æ;®c\"\\&§ƒHÚ\ro’4 á¸xÈĞ@‹ó,ª\nl©E‰šjÑ+)¸—\nŠšøCÈr†5 ¢°Ò¯/û~¨°Ú;.ˆã¼®Èjâ&²f)|0B8Ê7±ƒ¤›,	Ã+-+;Ğ2t©pªÉ˜„‹HàÇ‹ ç°ë‹'‰‰ÊŠªB„¾ËB¢Ú5(ÍÔÏL{,‚ÅSàKŒIˆèÑá\"5/Ô¥!IQRÂ<*ÀPH…Á gR†)tüÆ­£<°14ÍhÎ2#“êææ&2ÊÚ‡rÀ‚5Œl<°Â/ïë\"thô¯ÀP2%ğ\"X;O«¢X=1\"€Şô®êâ#ÁÃ¬X•ÙJÅ˜¥ªêÊ2¿ÔÔh9\\HEÉe”1æÍ«Ò?4İ×\rÇdŞ‘\nM|İih9Œ6€\$Bhš\nb˜2BÃhÚcÎ4<‹¬õb®‘¢‘B#h¿´ñkV¹¿‰ûn„)±(å•?ƒÂ7cHßœ%Âcæè\r’ó0Í'ŒÃ2«7/êf9YÕ¥@íhÚ)>p:ŒcT9ŒÃ¨Ø\rã:Š9…‹èå¬Œ5rŠâ¿t«7¨8P9…3RR2½*4MC²ÑÃ:ûµc6X”(£8@ Œ››û´ŒyŞrîæaâ42c0z\r è8aĞ^ı(]„ñÃ\\¹Œá{œ§	ûé…á}Ø£üHxŒ!ò\\+6â\nÕXQŸhÎÓC97µÒ¸'p¼RşÂ#œ'\næ£€ÒÉÂÁ,Îs<ß;Ïô=J;ôüjŠşõc—[×Œ¹³œöš	#hàÓ±ƒpèîİë4K¡¡À@ÕÉÅB¨95r¬ÿ¹s_h”6¶PêMQ+9ÖfØûÃg\$qÁ€Âİ™ûæ™±˜D\\4\r23vf½Úë_l-Œ–6hTˆA}\r!„À@ŞáÎ>…Ü9’âô_	ÁH)ğ's(‰ ,ƒ©\\‡D GDÈ	TAa%Á\r•hPâ`1£4¦œÔ®æ\rY´A(@»’Àîe!+GD”Âw~Í”‡\rÕê½pä‰Ã¹³qÃw:¥ŒCˆá„”&’^˜¾;Ä!…0¤â&YIùuE`ÈˆzŠ5°Ä6‡UTdÚ¹é9ÏÅ6rÕÃaRÉ1&rzsƒLA+ôÁ0@ĞÙ½;ÊI˜DÓÊECËD•èœ¸!\n‘9²FL8‡ST„1ÿCn1ÔÄ)vÉbˆQè86Ù<ªÊ	î @'…0¨È£\"ÊCµ‘âŠâ!ƒqâ4hŒ‰²˜!	2W\r*Cpf!D2³¢¬QŞÙS>†}436¯OÚ%Œ%¢©b˜Q	€‚2“J@B0TŒ\$i¡¤„4O94ór£H	&\r!èó²rQÌò,©F+úEêC4\$Ò]Uu*rµR„ÉX†=²4`—u`+İ¢ÖBè®×å_Q¤“JÊ®Ø0CKÁ°¼“ÜŠ;”YB–ut¶AÓ‰kEáso\n¡P#ĞpË\\Œ¯<d¹J½Éy4“ n]¶v+ ²NLí\rh´†V¤‹A(‹.L0rRÙ\0eCfØ•‡SÊaIñĞ\róÔ¨.wÚ16ª˜“âœ¥/p‚ÛÕB|fÔUI¦RÂÓPÑˆCa©mŠ\0_“àU¼¨0†Ú°®ÒÃ¢ª4¶d®›A#ë~üEÜ÷\rÿ2Švß—úğÉiUf7æ‡£ƒVª¬”0j1‡Hfíájêb‘°€5»ĞÒ\0";break;case"ca":$g="E9j˜€æe3NCğP”\\33AD“iÀŞs9šLFÃ(€Âd5MÇC	È@e6Æ“¡àÊr‰†´Òdš`gƒI¶hp—›L§9¡’Q*–K¤Ì5LŒ œÈS,¦W-—ˆ\rÆù<òe4&\"ÀPÀb2£a¸àr\n1e€£yÈÒg4›Œ&ÀQ:¸h4ˆ\rC„à ’M†¡’Xa‰› ç+âûÀàÄ\\>RñÊLK&ó®ÂvÖÄ±ØÓ3ĞñÃ©Âpt0Y\$lË1\"Pò ƒ„ådøé\$ŒÄš`o9>UÃ^yÅ==äÎ\n)ínÔ+OoŸŠ§M|°õ*›u³¹ºNr9]xé†ƒ{d­ˆ3j‹P(àÿcºê2&\"›: £ƒ:…\0êö\rrh‘(¨ê8‚Œ£ÃpÉ\r#{\$¨j¢¬¤«#Ri*úÂ˜ˆhû´¡B Ò8BDÂƒªJ4²ãhÄÊn{øè°K« !/28,\$£Ã #Œ¯@Ê:.Ì€†Ê(ÃpÆ4¯h*ò; pêB+˜ºÉ0ˆ9ÈË°!ÅSüí,€’7\r3:hÅ Ê2a–o4ÑZ‚0ÎĞèË´©@Ê¡9Á(ÈCËpÓÕE1â¶¨^uxc=¥ì(š20ØƒÃzR6\rƒxÆ	ã”ÊÈ	†Z›RâÇ3Ñ”r9gÅ+Ôöº²”Í§0e•	a¸ P‚Œ¨«qq\$	ÏI¢(Ü×2˜NÍ;W„RŒvÀËm£¹oP¡py0oµë4^ôıòü_q%¶9[¶üà’²@	¢ht)Š`PÈ2ãhÚ‹cL0‹¶•ûu¯Pu&ˆ‘¨ÂßEê“¯Y¢£›\$ÙÜK£7bb&Cªüš6L\0SFÒ¤¨èŞ3Ğí+¥ít99êò7R·ó *\rêâ|70Ó\$:ÙôPÌÅ\nğÉC3Ê0Œã\nòã>Õ*l7Cc(P9….µR©å#…²šòb*`£Z\n3fğ£¼#&ö—Ã:Ú2£\$Áâh42ƒ0z\r è8aĞ^ıh\\òé‚ê3…îĞ_\0¯OdT„A÷pş;Áà^0‡Úhƒ£fÓ+£€Ò9?›Šë–º6üĞ9´®ÌÆBküÜ\$ĞZ1XŞo2Â<Át=KÓõ=_Z;õü²ò—…İŸj7v±3´Ğ_à\"* øŠ!e6¥Äf¥İõ>a\rdt–) äØÔ²8/`\\‘Ò†{I(\\aŠ‚\nºBQ/%¡#ĞĞGC\nşZ €;šòRˆì#r]ôàÌø3i#\r­_†öß\r	`h1¼ƒ„fÓ\"–M\nî\rv~ÿŞQ—0FdÂhTˆÊë@Z¤(€ A\$Mà€R€©P†ù8Eú{pg6f¬ÖšòcJj ¨,\0ŞßC‹…æPãW‚Ã>ÆêI\$ƒ\"Ÿæ\0001Ä¢lİ* 710Â¡C~C|>Á)… ŒŞ` MÀ¸6\0äõÂğ\$Å†Ó‘ĞŠó&ˆ1_ARVÒTŒ'B€ØTaHª-\n1™E8Ia1	\$D<šHà§ä!\rĞ<İ‚)H	*?39Ê»–vŒÚÁ?æéš¶é)Âa²'¬(ğ¦2Œ>ÄøŒ@Îô”´\nO :œ¨Á>Í‘\\/3V’5Q_<2È„Í¢¢VB^‚ĞyeDÑMã\n}ªÁ#äyPp@ÂˆL)p/\0@‚¤gRŠ|Æ“ŒC4ëQ!ÈÔÁ0£Aã‚°¡MMy‚f¡ˆtWHyÔÂ9Sª9š:d¼’UPOSTµ\$«\$æ’VaXI'ªğeÌ*Yk9ü*\n~°4à‘\"\r0ÀVĞèc¥•ê¬ÂVCmpP¤”¬b£ñ5Bˆ7€@B F áœ/“jyÑ\"¥Ö®×9ÌBª`7õµ‚œkF‚k’¥3Ö¥&M«FòY¡Zl°’¶ÒhbL[İ®å5£´™T½1l±&¾ƒzâmµ|7,\0Â0Å:v¹ÔŠ[aI0aÂŸ¢dé]‰‘†İd^‹>wÂ‹ZÄá†=	PqI†*®¤Ûej\r\0eTŠ~ÙàšÔÒş}	í`­`Š#J‚µä‘Z#\nË£\\ğŒ@Ã/ÀUDiÄæZ\nä€";break;case"cs":$g="O8Œ'c!Ô~\n‹†faÌN2œ\ræC2i6á¦Q¸Âh90Ô'Hi¼êb7œ…À¢i„ği6È†æ´A;Í†Y¢„@v2›\r&³yÎHs“JGQª8%9¥e:L¦:e2ËèÇZt¬@\nFC1 Ôl7APèÉ4TÚØªùÍ¾j\nb¯dWeH€èa1M†³Ì¬«šN€¢´eŠ¾Å^/Jà‚-{ÂJâpßlPÌDÜÒle2bçcèu:F¯ø×\rÈbÊ»ŒP€Ã77šàLDn¯[?j1F¤»7ã÷»ó¶òI61T7r©¬Ù{‘FÁE3i„õ­¼Ç“^0òbbâ©îp@c4{Ì2²&·\0¶£ƒr\"‰¢JZœ\r(æŒ¥b€ä¢¦£k€:ºCPè)Ëz˜=\n Ü1µc(Ö*\nšª99*Ó^®¯ÀÊ:4ƒĞÆ2¹î“Yƒ˜Öa¯£ ò8 QˆF&°XÂ?­|\$ß¸ƒ\n!\r)èäÓ<i©ŠRB8Ê7±xä4Æˆ€à65«‚n‹\r#DÈí8ÃjeÒ)­Kb9F¬„n BDŒB„Ñ5\$C›z9Æ‚ äÖ;ëèáA»å´.âsVğ¢MÀ×#„£ @10°NÓõ}QÓÕ,¹CÜ7PÁpHVÁ‹İ55@Ö2DIĞÒ;<c*,0ÎÀP˜˜2\"×½Ã€æô¡kÊŒB}€9¦³\$q@¢Í@ÚÒ1t3nïÍ³mÛÀP„<'?¤CtIO¶°ÂŒÀMŞ67•Ùzà5%ókàíş^p]ê`×Ò0¹¥p¹ˆGÏ¢@t&‰¡Ğ¦)C \\6…ÂØå“Bí£iºïğ-4ÛÄƒš®”8	ÈÙš+0¸Ü=)ù´œD¶+è0²w<	J3<3Ê©Œ´^«uĞ…\r\rr²åj„ím^4#HÖ:kå3¢úÍ&Ó\0õ³éËò4ú5pÛR\r²‹`Û75º4ìÓ²¸;FÕ²Ò	Şšî;Ñš6;•^;oW¾Ã¿G×ï¯p‰÷\r²8§¶M4Vò;§)»òüÎùÎ%vÖÜPÒ‰²7ŒM\$ÔÎ.ÊlïâjÂnMú%\n73x‚2o°—¸‡ú@2ŒÁèD4ƒ¥¤áxïñ…ÖG£ª…ÉHÎŒc˜^2\rèğè4ş!xDQè¸æ84°ÊxaÄtê1zNCSTjø“ô~”šdn/åÔ‘S:FÉº[‚µ	7‡0ƒVò¯(È!2\"jéJFÄŸ°@õP@r{iî=àæøä|ÉEô>§Øû™é<¹û\0|Cjc©t7\"´/\0 aEäL¦â`\nc5¤`bd`TCƒ)pÒäÌ[BoP’‚t¥I+YÈÍO‡UÊÀ#Ç8§öiÑpBPQÛ»˜0ÓƒQ4­õÔ&¨,›Pˆi©Ì4’²ò^Ó2Gi+	—rCä3ğ+aÁ3ù0‘ÀˆãH£ğ€H\n\0‚MIÁZ¸M0 @ ÔĞDƒR—2å†F(g•°A’è\\Ğ’—1¸ªôƒÃBA´¹˜ò9Åètn3x¦‘Ç‚yÈ¤‚A	ü%Ö€@ŒÑ„0»²xS\nA´±…	;f˜”Æ‡T&\\³y%kı¶\"\$q'¹å%æp™¦ù¨¥éÀ¢´Yúƒ°y;LÑİÁ´\$ÑBƒ‰SÁ©º³ç*DŒzÈ hˆ«‘\$ŒP¥KÏ|ä	¸—ôFkÜ©F\$3\r³¼ì¸P¼¾\\î°xB€O\naPBÿGâb“f•Xß´6ïHil…¼7šô:üC0iáÔáˆÙrjak~t…š’°¬PQİ\naD&RNU¦à¯›ÌĞEGhoMÄØ#IfoÉ‚6(İ7r8¢±KA¤èè‹å6§×¿g”=/Sö lá³áŠĞ›êäC	BSYµ4J… L„üÜËOC\rYB4Q•ÍZQBÑÙ\"ş^à¬3FiX*¸× ÂÜ¢2¼­ÎŒ¬BéÒ=`IXCÈ6³¼Pî}„yTtVœBˆÜk“U•á³@ô¸Í)ŠDÕıFˆ6§ãaÈGbÍøeê›Â¨TÀ´Pµà,ŒÎ¶—ï4†rğ„IÂXaİå…ZNºøD9Y6….=ßb’€‰DÑuÔD+e…øÀ&ÎËïÀn2*\"QÜó‹úê¦ ;–G/¼e4…ù9ZÆ«ÑòB¬Ğ‰©´9ÈE†hÔ6ZvNÙ6«„?.’,8x‚ã^ªlµ”Rv]…h\nez`az‚ÍB\"!\nyá¬\0Öª²çxç@gĞÖ«{G¢À²2åÖ x²@*\$i0¬UåVª§øä<lHÀ\n\n˜ÔÁ¤qŠ&Â¬¥9&üšä~";break;case"da":$g="E9‡QÌÒk5™NCğP”\\33AAD³©¸ÜeAá\"©ÀØo0™#cI°\\\n&˜MpciÔÚ :IM’¤Js:0×#‘”ØsŒB„S™\nNF’™MÂ,¬Ó8…P£FY8€0Œ†cA¨Øn8‚†óh(Şr4™Í&ã	°I7éS	Š|l…IÊFS%¦o7l51Ór¥œ°‹È(‰6˜n7ˆôé13š/”)‰°@a:0˜ì\n•º]—ƒtœe²ëåæó8€Íg:`ğ¢	íöåh¸‚¶B\r¤gºĞ›°•ÀÛ)Ş0Å3Ëh\n!¦pQTÜk7Îô¸WXå'\"h.¦Şe9ˆ<:œtá¸=‡3½œÈ“».Ø@;)CbÒœ)ŠXÂˆ¤bDŸ¡MBˆ£©*ZHÀ¾	8¦:'«ˆÊì;Møè<øœ—9ã“Ğ\rî#j˜ŒÂÖÂEBpÊ:Ñ Öæ¬‘ºĞîŒãÈØß#ûjµŒ\"<<crÖß¯Kâüà;~¶Ãr&7¤Oğ&8²@(!LŠ.7422Ö	ÃËBØ\"èl’1MÃ(Îs¨æ\rC‹@PHá hˆ)§NàĞ;,ãšÈÍ'î›p¿‰ƒHÚ4°ÂC\$2@Î¬Â\ràŠ²)(#Sğ©N'ÀP¬¾\r Ìé©QŒğ¦U,ò€Àtï\$¶\nn‚×6ş:B@	¢ht)Š`PÈ¥âÙ†RÛvéJ.ÖU#j2=Lrº¯\nâ³Á7B0¢8ˆ\rà2	¨Üƒ\r÷ºOLªPÙBÈŞ‚-(Ş3Õ\n©E¡P\$U06£¬b*\rñlÌ<£—¸ê1Œo€æ3¬ôÕ{…‰€åŒ#=Œ¥­j“MÃªjaMöâÁpî<±–P¨42I[mv¨2â#&`…d£òƒ\$Hx0„Bz3¡ĞË˜t…ã¾Ä\$:[Î³Œáz”Œ—Ë@4ß!xDm«bb¸‡xÂCXöá3©ˆ\$6'K³B„†t7@Ã<\$#,BN”ß¡h×.¯ôoÀ£•…Á§ªêúÎ¶:kºşÃ±ì«Zó´mCvÕz©WÇd(ğ’6£Í‚”š[Î÷„zÆ‘hw“âÛ -\0@3§M*DĞÄ£”Íßï£Ÿl‚¾ÏÀæ5­°hŒ|!KÍ³©>~Œ#c|„ºd.ÏÊæ9òLà{˜æ<È´e¯È:‚`fˆ(a9à€1›×~Khs\$åÀÄ9ĞìPy@0åÍœ¼‚*ÔIÁ:2æl\0k‚ğd¡‚ˆ\n\\áz™8Ö×É}É<˜\"Óª`¹x.I\$û‡#rFO‰ó3æ¬ñÇÔf)O3äı1ÓÈÁkÈüŸ³ú’Hw\r¤1Àe6fMõ€¤`1†ŠÂÂªAHe¤Ğ„0¦‚0-,d\$¥¸ƒ^“:¹y'<Ö’‚TK	qŸM„3àè–\r@oaÏ~8sD.\rªqˆĞ¡2ŞDCË P`ùóé  ,R'¡Ä:ŸB±/i-š,°ÆGÏl©>'è¹99	BxS\n„õ9É\$B¤¡›X €½6:@Ci£ä‰È¡w(J4‚“’vOPƒ,]NY|’@ÎUkïLç ‚>–GHù!(%,œ.À¦B`-A¨\0Œ‚.#.ñK½\"ç,¡@L8¥pŠHºo /•“U•CèŒÆ\\¨Ñz WyÉ “«´àF‚Û‚áÈ„¬†¯¹¥\"–•ÒÒNĞàla­³ò¶Ş\"9Ñ€4¼“>hMd Ò¤Â\nøõBŠ €*…@Œ#Ğeç}.\$úJ’LP¦1qMDW€è)}a1„©PHGI8K2Df±Ô%JF&Áª5€(aˆcH‡Ã1mš„ˆ²g:‹ù½JhX£~U\r>y•ÑCJ”F&¡#H¾Á3á1ñE6ar—=hN„Cí@æ‚|F	éõ_æ§#xbÈMi·ü+ĞÎa‚YKA•Öbß[‹›“]ªTZó6‘PT.ªÄ)O\$U_kù0D”";break;case"de":$g="S4›Œ‚”@s4˜ÍSü%ÌĞpQ ß\n6L†Sp€ìo‘'C)¤@f2š\r†s)Î0a–…À¢i„ği6˜M‚ddêb’\$RCIœäÃ[0ÓğcIÌè œÈS:–y7§a”ót\$Ğt™ˆCˆÈf4†ãÈ(Øe†‰ç*,t\n%ÉMĞb¡„Äe6[æ@¢”Âr¿šd†àQfa¯&7‹Ôªn9°Ô‡CÑ–g/ÑÁ¯* )aRA`€êm+G;æ=DYĞë:¦ÖQÌùÂK\n†c\n|j÷']ä²C‚ÿ‡ÄâÁ\\¾<,å:ô\rÙ¨U;IzÈd£¾g#‡7%ÿ_,äaäa#‡\\ç„Î\n£pÖ7\rãº:†Cxäª\$kğÂÚ6#zZ@Šxæ:„§xæ;ÁC\"f!1J*£nªªÅ.2:¨ºÏÛ8âQZ®¦,…\$	˜´î£0èí0£søÎHØÌ€ÇKäZõ‹C\nTõ¨m{ÇìS€³C'¬ã¤9\r`P2ãlÂº°\0Ü3²#dr–İ5\rˆØZ\$Ã4œÇ)h\"¶C¤ıHÑœ¼(CÑ\0ä:B`Ş3  U9ÃğÚö»ÌdÀ:¡ŠFiô‡ bò’!-SU¥ PÔ0Kİ*ÁpHWA‹¶:¹bş6+CŠ¹I+ÛÂ¨Èãs7Bz4¤­F³ª+H±‚(Zš#`+Z¬(èáŒÃ5¢7\rÔß6ˆ#\\4!-3WõÀ¯Éeçz¬j}7æİŒWò€&cTÂ=R@	¢ht)Š`RÀ6…ÂØÕBí¬Å·c¬>ÍJl`ˆ¦¯Ëz¡ƒÁIàİ– Ñ†c£Ã„\$šf&G\\å/4C 7İ6\"öÏ¡ƒ²ãxÏÛ-7C²µ—!“òV6\rğ…%J\rÁh¬†Bî@\\D’8A62Ò4š¨ bjş Ğ\" )ÈØ:¢=« ÚÈß­ë¨6¾€ì[%€…ìûJƒ¶\rûs†î8>ç|nÔKI½\räî&zƒ\0002?Éàå­Yªô­hÉ ŠÔê2\r¨\rB˜Èê{D¿¦A\0x0´7@z+ã à9‡Ax^;úr5Ü¯p\\3…èß²\$v Ü„Aó¸4ƒ½Â6\rsLÔ7!à^0‡ÎÛgŒãŸgP:·ë\0000©@ÜkV3juEìş´îéÑ¡\"`äÔó Z¥ÔÊá„Â|ÿ›;Ãx¡”3<w’òŞkÏz/Mİ=`äöÓ9goyğ|Chp-ï­¶ğÜûŸƒµ ÆÉšÀ´Ây5©Y	œ`äNVQO7 €œ¬f€N¡N2/Ğ“™ª@P¡L–³Ã137ˆå«²^‰˜T!fì1¨:ùŒ\n1?DpÊ@Ãf:„`8ºƒ©;k€è˜§B#Z¥M@€1œ6¤IÓ>2é|‡#¤bˆaş4„§¥ˆVä™{6*„ğÅèdLã\$‹h\"Éc§&WyDÁ2œGQ¢|†!’÷Hg\n9—F¬[ÉaCJ=Í0t*nÌ\nñ¤ïÈÄ¦ÏÁL#²\"m\n‡TÂ aİz7·rÊür›’1şBfÂ˜RÀ€*õìsÓ€¼NEÈ¢Xví‚2&Ù²C’`)~_á{GlNI»~uDø dì’‘;E €#@ƒ«‰KÌº6§‚ä¡ëª]¢Ø…˜0®GÃ¶‡ˆA;cî\né«—t »ˆJñ#nøÑ¿òF\nÑJnÈÙh¹&	˜P	áL*Ó.AÈ	‰e7”¦Îj]@z8U.`QÊIKP´èOZ*Qß‡RŒÒø\$Œ	d%†pê}ãqŸvÁ¬¤å”èˆ5\\©D(„Ê¬¨É4F£P(.JˆÑ¤<\$\rÙØipl\"àr­ĞN¡ºÆ`Ìº¥3‡F¿‘º€`\nBó,R*kX’lHe]È¦ÙÚ•LŒ½­p³Ïl×ÀAQá¤Ó²ş¹ÚDA\rs†ÀVÖ%äØS±+ÑB®Ä’I2³-d#˜T*`Z\r,AGQœ€“5ño­]Á·’eÃUà/º×3&lÎÛv¥-1[t¬˜;Ñ™FuKêÃeÄ •0«0dÆ„|‡|P¢ˆLC \n5\$f7³®b/ˆHõ’º€€®ÚV»Ø¢L\"ŠšÁï-&a4”¢¥®ËRR6díÊòÜÃÉ\"f%¡„6BXŠèsU\n‰ªf•!šFkE“ÀîÊ’m»\n	n‡‹ûiÀT0ÌğÙ_œˆ&Gp‰€0@+8›{hf2ÑÊ`¼\0";break;case"el":$g="ÎJ³•ìô=ÎZˆ &rÍœ¿g¡Yè{=;	EÃ30€æ\ng\$YËH‹9zÎX³—Åˆ‚UƒJèfz2'g¢akx´¹c7CÂ!‘(º@¤‡Ë¥jØk9s˜¯åËVz8ŠUYzÖMI—Ó!ÕåÄU> P•ñT-N'”®DS™\nŒÎ¤T¦H}½k½-(KØTJ¬¬´×—4j0Àb2£a¸às ]`æ ª¬Şt„šÎ0ĞùsOj¶ùC;3TA]Òººòúa‡OĞrÉŸ»”¬ç4Õv—OÙáx­B¶-wJ`åÜëôÆ#§kµ°4L¾[_–Ö\"µh‡“²®åõ­-2_É¡Uk]Ã´±»¤u*»´ª\"MÙn?O3úÿ¢)Ú\\Ì®(R\nB«î¢„\\¥\n›hg6Ê£p™7kZ~A@ÙµğLµ«”&…¸.WBÊÙ®«ê\"@IµŠ¢¤1H˜@&tg:0¤ZŠ'ä1œâ™ÑÁvg‘Êƒ€ÎíCÑB¨Ü5Ãxî7(ä9\rã’Œ\"# Â1#˜ÊƒxÊ9„èè£€á2Îã„Ú9ó(È»”Û[Òy…J¨¢xİÂ‰[Ê‡»+ú ƒºé\\Œ´FOz®³¦\n²¼]&,Cvæ,ë¢î¼­âü¡°­[WBk¹4µF‰9~£™älD¹/²µ/!D(¤(²ÒH@K«­Câ•–êü=A²ƒPX¤¢J”°P¥HF[(eHĞBÜš˜;©\\tÔCÍê¡%%%ÚÂğ%Ò*d«7î´ƒ½2PÙBğËPÜØoD@gFuÓ¼—4È¤®˜ÈdÓ‡nµÍQ×ªûªKq8®\$ôŒ„—cn4˜c¤éœ¦;êòáI	œ–ŒŒ\0Ä<ƒ(æ¹¾r¼\ns8›(%È¡xHéAŒ	 –Ò^ ³Š²R^ª5êÖ¶Ğ¦±Ú¦ÔB—2“¹ĞÛ”]×ÅJEàPŠ£Ò6D1’@LenòAŒB	=¢•©oòŠ ¯†Şâç“é»\r”æQRíå¸]æ£/»òXç)yQíiz£'Œ&qRÄß[Ö:\$¢Ah–áz^^âÚ„¯w‚îÚìŞJa|õ;\nógï«€\"\r#œÖ6Më A2Ì#wŸèÎ§¤2ÈÜ9#¾»õÈ˜D`è9\$•Ø…Ä¿;¯Q§	³”‚äÉ>6¹÷ŒvBù_„YÃ‘ğÉØâÄ ­«¡7*íŞKè'Å|­uÈ_KQª+dR+\$G‚c/é”\$\",	sÇ;L‰S<CmEb!Í:6ò´ı\\¼jĞQà\$( S	<!tJ† •^rOšÎ*¶¸BTËÅ‚\nƒâfabÃ:ğ¼¯Ã—\r	rò†äV“öTÅƒçù<EÔŒb)((x£@ĞRÚtÏP:¾\0ÜÁ\0A´4†äÅ\nâ|‘1\0xA\0hA”3ĞD tÌğ^å\0.2B¦ \\™C8/¡¸§¬¬@úW&™\nÁà/ ø»‰xE^2ìB­õĞ!‚\n b×>ê%(KøMJ˜%Ñp“ÆR@\" z%Åé‰+åüD;¬Qoœù\$\$””’ÒbMIÉ=(¼¢”’3J‰U+ãŞ|ˆ‚\"èˆ`gJ!F¦%Ì»SÂÜ¥7ÒÊ|^<`)ªmzäv…ešD©•\$¡jEEÀ·TR[¢\rRgÅ\n88#C	<Œ²Lj!á\$XÉ@“GPĞ›Ãc–ˆvîäCo	Š=‡ ÚY¨aÌä9'€ÆÓ˜sÁÕÈ†ÀŞä-E\r!Ğ4\nÈ›ŞuOIÈÀİ,ljØÖ’µ§›êãC)[’ší0Éú&%<å8:AÉâşÅquP%A_¤d9j|\0\0(.€¦%p}‘S%%”ç)^å€PC|R¡KzÌÁ\0pA¤;7pÊê…±O‰Ñæ§PŞë^õBŸ&\$ßod€sOÕ`VÏsCpp©õ?¨äÍC¸h\r!²È@Ï&²o¹áŒ0ÇĞÊ]ÒR¤\"PqC:ÂJp aL)`\\É±IóIˆ¢hMˆ¡+;óPgMaœ)M[iÂÊ½Ó\nC l<Ğ›Ş®p’†Xä¼ˆÓæPŒÜ§¤¹B“ô‘2ÔQë˜Ôª¦a,\"Sè™Q²~{	ª/D¤Ø†½ñÉQ3ÎCóCFÌÌb5/Èõ“alšò-fü\n|W’ò†1ª8ZHxS\n—İA’u“	‰áTTÔª%VÀ`æ0&H@ÛbÂÄ‘P:¤o\$²_2È*èŸ1èÎVfŒ3^‹5³\" _\rQTï L;C¨ÊòÇf%X0¢(m¥' €#K12éÈH¬İÑ’Æñ,A>uÿ™˜Áeéneë]™g+DCMAˆIKD×ë’…®ÙŠJÎEyÁ,Òø# şÄ!—l\r’’Ş–¬jE²ÚŠñbJ-ÇÆ¼¹³]µ‰–Ê‡&Ì«n½¸¡òˆ07s³\rÒÊßèÎP¨SQM †úÃ`+a²í†¸d¦NÉáÏ„¿9a	‡I3Á„<T­_µ-&¦Y`ÄĞ`¿”“:Er¢¾:-	¤ÄÎÎ¶DE­ĞĞÀª0-3[&SÈ¦[9†ìômÄ„åÜo7¤6?Aí½¢‹)êáÙ”• ÚJ\\2q.¦U\ni£‰‡é1)AŠòFsÍË¦ƒôš÷%1œà3Ñ0€jO’!1ÑM7Fğg0ØMi”—ÎØêEönÔ”p6¦ß¹ºïÃÿÂ´•sÓ1÷ád¬WqLht§Dğ¬—i™˜wqƒŠËÍ¬zAØ´{¥‡³”,¤vÅùŒKìí¶ÆÛÊ–«°\$Tƒ”ÏgàgAíœã¹Uù¯Wsåïl­Ò ñ„3–›ÙŒ‹Nëq &üÖœ/²ÌÉPjV!eÅ)—|÷¸åF‚‰X:…ë'ßå\n4WòX8¤‰TÏ¯÷zb4|¦a\n";break;case"es":$g="Â_‘NgF„@s2™Î§#xü%ÌĞpQ8Ş 2œÄyÌÒb6D“lpät0œ£Á¤Æh4âàQY(6˜Xk¹¶\nx’EÌ’)tÂe	Nd)¤\nˆr—Ìbæè¹–2Í\0¡€Äd3\rFÃqÀän4›¡U@Q¼äi3ÚL&È­V®t2›„‰„ç4&›Ì†“1¤Ç)Lç(N\"-»ŞDËŒMçQ Âv‘U#vó±¦BgŒŞâçSÃx½Ì#WÉĞu”ë@­¾æR <ˆfóqÒÓ¸•prƒqß¼än£3t\"O¿B7›À(§Ÿ´™æ¦É%ËvIÁ›ç ¢©ÏU7ê‡{Ñ”å9Mšó	Šü‘9ÍJ¨: íbMğæ;­Ã\"h(-Á\0ÌÏ­Á`@:¡¸Ü0„\n@6/Ì‚ğê.#R¥)°ÊŠ©8â¬4«	 †0¨pØ*\r(â4¡°«Cœ\$É\\.9¹**a—CkìB0Ê—ÃĞ· P„óHÂ“”Ş¯PÊ:F[*½²*.<…è¸Ç4êˆ1ŒhÊ.´¸o¼”)®ZÜöH‰LŒ!¸“şÊ¢`Ş¸Îƒ|•8n(åA–2¨œ:Œª<´ÊÌxJ2ò¼4İ;O£ PóR­ j„XTšÃ\r&gD¹ÎjD„J¢xå±cÊîˆ3…k[L,Ä©ËLƒ+\"8®x‚2\rC­ª9¦£sJÄTC-œ–/6ûœT4åÄ0´ÈÕÊ4Æˆêh0Êtx\$	Ğš&‡B˜¦ƒ ^6¡x¶0áº\nÑƒpòMı.u¡?©@¥ŠóŒ¤Xä#*Ñ<â3¿CdÀÌ³ikŒÃ3È7+.óìÒ¹ÍØ†ûQéRVÔH¨7§£ÜàÛR\$àŒc0ê–\r”:Ò9ÂÓ‚0Œ÷bô´ãtàÚã¯@æ¦‚š3c¯Qêæ4â-s„âR ‰œnÏ>(ĞÍÃAkìyÀåQ¨ò†•àĞÆÁèD4ƒ à9‡Ax^;ótiÂ¾ÁrÜ3…ëÈ_\\µñ(Ü„A÷Pã-Aà^0‡ÛN×¢¸Q êÃµĞ´ƒ	¦NHæ1­ïô\náMCJ&Lü¼%PHçÁ¬€à4±pAÆ³¼‡%ÊrÜÇ5ÎüğÉĞ?=KÕä«ÎOÕ„JP|\$¤©”âö]¦6IC¤¦§È '!¬‘”}‰Ë88§è2†#\$FIA*3ÁˆÓ@dhˆQhn¨Œ“@¨yÆnÜÓÈFÏ³I)NÌô+OzmIª5bõ	ñ±Wa…%'²w€l3<ÏÚ‰)<+i!è2ˆŸt#A@\$Ğ<#@ ¥’A1ƒdp´r¬™ÀcvœÕ2biˆIÊ#e¸:¤`-\rT;œ¨=œwC‘Œ0§© p¨! Å~¹0%ğ¸9E:HŸñ\"0èaœ´x^‚S\nAêb\ny8 \nÊP6d8‹–òğ%MUšò:›‰d*\r­M?7 Š‰ƒnHÜœV* ?\$‘6MCÄu\n!\\b«È!Tğˆa¯ÜØ\"€H˜yf3\n<ôBi±æ08©B„ˆ`mpoœ´Ÿht^“Y“ÈµÆrxS\nˆtş&ârG—ˆ 1m(¡‚à@–ƒ uF!‚tOé-›gp‘LCc#Q1™Ì7R£ÃldĞÍÃ^cU™r\nFÀ¦Bb\0o‚\0Œ\"µ\$d–:4§‰gn„)t`¦IYåCk¦šC ‚0†x“Jœeƒ{jçØ”UEÂıá…ÉŞ®®’V¸«I­q©x™€†˜`+Mf+†&fId®j¯D”¢ã–JZ©4Ôî]ÎøNØ¨TÀ´€ÜpÌj&ŠˆÊUtçQºç­Öv°GkAYW‘u)ÌFj”HV1Ä7óÚ%S`Ã0y`e8«:9UQ)DËp6Õ£\naÍØU8Ç’CX-p^Q0t¥Ü •dÃ+ob*2AKR¡¦EÅff¸”\0«ÁfOP ª!*¤†\\PjªDeÔ\$á¿T¾¦”å¤S6«ZVulÀdV-U\r›¹O=§1	\\ŠŞWqz¶«p*1”«WÔÊÜ¶•7”~";break;case"et":$g="K0œÄóa”È 5šMÆC)°~\n‹†faÌF0šM†‘\ry9›&!¤Û\n2ˆIIÙ†µ“cf±p(ša5œæ3#t¤ÍœÎ§S‘Ö%9¦±ˆÔpË‚šN‡S\$ÔX\nFC1 Ôl7AGHñ Ò\n7œ&xTŒØ\n*LPÚ| ¨Ôê³jÂ\n)šNfS™Òÿ9àÍf\\U}:¤“RÉ¼ê 4NÒ“q¾Uj;FŒ¦| €é:œ/ÇIIÒÍÃ ³RœË7…Ãí°˜a¨Ã½a©˜±¶†t“áp­Æ÷Aßš¸'#<{ËĞ›Œà¢]§†îa½È	×ÀU7ó§sp€Êr9Zf¤CÆ)2ô¹Ó¤WR•Oèà€cºÒ½Š	êö±jx²¿©Ò2¡nóv)\nZ€Ş£~2§,X÷#j*D(Ò2<pÂß,…â<1E`Pœ:£Ô  Îâ†88#(ìç!jD0´`P„¶Œ#+%ãÖ	èéJAH#Œ£xÚñ‹Rş\"ÃÃZÒ9D£ƒ±ƒ\$¾½ŠH2pÓÉÈ\\ß\rÉ2Ï¾( &\rëb*Á0`P”à·²/¡¨dşÁ7èHä5¨‚*@HKK£#¢Î<°€S:°\\•8b	óRÎÉ\r,–0LFµB€Ò4KüPÒÊ4|ÉB(Z•¹B\\Œ†•ÈÊ™RK:n7(Ñëj’7)%dª!àÔ:‡PÂö7#ÈôX\$	Ğš&‡B˜¦*£h\\-7¨ò.Ééy6Ëñ¨‰H6IJOpÜ¹ °Â´áOpğÖ\rÉĞßˆ¦¢dE“Ê²òjR7ŒÃ26¶{7'P\\R\ròà7³Ãk<„Z„1Œl æ3£bñ@¡C˜XÓY Â3£/jq8¨SÌ2…˜RšÊc|6h²R—Éë\n£DìËÒØbŒ…á\0ƒ7%–†1âiÒ‚‡øÑ4ÁèD4ƒ à9‡Ax^;ğrI­ArÒ3…ğ ^üàôö&„A÷ úíà^0‡Ù+4æn&I<ªhpÙçëcIš­Ú81#*j›°¯zUK°ğ8Wˆ0\\n¬Æñ½o›÷Ápƒ¿\r¶'¼OÆÜn\nb^¨D¤ÂHÚ8 ²øÜ:s\\æ/µÃzÓ¸©ÂR©%O~dü|ö%‰Å‹4’ ‡O©Tl\$¤0†7\${C¹gÅÍø‡&Ê“{¤Æ±‚³vrbYã>k² Ği•‰)|4ĞCi&AÌš„R SËÑx#(P2ã\0É‘!%l%28iÕ” á@\$d„Ğ«¹0©P¤50Ø™=€Í Óààh\r¤ è5ã¤\r`;¼\"à¬SD^ Fü9 6si®}á¸8gvb]è å˜1ÁòNÛà ‘Ê†ªmz4fEL0¦‚3©AGµd)ãëÃ±i\r®ÁÙ;rpN™‘<%§–­§H’ ¦½<4êÏiB(Ñ£°(X‚I&XRL„}4cÃ1ñ“m­ÄB)	ZÁƒ˜pêZ“P Â˜T«ñY–e ]…LdÿI]\$Á+V\$.ZºB¿H9.&•4NÇ^ÀÃ€h\0´Á­ èe%àc1ŒÕğ÷øoÑ£i\naD&\0Ìh§L**DdÛú¶‹Çöd¥â+J>kæ­Ñj×MÛ`AUØÎÊJ×ñS–á-oVèÈ0u1sèJÒlKÖü¬[ŠIJ\"µÂ¦Ú¡Á:tÊos’Šdja\r€¬5“7f²ÃaK¢%ÀÆ–B F áƒ7Jv‰«Ş@•O4ÂÔ_‘9r!¤pÉºJPõ0¥\$X&¾ ÒƒÈ\n1m‰!%šÒêkGªö«RÖ©Å©Æ\n¨ ÓÌoìaÈ0LŒ„±Z¢ËIø>¥ı@FĞŸÃ*±\rr¨íšbuÂ¡ÁBdÍn7ÂµQb›¼Ã—HB[XbkœÖ²ÏZ9gU¤è„(;;ëÁ#v‹4¸—2Ş‘Xu/–4„ğÒB@";break;case"fa":$g="ÙB¶ğÂ™²†6Pí…›aTÛF6í„ø(J.™„0SeØSÄ›aQ\n’ª\$6ÔMa+XÄ!(A²„„¡¢Ètí^.§2•[\"S¶•-…\\J§ƒÒ)Cfh§›!(iª2o	D6›\n¾sRXÄ¨\0Sm`Û˜¬›k6ÚÑ¶µm­›kvÚá¶¹6Ò	¼C!ZáQ˜dJÉŠ°X¬‘+<NCiWÇQ»Mb\"´ÀÄí*Ì5o#™dìv\\¬Â%ZAôüö#—°g+­…¥>m±c‘ùƒ[—ŸPõvræsö\r¦ZUÍÄs³½/ÒêH´r–Âæ%†)˜NÆ“qŸGXU°+)6\r‡*«’<ª7\rcpŞ;Á\0Ê9Cxä ƒè0ŒCæ2„ Ş2a:#c¨à8APàá	c¼2+d\"ı„‚”™%e’_!ŒyÇ!m›‹*¹TÚ¤%BrÙ ©ò„9«jº²„­S&³%hiTå-%¢ªÇ,:É¤%È@¥5ÉQbü<Ì³^‡&	Ù\\ğªˆzĞÉë\" Ã7‰2”ç¡JŠ&Y¹é[Í¥MÄk•Ln¨ 3ûêX«nÌvî%©;C ú–ËÑl4îB:î›Ê“2sC'³I’ÌÈ1\nÀ”IÛôB¤¬i^Ÿ\"Ã#ÈÏ!ÀHK[>µÁTÀ¤ôáÀ¹®Ğ!hHÙA«ü²DB:…–3S£¨\nÓ@RÎ+úû Š;ú²ÕÌ	rë‰ÚC_¾ÓC £±¤¤§¦ó~XÆÑqR¦‹L¥=OjÂ[2l²_&Ë\ræ…\$ÅüÂ•«|¯²[\\†ª	ÄêŸ×Uã<€bÔÆ0âJÉû;Ñ°\$	Ğš&‡B˜¦cÎl<‹¡hÚ6…£ É-GÒMT%oí†\"\r#œ 6BŠA@Ãv—¦Ã:†œ2 Ê7cHß®E)İÿ,C ØØ6I)D«&êê&Fxä´µ2Ã1¶·ÂÂÒTk.Ï,ôC@£ÉK¸ÚFFÅÄlŠxşI±”<äe\"’JñRBŞËstæˆã›ìp®ğ.»‡Â\\3?Ä(É]Õ÷%ÊV|³D’¿\\Óy!óÄfé^Éœdˆ•I-Şâ•‰.Kº\nƒ@Ã\0C#6 :ë¯h@ ŒƒkÙ…\0Ç¯ë£\$ƒ@4C(Ì„C@è:˜t…ã¿ì>Ï·ÁC8^ÖÁzjAÑ¯à^ôAÇ°3ƒÀ^Añ[FéY…£EpQi·+‡è…¸ÖÚ@Ê»±,-É|4¸·Rrt	P‚8“dê+cL•Ê¤‰+zwm9¾GÌúSì}ÏÁù?GìßÃú\rÈ?×ÿšÃZk”0|Á×R8\$¬`©‘é˜rˆı6³€gZ!!UHÂ.(V¼S©\"*ƒ£BŒŠŒ!Êİ±bf§ØÊ_4D‰“<Ô(Ckà€;†Ø\0bBÁ½ äC*¶!™­\$:Ãa˜:É\0ØÃ9ì‘¤:€A+P£J“yô¾ İlIÁ>4nYŠŠˆSêI¤\\BÖVÅzB©/’Ã¤\n (#8âNã¡:q¬l”‚†\n^{Tîu¸’³c/Ê\0Ckñ.DÀÉ^Á\0pA¤õ´ÀÏ&gŠ!C-%\rô9.e8w“%C>A?0sDr†ƒ YaCC€uDH‘%lÃ@ir¹íwİ\$P¥a…êYÒÂFá­­ºUš´†ğŠÂX¶yI“(wî,¤”²šæúeh¤Ì¬º7\0A¹S6‰Y¹%æ7 ú,‡‘ ÂLãvoQÃt	\$h<†ğê…CJ¶Cdm­«i[?Hq¨ad^¼J|rÁ†9N„å}C({œ¢XæZfhÆÑ‡±™ÊZE‘-¦D:8£%`Z)(L@…Õ†Œ‘‹\$8Ç\\¦bITÎ1âFkèâ’yŠáôWZcFóxÚYÛ?E…Ù„”W‰ÀS\n!1Ö¨V)mw\$¦œ˜•çR‚¤Õ¦ªFÕˆ<\"cvØ§Bs0ï\në¿U¯JØãâ ^QjUŠºî¦#&âl,u›l” CË¶•ìã©ìHl^õ^“A!§÷…;ëyŒ…0§_5é!Ruj.Â§ƒgIƒŞÈ6ºíFƒZ–½Wˆ®/‚ñOÛP)*…@ŒAÂ·nq¾ÿk«m!EOc´Şâ0”/a#çó’u<Wq+HGq•ü6”•¦	;f}I•3ºLZ;>¤%œ•UÏÄxÊ¥“µUÏ&KËØ)İ¶âgE‹?E^-S«]	.bi›d„»£ÌºİTqRVŠÇH–DîmÅ0&\$š\nÔú(›É†O)64pi1i\$xÌ\\FR\rüO¢©0\n³8~“y%mó]tÁ¹ÁÔ&­X'iU–OYùˆéUM•”vKwil°‹";break;case"fi":$g="O6N†³x€ìa9L#ğP”\\33`¢¡¤Êd7œÎ†ó€ÊiƒÍ&Hé°Ã\$:GNaØÊl4›eğp(¦u:œ&è”²`t:DH´b4o‚Aùà”æBšÅbñ˜Üv?Kš…€¡€Äd3\rFÃqÀät<š\rL5 *Xk:œ§+dìÊnd“©°êj0ÍI§ZA¬Âa\r';e²ó K­jI©Nw}“G¤ø\r,Òk2h«©ØÓ@Æ©(vÃ¥²†a¾p1IõÜİˆ*mMÛqzaÇM¸C^ÂmÅÊv†Èî;¾˜cšã„å‡ƒòù¦èğP‘F±¸´ÀK¶u¶Ò¡ÔÜt2Â£sÍ1ÈĞe¨Å£xo}Zö:Œ©êL9Œ-»fôS\\5\réJv)ÁjL0M°ê5nKf©(ÈÚ–3ˆÂ9ÀŒæâ0`İ¼ïKPR2iâĞ<\r8'©å\n\r+Ò9·á\0ÂÏ±vÔ§Nâğ+Dè #Œ£zd:'L@¬4¾Ã*fÅ A\0×,0\rrä¨°jj\"Œ8ŞE°L_¦#Jl–Dp+Ç06 ê		cdÈæ<µÒøå0¨.âîÄ\n£¬2¡¿P25ã°ê„´¢SK1Xòæ1Èø¡pHÔÁŒø0Œ SÏËéc”&B;à€B”(Ü\$IİŒ•h¬–4îªl\n¤ä&-¼ŞÇ#KîÄ5´´:16jÇ¨•š5˜eµ’\r-0Ër5Ãe´ø(È]L[Å Ñp\\VUÍt5WUä KBj7=Sà	¢ht)Š`Pà8Î(\\-Øˆì.V~ê„	CğCRô]p£Mrœ×³iN=75¥µÂBp«wGB˜¦¢d\0 %ñ`Ù*Hº7ŒÃ2`îª\$B‚X—ho<ğ:ÏAúŒÚƒL\n\rªfL2b˜79/sE“C.’Ø7PÔDÇA§KFBR’ò³©Å‚ñjôÎ°Ë´Pzà¿(\r•ŞÅjÉæ²ş£ÚéI¯ì#vÆì­NÑ§¨.FØÓĞO^â¤îís¥;Í(äqÛêy¿ì|*ˆÎÒØr«´@İì#JjòMCŸ6÷dğQH:ÉÒ.cJÙ¸É€#£@ä2ŒÁèD4(€æáxïí…É?ˆ9ÓXÎ®áz0Û¨Š`^Ï€Ğ2]#\nÕåBî¬d¶máà^0‡É¨’Fr á¸ûöÄÛOj.|æ% Ü`‘ğ@µ 4ìfŠÂ.æ¤“F‰AÌ9è='¨³Ø{OqïgÀøŸ n|àøä2úNÁQšRşŸã1©L7¦’\$]Êe4Z%(õ Ã±lCØ?õFÊ\n_CåaMšC¦)”\"Šô¡àæaiİjïØŞ–ÀØh»»vh}°@—œ¢9;Äõ¬†“˜GW(-†MD¸è§Ú“Pty3’Z…\n	¦É6nXèP	A	v]!›+&8Á„BïWaë4È¶P²õ(yX/\0C?—ì˜\r=d‡&p´yŠ.ş \"~PL•!ä7ç’fäBƒ\\+±A\$Mº\$#¨„ƒhİ” ¶=ñQ+¥”¶’\0C\naH#Òx‘Ò(Àf/³Öi\n4%`ÀÒîíË¹@\"Äò\nÌr„]Éˆm.¡Î…Eòèj”ù§_«¸´FAÙ1y\$t§P”gÀ_K8õ°€zèh P31”¼‡ÓqŒá±\nš±IJPÊ–f«V5“‚t€O\naP±ª[(|ó\nÛæ4GY™†t`Ñ¢8Ì[nÁ¤3“’E8Ê!S(óhš…g•>	9Àrú\$0¢*e(äğ#@ ;°dÆø ÄW!Ë8P\r,—“^JOÌµ@+\$ä¯Õ«È¢‘±f–‰P\"wbJedgDÖK.yÃ{ïv¨AMY%Ï—xK´)¦\0õXlÍ§Ÿà(!¥@Ø\nÍ»ÖvT9Q¶°N\raC*!Â¨S)±ê™e`Ì…P©;AÄC0…¤7£\"Ğm]÷Yr¼cU_lõ7&ÄÿAÒ‚A–!Ê0fˆÄÂîgyD&ÁÖX pÌ)‡M®úßßÂ„‰Ú(5Ç\rÇÜ#ª£Ï\\÷ªÆÍZ:ğbàåO15¥Ğ—ƒ,¥€Q¦°ÎÊĞ¢‚ëHÜ4ÊaJ]sÅ9¢<î´¦_¤¯@S‘íkIÉ> kÍŠ6†Å\\İÕ¡KÎàrƒf•™¥wÏÑH@¸TGâA¢@Îlà2\nJC”Kt…æ¼÷¢ôŞ«×{/l;½Ü€\\ßr|o”•¢@îšÃ#ë‡G®7¸€ÿ@";break;case"fr":$g="ÃE§1iØŞu9ˆfS‘ĞÂi7\n¢‘\0ü%ÌÂ˜(’m8Îg3IˆØeæ™¾IÄcIŒĞi†DÃ‚i6L¦Ä°Ã22@æsY¼2:JeS™\ntL”M&Óƒ‚  ˆPs±†LeCˆÈf4†ãÈ(ìi¤‚¥Æ“<B\n LgSt¢gMæCLÒ7Øj“–?ƒ7Y3™ÔÙ:NŠĞxI¸Na;OB†'„™,f“¤&Bu®›L§K¡†  õØ^ó\rf“Îˆ¦ì­ôç½9¹g!uz¢c7›‘¬Ã'Œíöz\\Î®îÁ‘Éåk§ÚnñóM<ü®ëµÒ3Œ0¾ŒğÜ3» Pªí›*ÃXÜ7ìÊ±º€Pˆ0°írP2\rêT¨³£‚B†µpæ;¥Ã#D2ªNÕ°\$®;	©C(ğ2#K²„ªŠº²¦+ŠòŠç­\0P†4&\\Â£¢ ò8)Qj€ùÂ‘C¢'\rãhÄÊ£°šëD¬2Bü4Ë€P¤Î£êìœ²É¬IÃÌNÓšÂ‘Ó2É¦È;'\"˜dKÁ+@Qpç*·\0S¨©1\nG20#¤òí«SëÅJÒóØÇM32€ä¡°®ÔÉ,­HØ2cc&ûˆ¸È\rã:!-gZÖèê4P[à¡xHØÁŒÔ2¦Èešè´Éd?/ær\nƒ’¸¥¥)[OÀP§VÙÜÂº\"‡m£d%2\nc¨ÔİB€Òa•Zá,(Æƒ,}t7IÂ3¥	ìèÉ«Ãv\0É…ÕÍg‚ÑxBìª8YS†áåHæ(wèÜ0¾â@	¢ht)Š`PÔ5ãhÚ‹cl0‹¬ò¦&\r‚RìÔ»*,á©Z+£.G¢èévŒ§ÆúRIªïš³î‹F„U…V¯0¥ÍzøÂ§BšÔÑ¢% F×\r²7\n’¶(±İXTĞ#®„+Ø6Õ·áTÍ£ªS’G˜›¸b;~ãH»>Û»PET {âEïõcÁµ3wÈèÜPİ iÓÍ¨4Ğ6Ä‘¡#6œ„\"8@ Œƒj †oœŠH¤«¡âz42£0z\r\ràà9‡Ax^;úpÃŞ÷î¢\\3…úP_dw0^ßúÿ‡xÂl¼Üı!E¾mST ÚäİKiTÄ‚à]…GÌ7©PkT˜ '¨Ùø™±Ru^2ay/-æ‡Gô^›Õzîùtç¶÷Csİj(äì¾2¨ˆøpUÂ¥X§ÒúÉ‰=\r©ÑË)I¡™R\$ÈÊ†ãeÈb¤¨”î~ÎÙ©F(9¾æ¨ì˜Jà; €;¢ @¢\0rvÊŒ2«8p@Šékè9†c<LCy}31UKäÄG	º!FT¤§¢ÇÎ”#jfd°–5Rr¡‚FeI¨#ˆöBB€H\np:¡â\\ PŠb¾c™!\rÄ”ä¤fØ¸ù>\ræ±mrNÂšÓ!'ÍÁ@XPPd]'AQ1RÀBIì™?d ˆFéxƒ‚-RHÅBnM˜!|y±\\ÃFõ>B(T\rá­¥\0†ÂF<å•”“ª‘Cö5põ€3Ò²Kƒi!æV ´µ-ŠE!µ¹â@SÎy)2p É@ğA8\"*+AÔ3ÂÒºWi‘ÁsRjàC\ráå†T¾eC‹zkÈÒ`ÊÇy¯l\$Î ¿z-Ø:EH…\0Â¡>,mÄ3‡&HRXø Mb¤2\"˜šjÃa='ñB3õÏ\$H= ¯õûÔğQÒ³rhá‘ÄCæ‰¢\0¦Ë™¹H h¨a·¡à‚F†ÀŞõÉ„Ô‘²¶L˜Q	ˆ„®„`©\"Ï3RE)}­)L\nM5ä¢&‡&Ğ„B`'Uª½şÀ¦ï\$Ú=„±ˆ»*aZ°±ÌjÂielM‹…öØöD'ì'LÑY¦*l…OlA‰7{2ÿX¹Ú´Ìl6˜»fÇ‰ĞC,A°†4F_¹+²nUnŒ!•¾1Ö ¥¢šˆo\r&¦İ6½-šQØ†q†‚\0ª0-­Y PÎalİ°´ä´†¨6\"ÀïrLV—vù1ºı}\\Äx…\$ˆ’4U#?I<RrW@ŸHt“âæW¼6}Â’íTvŞÙå¶€Ò?&\$¡ÍPE‡SÙF°áÛ+¦BÚ_¹¦âà¤€äÂXlkŠEğ4‘±³VÖ”á2&×Á•MFpÏvØíla–Úß•ü¸ìÂåOØø2‚€æ\nR5f]åi˜üKÎYë=ÑØ2aÍ‚ØÁÁ3)‘Ğ­T\rÅtŸB¤ı\"Ğ";break;case"gl":$g="E9jÌÊg:œãğP”\\33AADãy¸@ÃTˆó™¤Äl2ˆ\r&ØÙÈèa9\râ1¤Æh2šaBàQ<A'6˜XkY¶x‘ÊÌ’l¾c\nNFÓIĞÒd•Æ1\0”æBšM¨³	”¬İh,Ğ@\nFC1 Ôl7AF#‚º\n7œ4uÖ&e7B\rÆƒŞb7˜f„S%6P\n\$› ×£•ÿÃ]EFS™ÔÙ'¨M\"‘c¦r5z;däjQ…0˜Î‡[©¤õ(°Àp°% Â\n#Ê˜ş	Ë‡)ƒA`çY•‡'7T8N6âBiÉR¹°hGcKÀáz&ğQ\nòrÇ“;ùTç*›uó¼Z•\n9M†=Ó’¨4Êøè‚£‚Kæ9ëÈÈš\nÊX0Ğêä¬\nákğÒ²CI†Y²J¨æ¬¥‰r¸¤*Ä4¬‰ †0¨mø¨4£pê†–Ê{Z‰\\.ê\r/ œÌ\rªR8?i:Â\rË~!;	DŠ\nC*†(ß\$ƒ‘†V·â6¡ÃÒ0Æ\0Q!ÊÉXÊã¨@1¢³*JD7ÈÙDàS¦¯ Sİ\"<‚òô#«èØQÃpÊÙ1â‚”œÒÃ•;¢´»A#\rğªÂI#pèò£ @1-(VÕõ‹8# R¾7A j„€¼°¸ÆÇ¢ª¢\r¦®3\0ôÌjc¾ ŒsTG^\nc*AjÈ«ë*\"§-T˜2B;U<‚È<C5XçCP[+Z·Ø°Ş1 ŞVuuë6\rë\0ÔßT(Â3Ê@P\$Bhš\nb˜2xÚ6…âØÃŒ\"èlÛsR*Ü8wt™(úB«%IMŸ•DÙnb±Dê+B½MÕŞÏ²­nÓO²kãêSR¨2´Ò^Ü Èîˆ\"_èî?Är¦è1}®jŒN¡\$*ıLÀ*µr˜7µX¨':’¡»ÚóZªk:I®n5PÃ°ä‹V›>Ó%éRŒ.°ºlET²0 Ui ¨Â>PèÍR#8AulêV¹ £ªR0£C63¡Ğ:ƒ€æáxïÙ…Ñ§:ü¯#8^Àê‚ú§\"¡xDwÏãÖã}Fƒ¬ñ;q,ç7k‰,'\n¥-<áêûíK}¦¼lõH®­;±5A7½2yÓ¨\\t’‡OÔõ}o_Øöc¿j2váwrîÃs»Cæ›@DUğI\$Š¡¯ò‰P 'Â¥U•b‚OBSÉ¤”³Ä×‹*åC®)k£ Ê›!œ\$ä¤Â#Tcã’&\0€Ø¼,ÍR÷…uË”´µ\r1¥99O °ÌgAç`C€è	¼3=é%;¦v¼šØ	¢,3W¦DÏZ0…MEØôĞèP	@‚†ô#((*Éú2¦ŒÙ;#ÄU:SŒ	7%aÀÔ¢Ğ;pC¨\nÄ‚ÒÔ2)G]¸”§“üQ:á¸86“\\‚ĞiÆ‰¡¡A ÒİZ¯\$1=¬äƒxkl)… ŒŸÔ¡Pf„U\0˜É	ToÄ©ÆæJáèm3ÉÄÅ‡‡¶SÌ)^I	*	ò‚÷V9\$Gêl¦¨GŞ„IHfBˆJW*“J‰‰¢Ù{ÇÙ|”fnŠINZÎ14BVçÂŸkË\\¤´³r“ƒ„Ö/©2cFÂ£Wm©®sĞtLãïK/°ÛÌHÈÕ“¬4³™ÄGs6lƒHvCm`Š’Òz'›€P¨D¡Â0ë‰JE%®‚\0¦Bcİ¡Çˆ›†rNÉZL€€#HØuæÑ&.­‚ˆA&ÑÔHEPm:œ”åA4	¡¼Æ”ZªHjºõnDĞÈ™4ÆáàŠÚ¬K «EéV\\@TKé~VúÖÕƒf®m½&Ø\nÃƒq´1¸#H’	)ƒ¸çÔ´PVÊ9+‹Ä:’TnÅ]%ËéÆ‚\0ª0-5WĞØaIo8uÖÒš¨ËjKÉ AQh‹‘”sÉ	d+tÜ³b@U[³KÊÒÕ=DRäK65 –Œq•=ÔuA†pÂ§áTº¤×AÛWIE:ÅX‚òó•Ö%\$øP¦ÖJ’Ñé<GH(P#~fùAKpÏ*5AÃ*«´ˆ¦Ó¯Ü‚š-»h\r ­å#y•XEb‡;‰EÍkd7†ùw…K~oÂ5®]èÌ69–€";break;case"he":$g="×J5Ò\rtè‚×U@ Éºa®•k¥Çà¡(¸ffÁPº‰®œƒª Ğ<=¯RÁ”\rtÛ]S€FÒRdœ~kÉT-tË^q ¦`Òz\0§2nI&”A¨-yZV\r%ÏS ¡`(`1ÆƒQ°Üp9ª'“˜ÜâKµ&cu4ü£ÄQ¸õª š§K*u\rÎ×u—I¯ĞŒ4÷ MHã–©|õ’œBjsŒ¼Â=5–â.ó¤-ËóuF¦}ŠƒD 3‰~G=¬“`1:µFÆ9´kí¨˜)\\÷‰ˆN5ºô½³¤˜Ç%ğ (ªn5›çsp€Êr9ÎBàQÂt0˜Œ'3(€Èo2œÄ£¤dêp8x¾§YÌîñ\"O¤©{Jé!\ryR… îi&›£ˆJ º\nÒ”'*®”Ã*Ê¶¢-Â Ó¯HÚvˆ&j¸\nÔA\n7t®.|—£Ä¢6†'©\\h-,JökÅ(;’†Æ.‹ µ!ÑR„¹¨c‘1)š!+hëàµÈ,Vê%Ñ2Ö§Ô#ÊI4‡'Í\rb†k”Íz{	1†¼–µ“40„£\$„ÆM\n6 A b„£nk TÇl9-ğüäÃ°)šğºò D°šå¦¨ #ëhtª¬I ¤¨dã5óŠá;-rÊ^è¤ü\"	­<„ Õ*TRlw’¨ÚZ/b@	¢ht)Š`P¶<ÛƒÈº£hZ2”F•A(ƒ‹ˆƒHæôj–<Nğİx^O•ëyŒ£Àè2Ã˜Ò7à0(µµkØä:\rŒ{&…(“\"û\\µMpJV„”èÚz½MÔºk%iŞ>Á³©mÖÀéZ[e’¡b¤ÁâLXƒXp|ûbÃ5\n6J1ˆN)zæÌÓ¢kÀÒ……£È6#s¿™ÇÈ2_JÒaàÂ\rÊ3¡Ğ:ƒ€æáxï·…Ú–©«<oÎà{ß{˜Ü„Aò`„±dèxŒ!ô„ËÍ:pÄgõÂŒ€fH>³3è-ë“ÌN3_*6h:v‡sN‚fÒëÛÅ²lÛFÕ¶mÛ†åª»áví¼o÷íÿ€ïÜ—ÁM‰+:'eÄç	³_Íè ¿ær£O¤{‘Ñ°×¨S«]“ë0P:¥ø°¨4=£Ç¾à\0î4ƒ`@1=£ƒ¾3<ChË?#5ş°cgÄ9†`êüƒ`oíU÷è=«½ı\0ÆØƒ#\0o¡„64]3ÄâŒ×½… \n (\0PRÁI¦RìX!°F¬úÚ¨g‚!¼\0äC³ğ¡ıÃÓô|—qó\rçÖ@îşÏ#ç;ç¶\$µğæ  J-ààOÙı?áÉ?pĞCj³¿3Ûa§±2÷”C!Ç5*1²LºÄë•Yb\\»c (ZÑPS)i*pŒÑ%ëĞòÔr!ˆ…ÂfMVI\$E,Ã%â€ôdìƒhrlÄ½@ZZ›´€q‡›bzKTúo'iYœÕ@5ÌCÓD\\i>I–I8˜ŒÄ«Æ xS\n’dRb%Ñ3­a%Ld¦;Õ›Rxk“T¦HÁl¤0iÎ`&÷&gUŠpW-@#HZIˆ–Mçè\$d—“)‰c;“Ôò`ÉÁÍc¯q\$Ğvr`˜µF©åy¬®iÌA -£¦v®(œó¢Ä½î¬Ò`sIKëOÈ¦óšOSŠT#Äµ)Næ„m‹ë˜Iid”ò TM!k3²²b,A0-\núD«s:ÉãÁ¨TE­Œ#FQDv(…ò›¦)Ìdz\\š´	\\ÕÃn¤’,%È0¬9¹\"–‘©-|F%<¢@KÕjT›\$rœ›¢GŒD~]T‰V£dCˆ…€«†¬I˜eFI¨‰-§«¦ycGÉ}NN¤¬†XôdKÉiĞŒzÊÚHğöTz˜‚‰Uyüírf³-	!Ò÷6\$@";break;case"hu":$g="B4†ó˜€Äe7Œ£ğP”\\33\r¬5	ÌŞd8NF0Q8Êm¦C|€Ìe6kiL Ò 0ˆÑCT¤\\\n ÄŒ'ƒLMBl4Áfj¬MRr2X)\no9¡ÍD©±†©:OF“\\Ü@\nFC1 Ôl7AL5å æ\nL”“LtÒn1ÁeJ°Ã7)£F³)Î\n!aOL5ÑÊíx‚›L¦sT¢ÃV\r–*DAq2QÇ™¹dŞu'c-LŞ 8'cI³'…ëÎ§!†³!4Pd&é–nM„J•6şA»•«ÁpØ<W>do6N›è¡ÌÂ\næõº\"a«}Åc1Å=]ÜÎ\n*JÎUn\\tó(;‰1º(6B¨Ü5Ãxî73ãä7I¸ˆß8ãZ’7*”9·c„¥àæ;Áƒ\"nı¿¯ûÌ˜ĞR¥ £XÒ¬L«çŠzdš\rè¬«jèÀ¥mcŞ#%\rTJŸ˜eš^•£€ê·ÈÚˆ¢D<cHÈÎ±º(Ù-âCÿ\$Mğ#Œ©*’Ù;æ9Ê»F¬¶Ğ@ÂŞ qóêFräˆ6HÃÓı\$`P””0ÒK”*ãƒ¢£kèÂCĞ@9\"’™†M\rI\n¯:!£\"š€HKQU%MTT€Sî·À PHÁ iZ† P–¸t}RPCC±Áƒb¤\rË›úpb¬PŠXµµ¢%¶oûø;´€Z6Œ-¨?ÎôSã`ãÈ!»ÈØŸ4uÒØ6”}NÏÛ÷r”\rw˜ÈÖÕ]ñp6¬øÙ~——¦·Î~_Ø\06 –Œ“\$‡C€à\r²˜¶<äÈº\r¡pÈø#“¶6\$²¢‚â6Ñ`A3ãv`Ö©™˜å™£Â²7cHß &âb‚íIKÓ5KZ7ŒÃ2€…0àWM­GŠƒ{_p4a\0ë £ÆÂc0ëåƒ:î9…‰ä<¦=Ã.ê]D6®ãª²aK\0®W\\úQÎZn*5P…šIïp@ ´+º5¸Œz‚¹çaâb4)0z\r è8aĞ^ıh];ïˆTáz¤™°é¡\rÁxDw-óÜã|ãìcXÂÉ!JSl5Kã¦âÊÎ£¥)K ï!t5KûÀşF\r„=Uz8\r*DDsÍOCÑô½?SÕõ£¿_ÊöAwiÛw¹î~ĞZ¾)€ø\$†Ğàm`tx™#4EÍÉ:0€›4ØJ6\r¯%å‚’P\$ ´;ÄVB9r*çd­ÇJa\rÁ¤ã6L»h`€;›TröHÈrÈ0É* ÂŸ\nlÍ¡µ6ÀŞÛD=3G\0Öã\$ê9!¤0†ÀæMÂqw/.‘¤d·¬a2}t°b®°Lá/lQ»7L€H\n‘Fs¬\n\nb+d|›„6†B¡Ë’ fÀÙCm\nUeDá!b``w.N,Í”ˆ(AIˆsC­\n ƒ4kKºNCÃÃ‚ÃA\r!Òˆ©\nO†©¹8'#´e@C\naH#\0ôĞÈ!˜%BÌ\0–¿•å{³-3æPÒ5'èâ€dJÑQ)H7”¥QÊËë	É”7³&gÌ„€ép’DCÉ§‘-ä.Bš8E 8‡S\nRƒ2\$QØ‘¨¨‚,g\0á!ØRâÌb9\"Á¬˜G€Â¡0O\0„.ÂVÌ!ÎqK'sÎQŠAÊz@€;Ú…²ÕˆÌÈƒŸò¬fNìÚi’á¶Xèèb–`€)…˜L©­6„Ä#HöVL€Èn\nPŠ\$WA³n™…<-”Á,bDæ²£šPUé™bLæ¶…ci©7^õÖ7—r²é˜\r{Sõš¾œâ’X|(õúÃÈPè|Á]\r!C²cMCe‚,R~fè‹É¸Fª\$I'”§²ŞB¨TÀ´3W3?pg¯Jˆ‹3²{\$‹Øl¯,\nÚÕ«píÚ÷·¶İ\rÜ¼G‰\"\$„'~¶ÎÜ3£¥ì¾¥cÀEÃMEæ“Ò”¸”`¡a¥E0äa™ú[j\rzQÛÍz#A84ÕëóEg1‚A¼åQÂ&,É%q/jôP¬ ôÑC}Hb’y<ğ™‚mï;õ\0007ã°¯T(ÃÁÌéS@hÕ¤;xïÜ\$o1>&=fZ—%tU…šJ@<[€múH\"ªé^2cuxkXÁ¸øBúwÂš\rá¬µá¼;Xµğ9á”";break;case"id":$g="A7\"É„Öi7ÁBQpÌÌ 9‚Š†˜¬A8N‚i”Üg:ÇÌæ@€Äe9Ì'1p(„e9˜NRiD¨ç0Çâæ“Iê*70#d@%9¥²ùL¬@tŠA¨P)l´`1ÆƒQ°Üp9Íç3||+6bUµt0ÉÍ’Òœ†¡f)šNf“…×©ÀÌS+Ô´²o:ˆ\r±”@n7ˆ#IØÒl2™æü‰Ôá:c†‹Õ>ã˜ºM±“p*ó«œÅö4Sq¨ë›7hAŸ]ªÖl¨7»İ÷c'Êöû£»½'¬D…\$•óHò4äU7òz äo9KH‘«Œ¯d7æò³xáèÆNg3¿ È–ºC“¦\$sºáŒ**J˜ŒHÊ5mÜ½¨éb\\š©Ïª’­Ë èÊ,ÂR<ÒğÏ¹¨\0Î•\"IÌO¸A\0îƒA©rÂBS»Â8Ê7£°úÔ ÂÚ À&#BZ\"ŒƒHèôB„M9È\nÔ&¥c¨ÖKª-CjrB(İ!\$Éê…Œ‰4œæ)€ÈA b„œğBq&£‰Êê5¢¨äÛ¯Îºàˆ¢h(²ãHĞ×£Ê6O[)£ ëL	ƒV4ÀMh—R5Sb!JÒé äÅ¯cbvƒ²ƒjZñº\"@t&‰¡Ğ¦)BØóa\"èZ6¡hÈ2RJJĞ9¢\"ôÓ±Ê@@ôõ©\nÚé¬2’ö²ÂÏXÌ¸@PÙL;™1ÃxÌ3-#pÊº%mÒ%Òãªd‰\rìŒÄİ	ê1Œi€æ3Sá\0Ø7Œè@æ)ãò¡\$h@A³hÚ„6ã(P9…*ZQX2á\0×~¦Ih¨Å¼)¸Íl:­è@ Œ˜ıì9bƒÈŠ%\0x°Ì„C@è:˜t…ã¾¤(yÒP=8^ï„¨,¡xDkÈÓzã}|BŒXÄ½b•Œ@ËØpÃˆ¥²ëB™¿Oãıl…H2¿ú‰£iV™§jïªg(F¯¬ëcv·p£×,)ğ’Õ³\rÈé´mVä‚4\rólŸ@åxÛò”`hğèõKI\nFá¦K5&ñ Ò3eÃC0Œ{‚;²õ’NÉ%œÃÂ(W>ƒƒá/ŞYaø'\$­˜£¡\0ÇKÓÂÂ,!qwR:¤·L¿_3İ\n@ œ'Rm<å'AB\nJyl0\$´!–UìñY³«2fTË™— }Ãt&æ”ù” ØÃÃ»„x\$ ÇRÈË	ceïB3¨~I[~I´;š@ÆIXøgiDĞ§¼'ÄNª÷`¬ „0¦‚4IN­X)´àçÕ>©¢(Æa·¬ğêPP På91!UßÄKI¤ôŸ“˜Š€P\r!¬ !RZHxy1í6ÁPæGQ¤&êX8‡R`FÃ1ç\r¬İÈ³¸jGŸğÕ7xtzË€ \n<)…BJ\n(l1\$œ˜˜Œ¥QsºZ¸”Á'	‘;RÁ¬¨òÄƒ0i\$	\nƒ\rÃ±3Ì<¡”\$İÂ˜Q	‘2‚,@Â0T~äå6œğë¢d‚\"å«¦TÎes(VI4ÅGÓ5İÑÈ›DîS¦ Ò’çÖœD­”¤Óˆ_\n-c«fpIpä‰Qâ‡IIõ†ÀVËHc\"Å–_dŒáu.ı‹¤âZ¦,NB0ÑP¨h8Œù&‘ùä›L”&ó­X“¹œz@PGnèÀ±õœi‹’\\¡a˜<€£r¥I\n\nJ^v\0£ê™ÎÛ@Ñ´É¨\"Šªê<(%Ù-ÄLL%\rÆ\\0»¢9ÀPL\ròô6ƒŞ{*Í[£¦øéV›Ù4‹¤)¯ÙôîÕŠª¤%è×)pÊñ\\%)™Š=õB çà‚}0†ÊH”EELº-¦'^‘ú¢R#`";break;case"it":$g="S4˜Î§#xü%ÌÂ˜(†a9@L&Ó)¸èo¦Á˜Òl2ˆ\rÆóp‚\"u9˜Í1qp(˜aŒšb†ã™¦I!6˜NsYÌf7ÈXj\0”æB–’c‘éŠH 2ÍNgC,¶Z0Œ†cA¨Øn8‚ÇS|\\oˆ™Í&ã€NŒ&(Ü‚ZM7™\r1ã„Išb2“M¾¢s:Û\$Æ“9†ZY7Dƒ	ÚC#\"'j	¢ ‹ˆ§!†© 4NzØS¶¯ÛfÊ  1É–³®Ïc0ÚÎx-T«E%¶ šü­¬Î\n\"›&V»ñ3½Nwâ©¸×#;ÉpPC”´‰¦¹Î¤&C~~Ft†hÎÂts;Ú’ŞÔÃ˜#Cbš¨ª‰¢l7\r*(æ¤©j\n ©4ëQ†P%¢›”ç\r(*\r#„#ĞCvŒ­£`N:Àª¢Ş:¢ˆˆó®MºĞ¿N¤\\)±P2è¤.¿cÊ\rÃĞÒ¶Á)JÙ:HÒZ\"¯H¸äìÅ0Ğ û¿#È1B*İ¯²2n—\rëRJ80IÈİ/ÃBrÈ;#ØÊ™¬Á(ÈCÊ¨ÎÓÄô¨CÍ\0‘A j„¡Ìú³¬cpâ:ÃB|´ê%ÎœT ÌS[_9S«ğŠ§(ü^¿	ã;<:,Â2±­Úş73‹ôú2Ôƒ\n>-uªäO“½qR£•ìª%£\n4àP\$Bhš\nb˜2xÚ6…âØóo\"è+5Më¤=PLÂˆSZ¨ÁĞ¨-ƒ›xŞwˆÊ<@©ªIÓ*œ469@S É\"	Ş3Î”×\n°L´¦\"°ØŞŒMCËÄc3¨àÙ2¸Ac@9cCµ×‰,ú6·Š P9…/ã	Œ¸dr:7±‹r’ã˜õ˜–ŠŒ£¼ÕŒ×šPçÉ˜Mc–MŸ¦¨Ğä‰€Ñ!ÁèD4Uƒ€æáxï³…ÖV ÁË@Î¢¡{Ø¶7i ^ÛÖ7áà^0‡Ø‚w¤)M•90Ã/\"Èäš¦)Ş7®éÒ©«¾ÉÜ³aô„ıëÖ¹¯l¦Å²lÛFÔµí›và7nâ+vŒ	#hàN›÷ğĞ¼´ÀÂ5¸/¬Ò0Íh²Z&p¬<Œ³ƒ0Ã°I\næ¥m2«\rÃ!\0Â1î©(î£‹£2Ái#’);Œ#7.øã¸şBds0AóƒCPáH™!n}/¤pØÀ¢ıze93¤#†ãL1;?äùW øIHP	@ø‡æ‹‘§“æ|Ğ@b(\$´!’DÖùa¡3A¤Î<àøt5f¨§JÃºChˆe!+ÃVL!;ã8\$•şÄ°àŠÊX?OìÔ†7üÌ;`O†&›‡pÚ oy”!…0¤ ` I¤öà@10\r„ÀˆÅ`Òr y—×tcpP0DtÊ–‡´‹û	AE\$…‡“\"LS¹ï!„U;¿Øx”š#¤6´Öë¼M\$¡‘œ3Q±h‘í\"DF­A\0P	áL*Fà¥ECë­y>)E¤Ts&)	VCDI!ä0©ÕàÀ<^\$¦‘¬§Ä` \naD&›,0Tƒ1;“(r¯€f“’L91\$ïúq:M@Ó9C¡<±eE¨´7IìtSœñ^'âÂşG!„\$aª£NË¼ø%€\$¥³Á'©¸_ô:%”U…D¤\0\nÊè3”\\¤ !¬øœxÔ[d3ëjá´¼—·ÈÉhFAÁ´”ÔË¨TÀ´/Cr^›á-O¥¤˜NcèzK1†§B¼Ä	Î‚“¹DLQ‹%¨¹\0¢ğ^ƒ0yZçJ&À#š0´Ò´#²vÕã\"-t²ºSbûL\r5/’*L•20“\$3Ï\róZšDSKYBe‹©=+¦bıB‰‘~0\\ŒPš0S½LX&.,¡ ¦œŸ\"C!ÌŒ¶OSËûº¶\rGVë·Cvæ¯Öú¦†«=~&ñVğ";break;case"ja":$g="åW'İ\nc—ƒ/ É˜2-Ş¼O‚„¢á™˜@çS¤N4UÆ‚PÇÔ‘Å\\}%QGqÈB\r[^G0e<	ƒ&ãé0S™8€r©&±Øü…#AÉPKY}t œÈQº\$‚›Iƒ+ÜªÔÃ•8¨ƒB0¤é<†Ìh5\rÇSRº9P¨:¢aKI ĞT\n\n>ŠœYgn4\nê·T:Shiê1zR‚ xL&ˆ±Îg`¢É¼ê 4NÆQ¸Ş 8'cI°Êg2œÄMyÔàd05‡CA§tt0˜¶ÂàS‘~­¦9¼ş†¦s­“=”×O¡\\‡£İõë• ït\\‹…måŠt¦T™¥BĞªOsW«÷:QP\n£pÖ×ãp@2CŞ99‚#‚äŒ#›X2\ríËZ7\0æß\\28B#˜ïŒbB ÄÒ>Âh1\\se	Ê^§1ReêLr?h1Fë ÄzP ÈñB*š¨*Ê;@‘‡1.”%[¢¯,;L§¤±­’ç)Kª…2şAÉ‚\0MåñRr“ÄZzJ–zK”§12Ç#„‚®ÄeR¨›iYD#…|Î­N(Ù\\#åR8ĞèáU8NOYs±ùI%Èù`«–trÀAèü~Aó,Î[¤ª(ùsD¯äª%äG'u)ÌXáÎMEª9^×êEJt”)ÎM•ÑtxNÄA ‰ú«‘ÙEH÷“dÜ! bØ§¥!8s–…š]—g1GÑÑÑ˜ĞëÉ[^„\"òE’¶¸t%ÁÌE?4rU¯%¹\\råÑÈ]/J	XÖg1n]œ…Ù0IŠ2‘Š\$ç6å¼AÒ˜”Ie¹y~ËMzˆœäy},EÒ”ó=§³Øu1ÁÒ0cÎ¤<‹¡pÚ6…Ã ÈªU7ú?Vˆ3’ÛI!ÛX¨ìÃ–Ê2Ü9#~çP5²9pƒ äÒ4Á\0Â95ƒxÌ3\rƒHÜ2ÄÊ]smÙuPÌØ¨7µãhÂ7!\0ë¹£ÆÜc0ê6`Ş3ñC˜Xİ\\èÂ3Œ<PAÛ¶èÛÅ®P9…;Å™É\\­Â*50t3lã®è7á\0‚2w|Xå×Œ{¶è2A\0x0µ Ì„C@è:˜t…ã¿Ü>§ĞXÎŒ£p_\nm#¦êş>‡ÅpxÃ>q¢ì•ñnG\n³h¢pOŸ ˜•H‚ ¦5È«åHXV˜´:Â3„ R HÌ&¡„Û‘\0sDH‘‡€à\\\"%{Ï€4>'ÈùŸCê}¸;¿äõŸ¨r~ïåü7òİ°/EDÚ\r°m!ÒÀ–Ë o[¦ğ9 ÖkJAiÅ†àéàh‚â’b|\"O2·YLöÂxS\nÀSÈ5„1¿æì¹µuˆÖó”Z[¡„3Bô4è]2tÎ¡Õ:Ä\r\"N!»\r°9†´9‹a¤0†Àæs‚2FˆÙ#¤ AŒñ ,…˜\0*1FhÕ„rCÄAQ!äÎ[ˆ(¡[Û“š±\$Ã		VL»4a\r»8¹MÙ¯6&ÌÚ›pÊ·Pèr†èã!t3+]Pwœç2A8GnnŸ\0sDÛ c‰ppw¦Á¢0ä·C¹Åa¢2†ÎùŒ”F°1†œãh¸Sjtƒ\n †ÂFš-xA(¥‚cåÂ5œ³GREDåäW&ÄÜ9Dx¯˜ÒØUâ€P™BªbdÈs'BL:± Qø±	úPÛ	0åD A,’ÅL!E2Hô ’,\\d\r+t×!”\$£Å7N8‡Sr†C2\n\r¯J#½ÙşƒªBRŠv¢ps€O\naP5Òb«A&¢›ˆ Dr[¥3‹ò>Sp‚UDXBt	È`‚E¢E¶QĞ*jkª5Æ\$_kªÌ*¦f”ÓÑToSñpTF‡\0¦B` Ó”GÀ‚ PpU¡ÛÅt<íë…rCH Õ Æ´VšÕBHróâHÙéd©·]i-Hì&Å æÊğÂ2\"TV…å»BHsc1ï½ëvóŞ›×~/p\nmø6ºòCklÈği[[§eŒA¤39ØÒs5Ä\r¯9Ñ'lB F á´=šÄôc¹l“Añ'UÍK,Wä@‘8_šğƒ0%bí°ÆÄ	¡612ğé¢õ:ÂÄÍ,Pt‰aDsŒpç2HÊ)b9©)&ÊFDÉ²>œæB»gÜ—ˆ~:ñ1ÌÆhUf\\ÏšH0¿=g´\"ªH.	é?âÔÇ¡QÄ€æÂq™¶ÃXxÂáp‘úLKñÊ¥#åƒ,ˆ%o‹ñŠYÄscª,¬v^KÑ*ZåÕÎ;b†n¢‹î(“Xå";break;case"ka":$g="áA§ 	n\0“€%`	ˆj‚„¢á™˜@s@ô1ˆ#Š		€(¡0¸‚\0—ÉT0¤¶Vƒš åÈ4´Ğ]AÆäÒÈıC%ƒPĞjXÎPƒ¤Éä\n9´†=A§`³h€Js!Oã”éÌÂ­AG¤	‰,I#¦Í 	itA¨gâ\0PÀb2£a¸às@U\\)ó›]'V@ôh]ñ'¬IÕ¹.%®ªÚ³˜©:BÄƒÍÎ èUM@TØëzøÆ•¥duS­*w¥ÓÉÓyØƒyOµÓd©(æâOÆNoê<©h×t¦2>\\r˜ƒÖ¥ôú™Ï;‹7HP<6Ñ%„I¸m£s£wi\\Î:®äì¿\r£Pÿ½®3ZH>Úòó¾Š{ªA¶É:œ¨½P\"9 jtÍ>°Ë±M²s¨»<Ü.ÎšJõlóâ»*-;.«£JØÒAJKŒ· èáZÿ§mÎO1K²ÖÓ¿ê¢2mÛp²¤©ÊvK…²^ŞÉ(Ó³.ÎÓä¯´êO!Fä®L¦ä¢Úª¬R¦´íkÿºj“AŠŠ«/9+Êe¿ó|Ï#Êw/\nâ“°Kå+·Ê!LÊÉn=,ÔJ\0ïÍ­u4A¿‰Ìğİ¥N:<ôÇYİ.Ò\n‘JÇMœxİ¯šÎ““‰,‰H«0é0ÓĞñê”ïÔµm(¨VË/VÔıwYÈÖ<X§5©QU:‰Kÿ=@k;ãYÄOd@¥GuòKÜMÌ¬¢C\"K©õ-?4] ¡pHßAŠ€ÿV—M¯'À6ÍÅ¥³Yø¿’%E#–²PÀ6º±IÁµ?;ÓmšrøËÖ½ÀÿÄù4\$ªì§T¹ob´!Ò€³'0£f[å‚»º×4´¥HTB,Ô¢”Ö¹ÚÓ‰èÉ>˜„·r\0İJO²àÁ–³—‡Z¹¯*†R·°7[Hº dmŞK¢©TÊêğç…W-¬¶ÿîªşñ»?I<ÄªË“¢Ó©Õ86ƒ–Ù„éÚ‰j¨‰>ê5¹…M¦µ|¯uÎM”¥Zí¯€*\\«ÙwäŠ©_ Eoş)nõ;_^àî5¹Ö¹öYê­¢åŒZd	z½©Uyé³FÒB9=Ğn>¢röÇRæª%¾ö4öãâPP“ô8Ï0# Ú4Ã(å*©şËªökÇÉ‚øË3ZucXG<ÿ¼—ë9×\$@eÀô€è€s@¼‡x<ƒî~ÈğäÁxe\rÀ¼2ğÜC i…À¼ææ¶Ô©º#Äî)S.xaÎ|İ¸“òlË±ß)/]€ å£SÔ#Æèë›øäSËyH1[@B”ZØS™R©Î<ri—ùDŒ=ä+¤Š‡Pz€p	3r<·ÙD\rğF	ÁX/`Üƒğ…÷¿å	a<)…a”<HTá”+E~7”îòMCæìKÄ„íË*•}MÚ+œw,‚£·:çÄ¤‘çÿ+¿wˆØ7¸ê§‰)×7|’É•*ƒÔìF\$Ï…JÄ¨\ná”zİ'%:!Øeåë‘•ÄÑÛª7u™â‹o5^-Ç¸€aê·eñŒ¢—:SåL77e&	'¤RœÇp±5*–\\qÅRQp†õX«Ç¾ Ñù»q‚,3õg<Ù\n-ì»æ\0—p |šuwßú',5=ÎÁ;T¥™\\StkQz²QÄœëš—C6Vk‡(K!ÔmB¢<–‰iÜRó/)Qe£LV}'æ:ÁÍÄét^T6„9×z“İ¢ğ@Â˜RÏÑÍ/I6¯ÙLä`õ)zGvIQRœ\\£ô¸Nds¦!Oc\rQç'Ùë,ãÖ£Òá4Ôã¦r••G˜Kp©šJoåwş:Vâw›ès4ó.j©Œ]š|É,Ç~—·˜Ø™¨Tt£•µÌ0'°û!†~•˜ÛÊYşq¢«|¨¨>x›‡^¯\r1?\n<)…I¢@YúÆ©óS’WP¯jµŠâÌ§§q(ìhÊ»ÒZÙc–”·vöß›†TUVâ„§%bêRg%ÉÊ¥wzä¦Årë@wÒê9‘ÅKFN;šn¡*è¦i[±we‰ÇDÎß«Ùg]Ü%äŸè}suáğKSnG+¢¦¤Í-Ú:cNº.êÂ%¢ YL›ÖÄMT·ìRIí¼xB¦ìÆ\"Ôf =¸e 3şıã3”7lI…ÇH¤d¬RPn46µ IÏåøAìa‡\nÊ˜på–òîTd‡pƒ×¡v}WExSpª*Ø84óÿÔºÄ©eãQ°å†šs<q¨ì6÷vàŞ9x¦M/¡oRü`ÔÓŒÉÎ65Ë]zy²IéLÙS–}°‰åSP;Ÿë5L‰îpÍIVe[){s•Q'ÑZ;\"	<Œ¥eòN¶H¤ÎØ0˜,ÕdaºãzMé»ÕŠğ”J•ˆ/¾³í¿dGm*ÛòŠë}FYJ¾F¶qõ¶‰”lÊç#C4œyÇ\\2ÜÆåd:]µÇd%\0œ©Ì›Ö>mĞ×åcç}¾€&Í7 =\0´™Ï²õÂl7j£2";break;case"ko":$g="ìE©©dHÚ•L@¥’ØŠZºÑh‡Rå?	EÃ30Ø´D¨Äc±:¼“!#Ét+­Bœu¤Ódª‚<ˆLJĞĞøŒN\$¤H¤’iBvrìZÌˆ2Xê\\,S™\n…%“É–‘å\nÑØVAá*zc±*ŠD‘ú°0Œ†cA¨Øn8‚k”#±-^O\"\$ÈÀS±6u¬×\$-ahë\\%+S«LúAv£—Å:G\n‚^×Ğ²(&MØ—Ä-VÌ*v¶íÆÖ²\$ì«O-F¬+NÔRâ6u-‘tæ›Q•µåğª}KËæ§”¶'RÏ€³¾¡‘°lÖq#Ô¨ô9İN°‚ƒÓ¤#Ëd£©`€Ì'cI¸ÏŸV»	Ì*[6¿³åaØM Pª7\rcpŞ;Á\0Ê9Cxä ˆƒè0ŒCæ2„ Ş2a:˜ê8”H8CC˜ï	ŒÁ2J¹ÊœBv„ŠhLdxR—ˆñ@‹\0ü‘n)0 *ê#L×eyp0.CXu•ŒÙ<H4å¤\r\rA\0è<\nDjù ÂÉ/qÖ«Å<ŞuˆzÃ8jrL R X,SÜú¯Ç…Qvu’	š\\…–ìÙ:Å½'Y(J¤!a\0ÀeLÔÙÓšøu½çYdD¤ÃETjMH	ÔZÀEv…åó%õMÅ åiÖU/1NF&%\$şŒŒ1`ÚåO:PP!hHÚÁ¬üY9¤½EBbP9d©ÖP[ŠJ—³b‚0¤!@vdêôT¶…âôY±ä¦vHgY<Â?I…€Â–Wl¦\$jÅôÄß¥¥Œu|IÍ«Í~dÕ2ÒeJ¾¬ôÚXMèPtu–Ä t¤\"Øó™\"èZ6¡hÈ2/MËwKr=y^„ìŒEÉT˜\"\r#œ06CŠ A	AÃvŸ¨Ä:¦¤2 Ê7cHß°(5ùR—^Ã`è90²YÚJ‡Z³G´M[”v‰e9×5Q*¾“d*\rãx@6Œ#pò»\0ê1Œq\0æ3£`@6\rã;ê9…“åÅŒ#8Âú„&)Ã¾£®¼aNË¾³{ûA¥:bê–¨>¹2`¨4q#\\B3jƒ®ÂûÈ6¾°:1ì{É„àÂ\rÊ3¡Ğ:ƒ€æáxïó…Ã—æÂpÎëá|=«›Ü„A÷ç>£8xÃ>(-àÍˆRk–ÄkH‘.îÛ±@<Ä…+…®Ú;I‰, „Ô4×“\n,h¹ àğKÚF/Uë½—¶÷^ûá|o•ó‡wÒúÃrÏ¹ø?v¸×›öPÚ€mkáÑÿÀláÃ(t\r½d‡GâZ\r(­¸ˆrƒ¤£–C—Ó\0H‚¤ƒ•á*­–4lì„ »ô8Cõl`€;†ØåƒL¡Éâ(”²Cf„•Ç¹Få³˜sH6>Å\$ÄãN‰@€1Â¨–ClhÉ#Œj]á¬\n (dfsAT&™:Ár i~+e|ÁB<YÒ±ÊmÇ—ú˜œ0pA¤ú5\0ÎVJ)AÑ´ÔDÑ\$¡sŞhGY0ö*!záÍ9Jƒb”Z\rÁÁÕE	’Éá 4†0Ğê;ßK¡ÀÆ^0e€byB¨uS\nAƒeæ MĞ%±Ò	ŠH*Äé^d¡¤¨6“Ò4(À€÷Ö\"˜•&e ¥0ÆşLiY8h)˜Qxî¦*€ ”‚šfy„R<È\n¤š>PBI!¼:¡ĞÒ²Q%kñVz\"´Cª D™†×“¤éA¡Ì!¹/5‘dÏ(!@'…0¨y™ñ®R¢Â’aZÅa¯\"ö‚êàn«’–)¥<¨Óôp_Mpê\"Á“–¤Ş(° ¤Dª“Ô*‰ á\"¢\0€)…˜AŠs)*\n~;D3¹!œV²ZKé‚Föµ¼7¢ú%Òãy¤‡¼ı±1m	ÂT‚Ğ‘	U|°«\0?'îœ2zf.3fv§¸Xæ%®Ï#(…^İ(Òh\rÍ•')Š1biqÔÓº—ZñWĞÛXlu’z†¶¦ƒ^¸v”5fd8x¨C3‹‹e#Gä8^2\$Ÿ*…@ŒAÃUz‘ş”Ê,	™7NŞ\nş&L•Î5†ÀÇ;’Ñ©¿&åãâ&îN)	…çäDÁÔ-ÕMædÍ™ÔánÄ•ÉÆH±.qH:ÅI¯;¦uaÚS¬2CS0fY‘Ö#D¢ÂÄ˜¦e{\0³l,¤Â•‰ÄĞLxŠË(àÏ˜œO€ëòö“\nu¾¯×“8Êº5õ…ğÊğoÍz¨P¾Å«/BÇ±Í±é-½#É-câCddO6Œ¯ìü©Åº·ô²P2&‘o˜asmU*a¥\0";break;case"lt":$g="T4šÎFHü%ÌÂ˜(œe8NÇ“Y¼@ÄWšÌ¦Ã¡¤@f‚\râàQ4Âk9šM¦aÔçÅŒ‡“!¦^-	Nd)!Ba—›Œ¦S9êlt:›ÍF €0Œ†cA¨Øn8‚©Ui0‚ç#IœÒn–P!ÌD¼@l2›‘³Kg\$)L†=&:\nb+ uÃÍül·F0j´²o:ˆ\r#(€İ8YÆ›œË/:E§İÌ@t4M´æÂHI®Ì'S9¾ÿ°Pì¶›hñ¤å§b&NqÑÊõ|‰J˜ˆPVãuµâo¢êü^<k49`¢Ÿ\$Üg,—#H(—,1XIÛ3&ğU7òçsp€Êr9Xä„C	ÓX 2¯k>Ë6ÈcF8,c @ˆc˜î±Œ‰#Ö:½®ÃLÍ®.X@º”0XØ¶#£rêY§#šzŸ¥ê\"Œá©*ZH*©Cü†ŠÃäĞ´#RìÓ(‹Ê)h\"¼°<¯ãı\r·ãb	 ¡¢ ì2C+ü³¦Ï\nÎ5ÉHh2ãl¤²)ht¤2¦Ë:Í¬‰HÔ:»éRd¤ËÃpóKÊö5´+\"\\F±»l¥-Bœ”8?Æ)|7¦¨h¡43[¾¿Š\nB;%ÓDËG,ÉZ	©i{0«‹PJ2K² 5J‚è%SRTÃ¢ì,øËA b„x¹£*¸Š¯ìØæ:Sª4¯Ï(˜•Tñ¤È”S@P‚:<sÃÊ\"tP1š¤ÚË“íàU4¸ñüFá¼®uß§5\$•Ipy.×…ê· –	}_…›†0ß÷½†ÆKÛÓd‰@t&‰¡Ğ¦)BØó\"èZ6¡hÈ2ZÖÄ®â“‚ã*ˆ‰³X¸D¡Æû-\rk*fMşp2˜Ü”ùúH)¹lZ#–KÈÆÌ¨Ş3ÃbÎ2èo¼Ÿ4ä³TØ3¯â Ş‹%sA6XãÆÏc0ê6-ãzÍŸ…8ä<„BÏ\r¾µPÚ³«˜P9…) ”b•i{û­;ñ*7)(b2£|è*2oŠ^3fitÌ#&ö¡[ˆÇ ¥##î˜@ĞãÁèD4ƒ à9‡Ax^;÷pÃÏjc\\±Œázâ½«B; …á}â¿³0xŒ!ö«5rA[©)IRU¬«bSo]|D,ÊÃŒ³f5\$i-%ÓÁt ûƒ|½[İK%Öuİ‡eÚvİÀîîã wáÉà¼6xÏš\0ny\$èÚ‚q=è³¼jA§lò  à}Û	qMF£²\"½ÃXySFdàÄá!SÍ†ƒ*CÇ>¡ÜÎ6ÀÄe`ørsÉ/*@Â‹™¿l­¶¦ØH›x ‡† ÓÃbRğ gÓBX9maAÒË\\b2É!ƒ.†Êâß…	u…\0ôeDHì§‚A(PåÍš,fÌézAh\$—š£.‚‰a\"ïĞğÃcŒ†Ü1\"-¼EWÈƒƒ}[è5‡%HÍHc\r\0½†w`©L©¨2¡ŒÙ‡6¨˜‹še;ïl!…0¤ Ñ-˜ Â‰›ãqËôâD7\"KÙJ]esA‚ÎY\$\nÆøÂ/BVJÑ-%åÀ4†EXj;E²µ\$vv‰6|îr¥s²ÍÓ I!aäÈj©\$y.*Ô£ŒC©ŸAA˜ü†×9\0]<²>¡Œ‘Khªjˆ‰\$AÓ—³h€O\naR2 ÜcA\0S¤®sŸïIf•'D“À5‘†êJßŸóØÓPÜ‹+„ aF½WÁÃ¤*CDèÇOÙh}M\"wmÕR–ZRBa3†TÍ°€Œ#ÄÇT„©#t7BèmM\\’#Jã&.E¶˜%ÀöÈ‰(õĞÀWeBÙÂÇ\rk\".¨9^ÉzªSõı})ô¿¬QÔ®á¦ÆØvbU%‹1…Ş@-€Ø\nè ia­™V‰e	ƒR±DÌİÉ=«r°— ¨'B¨TÀ´3GFMNù\$²5¼é‘¢8SyÓhÄxŒ\\b:¾®àGw4Ÿä\$HÃaK¬ ş­“ˆ\\‘{Ä–ÖÚğKñl\rI¸ÓÚ€äS¨y+‰EÂø³Hqc-…í½œS_Måş %p6œÌ…|Ê¾Ê´Í¹,ROÈd› (&ú¦äd¹¥,8VzõÔK”UA–\r¥Y¶l‰yÆM¬ÿ.+£_îMšUØ¾ÍÂ6aÉz<Ä‰ÆàŠJ+*âÙµ•„(V!š)ÅŞæ‡Ä¼¶ÂŠñ.šİdÏ2d_Ät8°";break;case"ms":$g="A7\"„æt4ÁBQpÌÌ 9‚‰§S	Ğ@n0šMb4dØ 3˜d&Áp(§=G#Âi„Ös4›N¦ÑäÂn3ˆ†“–0r5ÍÄ°Âh	Nd))WFÎçSQÔÉ%†Ìh5\rÇQ¬Şs7ÎPca¤T4Ñ fª\$RH\n*˜¨ñ(1Ô×A7[î0!èäi9É`J„ºXe6œ¦é±¤@k2â!Ó)ÜÃBÉ/ØùÆBk4›²×C%ØA©4ÉJs.g‘¡@Ñ	´Å“œoF‰6ÓsB–œïØ”èe9NyCJ|yã`J#h(…GƒuHù>©TÜk7Îû¾ÈŞr’‘\"¦ÑÌË:7™Nqs|[”8z,‚c˜î÷ªî*Œ<âŒ¤h¨êŞ7Î„¥)©Z¦ªÁ\"˜èÃ­BR|Ä ‰ğÎ3¼€Pœ7·ÏzŞ0°ãZİ%¼ÔÆp¤›Œê\nâÀˆã,Xç0àP‚—¿AÂ#BJ\"§c¤\\'7¨áEÀ%¾aŒ6\"Œ˜7§.JüLs*ú³\nØ	.zhÃ¨XÈÂ.xÎãòıI°%A b„Br'qª0¥²Ğ¦é2`P¢HÉzÜ(\r+kˆ\"³“{å\"ÒãÔ2sCz8\r#’oM&ã¤a;€Ê¹ğzt4¬`’¾Ê\rdµ	@t&‰¡Ğ¦)BØói\"í(6…£ ÈTtùB#ú\rº”=õÊ01\\ªK÷tÜã(ğçÉ‚ÒÄ¤Éô`ŒCd?# (ìİ'#xÌ3-£pÊ’ŠØÂ›LS#/]¢ƒ’ŞèKğšôê#r¸ä1¸Lûv6bS27ˆ')\nF\"\n/RòDË(­ìk©3¸ÚØ‰,Ó‰Ìƒ\nè.‰²J*:	Š ¦\nC1c lâq,¥Ê‚2gxPå–cÌŞ7´sD‡ˆĞ9£0z\r è8aĞ^û¨\\¢k.€\\÷Œáz|Œ‹J*4­!xDjC# –‡xÂ6OÓ-^¨òğêÉ™baä“S•62ÒÚ’Ë•¶e ·c­l–ÉXr‘0spA²„>Óµí»~ã¹î£¾ï¬5;Öù¿\rÛõæŸ^Ş(D¥Ï0Àq¼x@1ßwp@Ğ´zÏV#c\0ê6CzÌœ(O²aS~—!3£¸Â1¡H@;Ô(ú{ôÏ{8Ãf9áÈˆPÆÏĞsÁÔ¹ÓRıÃHt)Hû¡8ôÛCŠlEÄ6/‚à\\¡¿|†ØŞÂ\"BnÂ€H\nÕ\0PRJ¿-Œœ¤·îU\n³:ğ•³0A,'¼ƒ“:@á±;'¤ı÷–÷âÚ\"!C\"ów\"³˜ „ ‡îHc)ì3¶Ö†’Ÿ‹Ó'I…–÷ÄO\0C\naH#\$ ÒTMâNÍ¬ÄĞƒï…«î™bŒÍŠ7(ĞŒÇ£G)ÕØeK\0Ë,îÊüKF¾=²èìN\nt9ˆ`¢4@É¹Œæ9íGåmóYVÅØ-ó»y€n\$:§.í^êU7fÀø¥Ğ Â˜Tu¯¥î±£ô°Á\0K¡PœÂéš!€ŠœÃ­ªî1LÂ†õËÈ¡#h•*öBdB'\$õÀEK3L°F\n­Hƒ=Ó–JåˆTâdj¾'dØ›AÉ¤Wæ`äšƒUA“’ç¢Dí“DÔøkÓ•	r°\rU—EğĞølaŒ¶†6;\rQF2Ï`™Æ²@Ìî3‘o”bh•Í)fFT*`Z	-0È’†™ü™êÜ2\n…`#Xk¡±=Mêªšu:&\$(z™	ÄyÉZ5«gRpŞ»b{5NrS3¤Jc(‰zI,¾­“@o(†¤3«J|¬Ét`bÓIÌ\"„f²`:1ù\\T²\"Ò’°TIm’ŠKĞ\rPNE(Ôs_È5&•]îÊRëkNù‡\"%åÊª³TÀ";break;case"nl":$g="W2™N‚¨€ÑŒ¦³)È~\n‹†faÌO7Mæs)°Òj5ˆFS™ĞÂn2†X!ÀØo0™¦áp(ša<M§Sl¨Şe2³tŠI&”Ìç#y¼é+Nb)Ì…5!Qäò“q¦;å9¬Ô`1ÆƒQ°Üp9 &pQ¼äi3šMĞ`(¢É¤fË”ĞY;ÃM`¢¤şÃ@™ß°¹ªÈ\n,›à¦ƒ	ÚXn7ˆs±¦å©4'S’‡,:*R£	Šå5'œt)<_u¼¢ÌÄã”ÈåFÄœ¡†íöìÃ'5Æ‘¸Ã>2ããœÂvõt+CNñş6D©Ï¾ßÌG#©§U7ô~	Ê˜rš‘({S	ÎX2'ê›@m`à» cƒú9ë°Èš½OcÜ.Náãc¶™(ğ¢jğæ*ƒš°­%\n2Jç c’2DÌb’²O[Ú†JPÊ™ËĞÒa•hl8:#‚HÉ\$Ì#\"ı‰ä:À¼Œ:ô01p@,	š,' NK¿ãj»Œ¨¸Ü Œ‹ÂX—¯3; Ï\rÑˆ‹4µC”k	G›¬0 P®0Œc@éÁÀP’7MÛ\rH£Ÿ7LC`È	Êğê;ª)\\ú¥#ò•4âEOÈãÌĞA j„XBÒ~å®® Â¾ŠpBÈ«23”#éB]ˆ\"…£l¨¾ŠÍê0ã9#„¤5(TSÂ9!K™Q¶M–á¡£ÒÄ™&•\nSnÙM2¦â\\VÒj7 P\$Bhš\nb˜2–HÚ‹cV5¬”³8¢¨èËaWn`ˆ©6«”J.Ò~\"–*¸£ŒÃÑ\0Ò±F\"b>’²#`é;3(+¨–\rã0Ì/i¨¦†¸€Pª4”KÌ¦„PÜ< “ˆê1Œi æ3£`A6/C›£“èÏ*(«Ò¨”«Ğê•…˜RšæùÊ6íÊé#x3Áìc8øªc6*:Ä38@ ŒšÊö9:#@4¸Á\0xëpÌ„TèİAx^;ñrc»!¡rì3…é˜_\0:ÖÄ„A÷0¡»áà^0‡Û0ÿq\nXÙ¸‰|Œšú<ìåƒ‚º2Òê‹5ìlºo’8ğWS¸°èğ8J±Ø]¿p	ÃqW;ñÛªõÈò|¨İÊÃòÖ?í„J¨|\$©pË1\rÃ§EÒbsb7¥-Vz0¡”ËD†çÉ˜é™hÑzOÊ#ePÏ\$\"GÌ0T à‚½ğ@xÌ!F~áÉ·\$ÄJCf%g´FŒÒSL(§ŒØ’¨Óá,h¹r,5nŒKùÂc¯y2ÃŠšP \n (<f<IR=ÁˆçœpPUZø\neˆ½¬#¾O¤4Æ@” pä\n™³@p0ÂóÚ`R»*%Lë‡4Ñ]Ô&ua¸85³D‚Ş!)æÉ@³Òğ  ‘±…A…¸Sp›”‘x)… ŒO[ËZä”:‡SAÈ'nÌã†÷l¥¡6'é'(ô’¸‘Â\$h¸÷?°@µÃAMè”š„’(LÑÒo€‚-ŸäRlMš.!Ô£‚\0ÌS	ëtr=PÆKÏô&6h(P Â˜T¦¸“F2TK\nYM”Ë\\5—&¬‡%K´#¤YÍC®ËW\0r•\$lé1‚¦Şƒr=ò9ĞGã1,C5„¼˜ÀÂE ‚˜Q	’ú)œ0ÒuÂ0T‡¥l¨¾Tı¥úaa!ÈâJÕ5:Oj8Í¤\$ÑuGûPÊiŒš¤ŞOÛA5TLáóÀÂOHëû\\ÊeóÆJ^i	5lœ6¹º„ä²8AŞ\"¡Ê\$LˆT×9(Ë„j\nNÉ,jÁT*`Z{zM)•“+à1b¾‡ErqAu32µ\r!*ÅY\"±+HQü>HL©Gø¹0Ä\\skğ\r!˜<¬_%‰š“\nDvz‘bF—“º7­,½EcÌP\"b„ö¤Êˆu#áé5`˜LÁv?çìZrbwÀPPjIL¸¢ÆH‚².I¦U¢ä\nÉ‘a«Ä~µó\"Ìëé+Ra}†–ŠC@PExêê¿¨jËÌŠ²!g\noŸ¸ˆŒaµ# ()\\c˜RQS³(™€";break;case"no":$g="E9‡QÌÒk5™NCğP”\\33AAD³©¸ÜeAá\"a„ætŒÎ˜Òl‰¦\\Úu6ˆ’xéÒA%“ÇØkƒ‘ÈÊl9Æ!B)Ì…)#IÌ¦á–ZiÂ¨q£,¤@\nFC1 Ôl7AGCy´o9Læ“q„Ø\n\$›Œô¹‘„Å?6B¥%#)’Õ\nÌ³hÌZárºŒ&KĞ(‰6˜nW˜úmj4`éqƒ–e>¹ä¶\rKM7'Ğ*\\^ëw6^MÒ’a„Ï>mvò>Œät á4Â	õúç¸İjÍûŞ	ÓL‹Ôw;iñËy›`N-1¬B9{ÅSq¬Üo;Ó!G+D¤ˆa:]£Ñƒ!¼Ë¢óógY£œ8#Ã˜î´‰H¬Ö‹R>OÖÔìœ6Lb€Í¨ƒš¥)‰2,û¥\"˜èĞ8îü…ƒÈàÀ	É€ÚÀ=ë @å¦CHÈï­†LÜ	Ìè;!Nğ2¬¬Ò\n£8ò6/Ë“69k[B¶ËC\"9C{f/2¶ñ3Ä…-£°Ü\nÜÊ.|Ğ…2(¸ÜçJ'.#†Œ`ò!,ú1Oï¸5 R.4A l„ @,\nvï\r²ĞÎ¬Ê€:0/ó\0)lSÊ2BC\$2A+“Šz>ÌP*\r)²W¯«ÈÏ0°MLø¦Ö°Z‚Ôu>Œ¶J@€Ùc%›E4ÕH	¢ht)Š`PÈ¦\"ÛMs”­0»`V(áDˆŠ‚ì¼Ã1š¥ŞhÂ‹{^£(ğ\rÈ4µ\r7*…¦HF2…©˜Ø	ØòÜ#z0¹C0ÍVª¬\n :ÂdÚ:Ô Ş7„ŒòŠ¿cÆûc0ê6#ZØ9…ŒüòÛ+c\n¢O£jÙ’¡@æ¥\"¨é:ÊMì€Ø(C”ŠƒC\$–·Á\0êƒIÂÉ¡\"ã–r1àq²†!\0ĞŸÁèD4&Ã€æáxï»…Ì^À½-8^¥…ïªÚ™ËAxDpO„Àã|”˜dÀÖçdƒbv89ÁhA„ĞHÏéx@88ctÿk¥jˆåZMC• €ò¼½šmVÙ·n¦åºnÛÆô¶oƒ–ıÀ_Ø7pÊ(|\$£‚óÎhWÇBzÈÆk7Üª€¸c8Ôµlcÿ82J]¯Èòajt\\WÙK†;GMXçR£\$ûÒº£3Œ|\"˜É¢#¬9=´^ŸCfZd:²æ`Ì™¢ugĞ\0:‚dşÃ˜aEà€1œWÌKs.%Ì¦2@ìÙ¹‡„ˆE	½‡Ö¯ÀP	@ÂhP²ÈÀ(( ¤¦÷ŠYssÌt!¥¢.ÿS2el4Âu„_]ş4E@”\"lOA}EHŒš&ÒĞ/gğTŒÀàÉ@n¸pĞChA¸@(.Faklu\$¤´šoBS\nA††òSÒÅO¥°¦?0ÔL‰ƒ#1-’²KÉ‰’>&Ä’“ğè™J`odÎ9—6RÜòRèd”„’\"X±FÑT7Ÿ¢–Ÿ`¬T'ÁÄ:Ÿsœa1kÍî’˜ÒA‚ñQ\02iù¤‹½èw°H(k‘òEÙ¥Øö@f™ñš¦073’ğPå[ë'¤ıeº3†QAhkKD˜3‡S–~'ÎN&µ3AY’#„x›’\"H]&‰\naD&ÒLÍ!;iÁP(#3z^j¢|%Ò`Eö>T’ØR1eĞ¡‡3°H½\"œõ’9ÂŠÙ‹_t”øéJTz~æš‡\"³V}:(oâÓòRÑla­‰ÜiË£ /ÍdáËz6–ÊI²}Æø#¸Ê¨TÀá††PÎyC|§	ôÅ:ò3FMı0¨5´¡•:à´Ö¸K ERS«0|M²AÔ›†Á!Ìb\\'äy,¶qKùÖ<ˆâÛ\"M”Ö#lÒÕ\n}\ri®ªA¤¥›JÊJáQ&ÕÕÌˆ1Ò	üŒãö(Xõ¶¹Ò3¬‹pgT-(&pŠKU©¿.İQÊ»TFM|ç:ÁP»„¹o¬Ÿ&H¨2€";break;case"pl":$g="C=D£)Ìèeb¦Ä)ÜÒe7ÁBQpÌÌ 9‚Šæs‘„İ…›\r&³¨€Äyb âù”Úob¯\$Gs(¸M0šÎg“i„Øn0ˆ!ÆSa®`›b!ä29)ÒV%9¦Å	®Y 4Á¥°I°€0Œ†cA¨Øn8‚X1”b2„£i¦<\n!GjÇC\rÀÙ6\"™'C©¨D7™8kÌä@r2ÑFFÌï6ÆÕ§éŞZÅB’³.Æj4ˆ æ­UöˆiŒ'\nÍÊév7v;=¨ƒSF7&ã®A¥<éØ‰ŞĞçrÔèñZÊ–pÜók'“¼z\n*œÎº\0Q+—5Æ&(yÈõà7ÍÆü÷är7œ¦ÄC\rğÄ0c+D7 ©`Ş:#ØàüÁ„\09ïÈÈ©¿{–<eàò¤ m(Ü2ŒéZäüNxÊ÷! t*\nšªÃ-ò´‡«€P¨È Ï¢Ü*#‚°j3<‘Œ Pœ:±;’=Cì;ú µ#õ\0/J€9I¢š¤B8Ê7É# ä»HÈ{80¡Ã\"S4Hô6\rñºŒ,§Oc ¾ˆûÒ\$@[w8î0æ4¹nhÂº¹kãY\0cU'>ˆ Éˆ˜È“1c ÓoøÔáSõ\r:ÊàRøô¥ PHÁ iX† P¦=£[ã ô‡¨Ãb†ôŠpc\n	ƒJê:H†ù¡2ä]& PŠ§£HÙ#,Pî1È±º{f6IIˆBÊSó`+¥ÚDÃ]ƒšR)ôØËl6Ğ\\Ş7›ç^×Å´šßƒÍäA^˜\$I²½â@	¢ht)Š`T&6…ÂÛîû‹ P 7Ú…äÛ&ˆŠÊú6@J€@ü¸ê\0Å–Eƒ^9f®8ğƒÔ8ß¦ÖJİ\r“Ê‚ èN^3É>›\nq:i<İ”%Ù4PÊV–¬Sr1XäÊŒAú;ÃXŒ3›#¾r)áK«ˆ:9wŞ°2kJ¸Ë®£ÎNÃ¶–{.Ï9®ÛXçÂ[~>¤¥@nÃ«…½(;âc­¡ü¿Áì|.Í8í§ÆqÛŠZ¹¨<Ÿ*:&Ú¯I9¥pXÛv§è{÷_¹i\"	ÛõÄ÷Ä@BÉ»DûS7Nêc±\rğ±2ŠĞÈÁèD4ƒ à9‡Ax^;üu»ä?OÈÎ`^Ñ¨ ÓŸ…á|Ñ¯Q2ã|Ô(j(!Ü<†uÂZSjÊ=D=6àä	©\$Åh‚” 9Aä¥T!ôBP ,ÏQë=‡´÷óà|OË¾t¬‰Ásê}¸2³² Ïƒsõ*\0øÙ,§ÚKˆ„\rOñÿ%æÃrçr”§D.TY}Gä4(‚”A	aAŠ„¦®Â{Š Ì¼10pëˆÄ5!Ü\0	°T\r0†7äÏÊÚ\r„™ÀäÊISa„30ôÃa˜:Çµ•£Ètl2I5€ƒ‘»ÀæfÓËD¡éÊDè†(»[¨|¹Ê\$LŠ\rmè`—“f]”’èpD,˜%Î”ˆ Pè\\¹ğõšô»@·èÄ‚\rÖ'bŒQ/å1 4rğ9EP†á!Ì°5:YvA#-4o47§£™F@‚- éoƒqªsàµ Ô„L™B\r¥°9@ÒŞä|ŸR`0–È\"“1\r)¨•@CTÉ€ aL)hFİA¬€Œ0¡É\0ƒl'º^:§tÈ‘@k?	IÇ¹BrNÉé?‰D‰)\$.©h‡;ÓÍI°J!ğ×Ìè„Wœ\\iF¬Œ&&~@‹“Æ}))†ÓäMaNeçÈ7¡IIIøz\$óA¾Ì–ÁÂĞm+u7†%\$&JrÑ)U¹°†ÀêÙ^l\r'FØº‘’!AÀÔ:‘ĞÆCU‡©jÁ\"b–ÍHS\n!1Ê\n™QÃ¹¤¥*0 äÔåäá. €#@ Ø¥\"›Jpù:RÇ\0PR2(H¹½øJPÁâ#ê4<“kt¦-ì[N°5˜B|CÔæ¸ÖìÉ²ë£rÉ&ÕÄ‡‘újäÔºoVï/ûÁuã»¯Îó)»ÂdÕA6iˆ6°ÖUHı\"¥uæEN@âQÈ)Çt¤“`–bm´}*¤–”¨rL†ÊQl/„9Ÿ (ÂB F à›^{‘uíıË¶aµÍÛ¤©L}è«ú	ˆ*°¾q%µNl!)¿ˆ1j\"Æ—¸Ş™P€MdbËÀÄGPå|•§ø×›\ny)Èe—!Œ7Û’‰H \nÀK*§³XÓ‰!Ç4Æ¢z_óo0PãxzgwW§b”Œ‹'.DšÊøF³ÂxŠÚ«\0‹¡=G²R”#Áƒ¥˜-ØÔÉâ‹Ş`´uèUí&G[¢ÊŞ0ÁcÀqŒİ=G=\\ç\"i¡ˆ	³&Èê±®ÕË\"‚V¢ƒ£ôÜE&©€";break;case"pt":$g="T2›DŒÊr:OFø(J.™„0Q9†£7ˆj‘ÀŞs9°Õ§c)°@e7&‚2f4˜ÍSIÈŞ.&Ó	¸Ñ6°Ô'ƒI¶2d—ÌfsXÌl@%9§jTÒl 7Eã&Z!Î8†Ìh5\rÇQØÂz4›ÁFó‘¤Îi7M‘ZÔ»	&))„ç8&›Ì†™X\n\$›py­ò1~4× \"‘–ï^Î&ó¨€Ğa’V#'¬¨Ù2œÄHÉÔàd0ÂvfŒÎÏ¯œÎ²ÍÁÈÂâK\$ğSy¸éxáË`†\\[\rOZãôx¼»ÆNë-Ò&À¢¢ğgM”[Æ<“‹7ÏES<ªn5›çstœä›IÀˆÜ°l0Ê)\r‹T:\"m²<„#¬0æ;®ƒ\"p(.\0ÌÔC#«&©äÃ/ÈK\$a–°R ©ªª`@5(LÃ4œcÈš)ÈÒ6Qº`7\r*Cd8\$­«õ¡jCŒ‹CjPå§ã”r!/\nê¹\nN ÊãŒ¯ˆÊñ%l„nç1‰ˆÂë/«Àì¡=mÂp\"±m¤—1A8ë#2Jèò%\r;ÒçJÎ0š€®“ë”‚2iôr'\rã²3.³í¢2ò„!-1M!( òØOĞ¡xHÕAˆ(&ÃëBC‹†6VÈ8@6\rìrö'S;&=ˆH ÍˆÅ\0×ŒkŠòã¡jx4¯b\$¾ ¤«œ¹#r¹(©JVãSÉ=¦“%	Tl›Ó´Â0Ú•Ò•ÈIÀÂ3®‘È\$Bhš\nb˜2xÚ6…âØÃ…Œ\"í–2Ù®k”ÜHR‹2ßÀê‚\\–¥ÂÙC8Ò¹vsXŠó\rÑ´©d7ŒÃ3×o'š•IIÊ6½©k&; Ş '£Ì@É£ÃªM^.ìXÙZUïx?«ÂŸLhtö2…˜Sœ¶).„[l‚oŒ§iºrÓ¿\rÍä£pÎ#&Âƒêû‹% ×Aâ43£0z\r è8aĞ^üÈ]pp\nè3…é@^¤/.{š„A÷HÜ¼áà^0‡ÛFˆË«ƒ‚k·êğ|5c”\$µT“ÃmŠA\nX+÷lÎÂÁÓq¼#Éò¼¿2;ó|ğƒ…ÜÿB7t#ÆIgu\n€|\$£ƒ_'9İwa„pèŸÓ\r§â0p8ÓH›jzJ¡8[M´¢„É!6&éòÜÉÂ¡3 aÓĞîµ	1„#¤½¶Ó¨@Ã3Åm5º5¦Ú¨ ‚ïÌİ sê“ˆ+Ì€¬62r,eÃB1dd¡µ œ Q	7ÉñCÈl‚€H\n5˜(†AP\$œ¯#Èò@\$±Õ½9³5fµj1M‘½?H0½+Àîu	Ä ïèÙ(ÀàH0 kÏÍıààŞ2B°¨Ş'â]œŠ™@ñğ‚†<cˆo'ô!…0¤£Ñ)N	›‚Âº.µ©ÂâğIÜ\0’\0((œ4ĞÅ9A(e! súHüm¯îNDBb×¨ Cµ\r©#¤ˆeS'\$‰“H‚\n€¹ú›ÃdgUÉ^CDØ8;OècWˆ}ù›ØîåXP	áL*\"4¦Éã%]`€3¥i©\$]Ô…’ã|O	ñ™ò9ö¨„MSi-Éäæâ`Õšk:æ]²ùcé¶WˆØ©’ì˜Q	ˆj2‚Ô0T‰dõLX›`f›9D”¡™~Ä”ö’N)É ~&)4˜êNõ:\rìhî™J¹_Ù›!™1®°\\»i„<„Uz€ †xƒ`+œA¥\\Òb‚›¨b3³ø·+Â[Ò±'N¡„«ÄXkßŠAi¯P¨h8\$á¹¸˜¶üN)R¤f—Hœ»,MI4¦\\…×\r‹éNGŒ–-ÀE˜³”X 'F6#“bÃ0y`l@Î‡2s’ú]´©±‘BjQÆ\n©ğ=35v^]C·:·’Â^›a,'07ÑÂZÍÁ‚e6Ã€ Pšª9bé–”T¸lZ•9¤×©k#bêÅŠ©DQ,Â*ò¼Yç¤<DÓİe`b¯6*Ù‹’Ö).KÉ²´fŠ`;KR,Y¡Í\\¢\$İ&éø";break;case"pt-br":$g="V7˜Øj¡ĞÊmÌ§(1èÂ?	EÃ30€æ\n'0Ôfñ\rR 8Îg6´ìe6¦ã±¤ÂrG%ç©¤ìoŠ†i„ÜhXjÁ¤Û2LSI´pá6šN†šLv>%9§\$\\Ön 7F£†Z)Î\r9†Ìh5\rÇQØÂz4›ÁFó‘¤Îi7M‘‹ªË„&)A„ç9\"™*RğQ\$Üs…šNXHŞÓfƒˆF[ı˜å\"œ–MçQ Ã'°S¯²ÓfÊs‚Ç§!†\r4gà¸½¬ä§‚»føæÎLªo7TÍÇY|«%Š7RA\\¾i”A€Ì_f³¦Ÿ·¯ÀÁDIA—›\$äóĞQTç”*›fãyÜÜ•M8äœˆóÇ;ÊKnØˆ³v¡‰9ëàÈœŠà@35ğĞêÌªz7­ÂÈƒ2æk«\nÚº¦„R†Ï43Êô¢â Ò· Ê30\n¢D%\rĞæ:¨kêôŒ—Cj‘=p3œ C!0Jò\nC,|ã+æ÷/²³â•ªr\0Æ0˜e;\nÀÊØª,ƒ®¾>²<´½\ni[\\®ŒªÍ‰ƒzşÿ©ã’z7M*07«òëJò¯A(ÈCÊÒÔÅ4ÛCÍ@şA j„PBƒN1´š0I¢\rˆ	ã”|ÀĞŠ2¥G3jÄí½£ó`Pjz4°o` ¤c«¢½4`ñ(P)k)N ã\r¡\rãJT–%ÊH]NR÷\r ÜËâysÛÊK#=ä	@t&‰¡Ğ¦)C È£h^-Œ8hÂ.Ù\r­”è:­íìõ³î#¼¬&)‚Wä5DéXçeŞâc>Å\"ãcÊ5Mb^ï\rã0Í9Ñ2–©.UÄÍŠƒz‚Ÿ1.:ŒhRF3©MsCab\n9h·ÍÄÿ°\r.¨ºh0P9….4ØV·´ÃC#¡L“zœŠsõxCÙ8Ü3„É®!zÆèdñò`-kF3¡Ğ:ƒ€æáxïÇ…ÃîÀ!arø3…éX^¤°*› „A÷8ß=!à^0‡ÙÒFÂ¬ƒƒ5É:”!\rÃ©†:Œ¡l@ş.Òs¢2§\"kƒ&„'\n×Ì7\\ÑÂáÁGü/Äñ|o;ò<òüÈİÌFOÏ„JÀ|\$£ƒkß7Q·pè Rã¦†5»Ê”‰E'ÓCŒnËá¿p&aÕ¢´zM·=æ´Å­&º‰Ğh;Á„1¹ãşÖ)1íx¨¤¼fxˆ9¤›pæÓr†k0aùœ¼}Òq	y‰ —ÅîFLèhFl”Ë¬²0Ÿ‘D¦ñ\"¤@PAÈ@ÅD0PVI*,ÈÌÉ2BXI1('!è(È*zMÉ 6k@Û)x˜nüA¦	\\‡t¼Û ‘£;æâ¼bwÏûó!¸8Tñ‰ÈRáÜà†0Ğ\nˆgpíšÂ‡Ş:\rïàÿ„0¦‚4xN\"…9® @·Iqã\\Š)¦§³bïTi9\n'Pè§D}\"\nEDr½5¾”†¢J‰S^Æ´¤#É0C„Ü˜H÷ØÆ×¸I\"¡äÕ¢B¨ƒ±+~§Ü5lYÙ§!\rÙ¼ôÃ¹DÌá<`Ï\$B€O\naQ%\$|OY;’#*—€èd¤8˜œ5P	‹2DJ6LãFTË²\$;Íì7\$\0Î¬Sbn3¨µ˜LÔÖÍÚ¹rT1à@ÂˆLCq-à@‚¤J'Ê\\£DÃ¾ƒC4Úš!È“ 5y>\r#+4ë‘s#ãkIÅ7RTè/Ìo¢s!Ğ@'Ó…&iØåF…!•:°ÈER„'ºy.º®ø¥ó«u{„3Ê\\á\r*Ø‹ğÅˆávW/—¸¼dÁj¹'!’Ô>ƒ`ÛY\n¡P#Ğp¹ÛÙ’nuYKš:ŸQW)ß–Ä2®®ÃENM2Cƒ«Î–Àå\rÌ)\$ˆ’E’Rf’Ë:®˜ÀØ•‚ho2A˜<°PÊÍs!Nı.Õ€Uw=çÔ©Ô¦h®\0k:ÆNº%YlƒJj:°(ïW\"øNT-(ñÈŞ˜ vlQê\n\rC¥a†.™qF/Iu#²éäsßM?R¶6Ì)K-cª!O¬Hƒ@ÕÊÌ¡â&GÑLØBCé¬˜1d†`MÅ­1¨äÅ˜Û{cÈ¸EjÙ§åU@";break;case"ro":$g="S:›†VBlÒ 9šLçS¡ˆƒÁBQpÌÍ¢	´@p:\$\"¸Üc‡œŒf˜ÒÈLšL§#©²>e„LÎÓ1p(/˜Ìæ¢i„ğiL†ÓIÌ@-	NdùéÆe9%´	‘È@n™hõ˜|ôX\nFC1 Ôl7AFsy°o9B&ã\rÙ†7FÔ°É82`uøÙÎZ:LFSa–zE2`xHx(’n9ÌÌ¹Äg’If;ÌÌÓ=,›ãfƒî¾oŞNÆœ©° :n§N,èh¦ğ2YYéNû;Ò¹ÆÎê ˜AÌføìë×2ær'-KŸ£ë û!†{Ğù:<íÙ¸Î\nd& g-ğ(˜¤0`P‚ŞŒ Pª7\rcpŞ;°)˜ä¼'¢#É-@2\ríü­1Ã€à¼+C„*9ëÀÈˆË¨Ş„ ¨:Ã/a6¡îÂò2¡Ä´J©E\nâ„›,Jhèë°ãPÂ¿#Jh¼ÂéÂV9#÷ŠƒJA(0ñèŞ\r,+‚¼´Ñ¡9P“\"õ òøÚ.ÒÈàÁ/q¸) „ÛÊ#Œ£xÚ2lÊÊ1	ÂC0LKÂ0£q6%ŠÃ3¼ÌA²ïñAÊ2õÅSb„nºŒ,ò93¢`Ş3ÆéÀà™¨£pÊ3«û@Ö+©Äï´¡(È\ròµ\0×Ö…CÊ°A@PH…Á gh† P†‡¤5jî¼ ,;¥[Oú˜:@CZ€À¢	a:\"€Ş˜Mw]î\r)CJ\"'€(VtêŸHO\"8È¦T°¼Üp(‚–Ål†äØª+x\"\n63a®b/pØ¡x*£h\$	Ğš&‡B˜¦‘8Ú6…ÂØó›\"ë¥y=èˆ¦¹iÂ¥`+Õ‚¨©ÔV‡ÖhK¸9§¯ú³”Ø£um€3ÃböËgc’w4	(\"bòB ŞÜV#ÌdĞ£®9c2†6Uo€YmÃ\rYÆ8‹¼…˜RªÅ¡eKO¹+	è¨ÚA\nv0¯¨Kì*^¾9onÜzÃ«ÁâX4<ƒ0z\r è8aĞ^ı¨\\¥)•ä/8_Y…ğÂù/»xDxLRö3‡xÂrJDàœ¡(|GCÀá'1[Ü3¡¸ìëôC,¬0ñ,Ë]§¢l*ı£ñJ9Wë¶òDáLu/Tës°vNÑÛ;‡>ïã¿\rÎü<#ÆÁRÁ%7™Tş@kÏi@€Ş¯Î	í\rdàÍpä¬WéÍ+¥ë#•Cu&O„õå\\^qšlLœ%Wˆ`C¹¼&ÆN9tş¯Ãf~9È7FìM›ÀgF1ãœ\0ĞN™JOá~†’úÔ\r2:jof(Â¤¡é»2FğëÃÆAÍÃLG±`á®.ARpëXî+\$¬òÎ¸èŞS|¯Ğør…8ä¡²8Àw<.J+°#’Kš\"*î(ÀœxH‰!~H˜sÃAD!N´Á‰<ãTa¡'¡)…  azH¬4†&&Â)é†Bt–™IIC(¥%ÒJ­Íj\\)¨~ËärKÁY`gÀÜ‹Ãè Í „’.M’\r*şGİAN<Ä:©\"ZƒÈów\$Î++7Ş±Ç9(ˆß>Ò°CQC\n<)…HÄbä£=§’n…F@ÜK#áÕ«\$.Á”YLÅåÄ¨è´¿‰³¢%Ä-*\"ĞÊAÌÉääíÆ±,	Ão)ìKçÀÂˆL+hæ5\0Œî)7¢Vƒ4ø'-ˆ\0¥ly\"‘YJŒ\nl1•àhÃs,µXÀU„Váë›S=ªÇUä5gU¡…¡ÕÙ´|	™Š¬l.l• ÛÃI.%qİbÌóW4L³bìf¿™›H,)ªWºY¨Ø\nÃ^a¬›¬\0@‹èokJ½Sµ›=cl\nõ˜äı†×2V¢\"1\n¡P#Ğp‰=€>Äõb‡)¯åÜŠ1LRŞÛöCS\$I‘ dôÂ=(I¡2¯˜·ÔrŒ™v	¶\0003–`V\reàFªm<)Z@Qá3Kh+ZGhŒY.(—rÕGwG(Jc(pÄà–\0Ù\0Âv¢Áñ„.Ú¢WõıUJªŠSË_mù:€¢j›•+j+G=asMHš¿°ÖLò,LEd¦è]WL†E¬Ş‘Ñ¯iê›\"ŒèCQ‘Şgİ…ÚL‘”¬“@®Şƒ1\$€";break;case"ru":$g="ĞI4QbŠ\r ²h-Z(KA{‚„¢á™˜@s4°˜\$hĞX4móEÑFyAg‚ÊÚ†Š\nQBKW2)RöA@Âapz\0]NKWRi›Ay-]Ê!Ğ&‚æ	­èp¤CE#©¢êµyl²Ÿ\n@N'R)û‰\0”	Nd*;AEJ’K¤–©îF°Ç\$ĞVŠ&…'AAæ0¤@\nFC1 Ôl7c+ü&\"IšIĞ·˜ü>Ä¹Œ¤¥K,q¡Ï´Í.ÄÈu’9¢ê †ì¼LÒ¾¢,&²NsDšM‘‘˜ŞŞe!_Ìé‹Z­ÕG*„r;i¬«9Xƒàpdû‘‘÷'ËŒ6ky«}÷VÍì\nêP¤¢†Ø»N’3\0\$¤,°:)ºfó(nB>ä\$e´\n›«mz”û¸ËËÃ!0<=›–”ÁìS<¡lP…*ôEÁióä¦–°;î´(P1 W¥j¡tæ¬EŒºˆkè–!S<Ÿ9DzT’‘\nkX]\$ª¾¬§ğÔÙ¶«jó4Ôy>û¬ì¹N:D”.¤Â˜ìÂŠ’´ƒ1Ü§\r=ÉT–‚>Î+h²<FŒ«Æï¢¬¹.¥\"Ö]£¦„-1°d\nÃ¾å“š¿î\\İ,Êîú3ˆ¡:Mäbd÷¤ÚØî5Ní(+ú2JU­ÌüğC%á¢GÖê#šë\nÇTñæšä,ôóµ`#HkÎ–ÅµJÀäLjm})TëÊ£U%•c”Ä»ŠÀú7“\$ÛqNË€î8N\$@#\$Â_Ì“­ÉW(mÔŒ“õlİqµ/Ä8Œ“Îu±\\¥ÀY(¥\\³É75øëÜ-˜ŒZtš9D¾¿Y.Bh5™C÷Ø%„’ÆA jpà‹ËBá£8¤ŞGe‹Éx•Z,ôrhA	…7<2A MÛĞ-ƒXaÖÎ–€È²<|V¤AuúhïÖšÔHjŸ™ú†ú)hc¸ìª*ûdªR‰Êû7‘yêKZâ™ Hª‰})¾ö¼YWÛkVìãÕR_ÀOÂ¯pÅ(“c¬%,òÜ\"ÅÃqÜø!³ïAÈ;jrØÏ6+ÄŒÖe8th)\$çw\0(Ê>ZÊwd-¯EŸ’.È™f¼æƒHæ0ŒC`Ê¸a\0Ş9(İêzŞÇµî.^èğ:{Ã˜Ò7Ã›ÿĞ¨5\06ƒ”ˆÛW‹P‘¾oåxhĞ“ïG‰4…¼ÔòqQ¼PÇ\r£4…¶^œ:b&&Á²¸NB¥>ç ]?ôÊ,m/+\\ä\$dğŠaî9\$b£Ã¸-Üt>„=KaåZaüiah@èTò Š6‚jµb¦ú©È8py	%’TÁ`ÊyĞt1XTxaaE!°ÁĞøfa	°‹‡%XxS¡ñyˆDH,˜UDeñ,Å@½b„HMĞ˜ªÂ‚ƒÉü…Ä®¼h¿\rK«\\#©Ì¡%Tû d‡Å1.…÷\rÑl’’ÃÖÏßÚv`ú&0‚hi\rÁ”9Å`Ê“ZGD×ƒY‹Ê=Ç8›ƒÀÂ@r¡˜‚ Ğ p`è‚ğï3Ápa•ªVç¶Á{Şá‘öèúÃp/@úH)6œ°JWàğ†|§Tù*#í`Ï\"ÕøvåM	ú^ñu+Š™—EîÊ3ÀÂ)àb²UµRêRT*è!`µp®õı?A^8mŠ_L	…1&4È™S2gM	¥*eXršÁÊlM ÊùßLàœEÊr/2 SS¼}]²w@ãiÄ®l‘)#2™<ËbçAÇr5Á†IDy`4ÎÉeO“é>ĞÒRE„ÕÄ“¥AÒÁQÒ°—Uq=Ê8«±´ë)§ô¸“©ÎJ)b‚vJä©ªj;\n%qÜWÂ †ÔŠTœ‹…§Ş©ÅéäK[¹b‹ÅÔêTIÔ:ªÄêÕ™xAÌ>iìÒÆªV™nS©uÈI ğâÒh6\n\$›PP\\Iu\$~°ÇQİƒ±è¦­—<òêæªEQ£Ôá\0~£Bb±ñF»@ÂB²É8‘4&¦hsdÓ5É9¡öÀªs.N­j‹yn\"Ä)²ìGYC‚CSÌõ@¬©U2¬Â˜RÀ¶%%ƒåXkª=Ú*rIHeƒ%HÀ¯(BWY	>*A¸HJYA>­mB¥ºK…d·\n¸_+ÈbbÅ£6‚7Äw<.a£(‹¤í6Q”a¥1v˜¥L›'tôŞ}ÔID*“×¿§é	)éD¬ M¸Ğƒêrì\$¼D§hCPèv£4\r“Š¦œ‚¬{ˆŠm@€(ğ¦(¡Â²&Ò< «¢«Æ>LEC–<à\"¨ÕÍÏXÔ¦®Òcw`V>OTU­lZ`aÈ!äç\r˜KP!Šnšù¼¨ƒ†²+sØ‘\$\n‰Í rän©H Ö/ìÚXÂ˜Q	˜½Ry\0F\nA]v/•Šztœ÷\$äß\n™Aø!¬Q.K]'%1¹ä¡µ¶ÃE–ºÄoÎ×;‰ò¼ì–zÔÒ+s®µ,‘—MRr´¸FûBå=²œ+˜«	/AWM¿w?ã{ºŠqÁQ³Ÿe{ùZ_W[êÒ&Š¡\rú†ÀV4MüX°q¤D’»ÄrıÊE€å\\L#ªeu	Á\róÄ¸ŒQª>é–Å&Cªè‹¾\r\n3êX¤B00 \n¡Sƒ„âñ¶UÏiò–CÄ^RÄ rËëd¯t9Ñì{dİ^8q×.w_j…Ãö:2§ïµuKÅ±¿ éB¥–*.±é}¼o›~àVeo£A}o©Ô“¢‘¨³§—¶s.Òª‰Wà)¯^Õ(Ú—…çë'aâeŠyujxÚ¹Æ’h,‘((%ª\0@0…_,ş”!w¸		/¹ä–w¢„ŒD{¹e‰¥î¢\\L=åÖGZ_°›Ò@fğËŒ4ªÿÉ1¶í]9\\gé´vå¥ÙEƒ*õ3²‹ÅÙ\0*‰1Õ6–&»¤êízÌŒ¶CÚDùê{üÂ%†)‹†b‹ 7lÈU¡\nm/\0¦Ø/¾h°š‡¸ºK^LÂ†¶DÔ<¬dÌN%K†e¢d[­ˆÆŠ…åÜŠEÔ•Ê:˜)†˜©™)–™©é¢šjR¥jZœ à æ`î{`È¦`æœjÍ\"ÊÍ6^çŒ9M@ªêxà";break;case"sk":$g="N0›ÏFPü%ÌÂ˜(¦Ã]ç(a„@n2œ\ræC	ÈÒl7ÅÌ&ƒ‘…Š¥‰¦Á¤ÚÃP›\rÑhÑØŞl2›¦±•ˆ¾5›ÎrxdB\$r:ˆ\rFQ\0”æB”Ãâ18¹”Ë-9´¹H€0Œ†cA¨Øn8‚)èÉDÍ&sLêb\nb¯M&}0èa1gæ³Ì¤«k02pQZ@Å_bÔ·‹Õò0 _0’’É¾’hÄÓ\rÒY§83™Nb¤„êp/ÆƒN®şbœa±ùaWw’M\ræ¹+o;I”³ÁCv˜Í\0­ñ¿!À‹·ôF\"<Âlb¨XjØv&êg¦0•ì<šñ§“—zn5èÎæá”ä9\"iHˆ0¶ãæ¦ƒ{T‹ã¢×£C”8@Ã˜î‰Œ‰H¡\0oÚ>ód¥«z’=\nÜ1¹HÊ5©£š¢£*Š»j­+€P¤2¤ï`Æ2ºŒƒÆä¶Iøæ5˜eKX<Èbæ6 Pˆ˜+Pú,ã@ÀP„º¦’à)ÅÌ`2ãhÊ:3 PœŒÊƒ¢u%4£D¨9¹Ç9¥Ò9ˆ£ ÒÖ@P ÏHElˆŸÀPÕ\$2=;&›€9Ê¢ ää’HA:Ó¥7EÓs£MØ×*„£ @1 ƒ VÕõˆóYÕÕ€ÔÖÀPòÕMÁpHXÁ‹æ4'ëã”\rc\$^7§éëä-A¨ôJÉBb]AB×=Ê¢´™)Yâ(Zõ£àPŸÒÃ,ãFRQ,Ô:RO@4I×z…*1‚n#wøÂªm\\2İc\n>8Ø4à—„©9áOî°¸Šnà²a—®–3†]‰\0ì”¶I(õG	Ğš&‡B˜¦C#h\\-¿ïøºùBˆ2K¨ˆÚ°	Äc‚\"‰Î’¦)Úf–«¶Cu,7êÉM°=&clœ6M€S:¤£ª`Ş3Øš0¨¿ÉíwÒ•¸*\réÖ7!\0ë«£~9c0ê6	é«`å¼Œ#>ş.¸ÈÚº­P9…)NáPÔsâÖ2ŒC,ìÔúäÍ©H¨Ğ?\rXÌª´°Ü3„ÉÉ£—jÃM¤Šˆ²H2ŒÁèD4ƒ à9‡Ax^;úrùÜ?Ár&3…éÈ_¦c Ó¬á}î@8xŒ!öÙÀ{ïàí&O¾0ñ5+Óü9äòJIYY?Ê}\n¡päÂÃÀp\r!É‚à@ğAÃx¯ä¼·šóŞ‹Ó.¯Uë½ÜöCÃUjğ|à|Chp'	¨7GÎúZZ÷\rlÖ7`ÂÊbƒSçù»“èúáú°±Œ×b^Õxy(„Ô›“”’êƒALOOy¬\0îGÜ#¡‡aÉ×‡\$Ô«ƒfe¨=¿§àÜ(opñYA†ƒYHªj-(Z^Ãb22…ì¾™‚xOT‰\0''Â–Z`ë™b˜ŠĞdB)å„2d\0¤\0­GŒ‚œæ@PCk`1½çg\r_Ñ¦#á”Ôªä\"\rYµAˆ8ï’\0ï@S«?ĞäÕ‘`æ…ø r!Ò8Pàå`#€€Ê¸;›FLäÃ;ÈUæÌ¦0ÂìC))Mğ©0ˆkàC\naH#@åüxXš#‹±P‘GürIKˆGjØœÂOY-%äÅ¼:cŒšİ16'V7*“Ç‚ÑĞ§…–²„ÿ\nq)	\$,<™éï¥™'*ºbK(C©ªAÁ™\0!÷lõ¤Ä'1Ì xß,¡©CE°V±3ÂObHIA@'…0©\"-…‡…A †ŠpIÁ´*†Èf[ĞiIEA˜¹‡S‹=ƒU¬:\$6óD[šÇğ×’øpUysL(„ÀAUQŠ\"Á*‚•q.•ó\n‘ÒZ4¦Şª†cæAUªònvCÉë eÙ\rX2€Eˆ’T85p:ğÒ¤¡0±ª¨0µ\"vMÜN\$¥Z*“¬dQ}†\$;B òˆÅ%!\r6ÀVØ™ù¨¤‰Ó)ÑZqŠ‰«„MO\$~ˆÃÔ,2D¤#J°AŠÌÓ˜Gğ*…@ŒAÁıEdlóÏ^ˆš¼dl%Z¢Ú×Xƒb—	Y”•yo8ak×¥Ì(SxlË%×ÀPµËärQ€0E\0¤¿’1\$Hét‹œV©ƒnàfIÖ`¬šòà¤À¥\$i8×’ScÜ……\$Ù+²CSÍto\$h:tFFÕZ(Ã§˜“’˜ë8m\"h!%\\n_9M„Àü%R4”Ñ+”+É’ÆI’IòV†g(«ƒ1&VÊ…^…5äe*%A,3’c,	¼ *Jõ–³HãC¨î”Ûà4(N”¹ÀF\r*…,°“Œ>8Øğ¦²‚";break;case"sl":$g="S:D‘–ib#L&ãHü%ÌÂ˜(6›à¦Ñ¸Âl7±WÆ“¡¤@d0\rğY”]0šÆXI¨Â ™›\r&³yÌé'”ÊÌ²Ñª%9¥äJ²nnÌSé‰†^ #!˜Ğj6 ¨!„ôn7‚£F“9¦<l‹I†”Ù/*ÁL†QZ¨v¾¤Çc”øÒc—–MçQ Ã3›àg#N\0Øe3™Nb	P€êp”@s†ƒNnæbËËÊfƒ”.ù«ÖÃèé†Pl5MBÖz67Q ­†»fnœ_îT9÷n3‚‰'£QŠ¡¾Œ§©Ø(ªp]/…Sq®ĞwäNG(Õ.St0œàFC~k#?9çü)ùÃâ9èĞÈ—Š`æ4¡c<ı¼MÊ¨é¸Ş2\$ğšRÁ÷%Jp@©*‰²^Á;ô1!¸Ö¹\r#‚øb”,0J`è:£¢øBÜ0H`& ©„#Œ£xÚ2ƒ’!\"éÊl;*›1¥Îó~2Èú5ÄÏP4ÅL”2R@æP(Ó)¤Ï*5£R<ÉÍì|h'\rğÊ2Œ’Xè‡Âƒb:!-CŒ4M5\$´pACRè<³@RĞ°\\”øbé:èJø5¨Ã’x8ˆÒK:Bd’F‚ Êà(Î“¨õ/‚(Z6Œ#Jà'Œ€P´ÛK‘¤Üğ<³@ ”-ÂùgÍhZŠ£Âƒ-`®àM¨6!iº©\r[6İ„«[ÉíÀÙl•[V„4…×Mª†\r½Éx\\É\0ì—I@Ô	@t&‰¡Ğ¦)PÚo[Ö.×K«(ÂÃ¢[´«£/\r3hÕà5\n>B9d€ğ€Ğ8ß–µNÚ<6¯ƒdš1Ld€¨ŒÃ2t¨5RhË#;¶\$àŠM ŞÉXpòÏe£¨Ç,c˜Ì:@¶å°T›§Œ0‹hÚ]ù Ü: !@æÀŒ¤¦›£˜á¡”(ò^*1Ï+63d[‹ 4\nÜõAC_ŒP@&í`Ê3¡Ğ:ƒ€æáxïÍ…Í¶ÌõÈĞÎÁá{è\$¹x^İ:RèxÂf)µôğ%—\0\\@©ì7ÁVdú´iÄà€¥âkâ€åö«\0VÈñ/]ßÆ ¼‡%ÊrÜÇ5ÎsÜåĞ]J2åp~\\7uj@|\$£ƒ/%Ã§cÙå4Ñ	³š`ÂQ	!=M5‡D„Ğa¼\\JÜŒ&I	2J\ráœ7”U“˜h A„1º“ÖY.°97Àä’Ğ aÏ0Š5&¨ÕšÃZ‚fÒEó\rˆs),2õCId¼!Sz}\r\n\"ÅI¡ô(x!\$«æ\$!tûàH\n\0€€RGI2wÁ¼Üò^ÙyPƒ­üÎs,f?†lÒŸp@GÉ\0wP\rŞ\rSff×şKÑ\"ÜA?ïA\nsH×Ë4îMI(qCqK)D»%8šJ‰iÈaL)gv³ÎºÒ4P˜6µ„€¹Aá¬ÖÀb`L‰ (ÒÀP£%„d¤c7<4¯“Á0İ¹>D‰ÆgxûÁO²åC²b^H˜y1dœ4¡BĞ~z†ÑÔ‚‡êf‰øf=„qÀ¹öQ@c\$ÈÑÇSüf[j\\.ÄìÉE¬†CÑ7\n<)…Gvšhj:óV^Kâ@Æ\"LW#ä˜ğ4v¤9HÊ,!¶q6ÃÖ3ä€ÛGxÁ\0S\n!0e„@Œ7ÁR-´(‚Ù?€3©%4€äLßm/J	}“¨e!ĞnŸ*&;rz„Ê”Ã©U	@(\"¾ƒ±ªF©?¿BŒr%ÚïOõ2\0§WP}_\\EÍ}Ö5º+9­3Š.èŸPÒbô¬Õ àÌEóXk½UI‰ö3¤ĞØ\nÃY:\rfÔœ¢ĞO¢Àu7ä,£ğKÁ­²ŒÚP¨h8dn½†àÎ^#ÌNöÆ˜jB½B­©ÕÑq®ó	k«Õ±\$6Í*®Õ÷mÉ*X¶Ê¿/«lan\n#¤lÙÔw^g¡vM5	†ÌK!¶&0TF¦€¤] âoh¬Y%\0xÑ9\$ W™\\**vÌy \$R®–ëÚg)…'¤”—„ÀßIHÌ~4(Òÿc qiXÆØà£@†]­M`¹¨;k”n¸!¥I\\píÈ0çéAÕ\0‚Y !ŠÀéÆ4Kn‘(IT·vˆ!{Üd=à\r	Îè®ûˆƒÄ¯ˆ°8\0";break;case"sr":$g="ĞJ4‚í ¸4P-Ak	@ÁÚ6Š\r¢€h/`ãğP”\\33`¦‚†h¦¡ĞE¤¢¾†Cš©\\fÑLJâ°¦‚şe_¤‰ÙDåeh¦àRÆ‚ù ·hQæ	™”jQŸÍĞñ*µ1a1˜CV³9Ôæ%9¨P	u6ccšUãPùíº/œAèBÀPÀb2£a¸às\$_ÅàTù²úI0Œ.\"uÌZîH‘™-á0ÕƒAcYXZç5åV\$Q´4«YŒiq—ÌÂc9m:¡MçQ Âv2ˆ\rÆñÀäi;M†S9”æ :q§!„éÁ:\r<ó¡„ÅËµÉ«èx­b¾˜’xš>Dšq„M«÷|];Ù´RT‰R×Ò”=q0ø!/kVÖ è‚NÚ)\nSü)·ãHÜ3¤<Å‰ÓšÚÆ¨2EÒH•2	»è×Š£pÖáãp@2CŞ9(B#¬ï#›‚2\rîs„7‰¦8Frác¼f2-dâš“²EâšD°ÌN·¡+1 –³¥ê§ˆ\"¬…&,ën² kBÖ€«ëÂÅ4 Š;XM ‰ò`ú&	Épµ”I‘u2QÜÈ§sÖ²>èk%;+\ry H±SÊI6!,¥ª,RÆÕ¶”ÆŒ#Lq NSFl\$„šd§@ä0¼–\ne3ÔóšjÚ±Š”µ”tÓô‰6ï]*ĞªÒÈ_>\rR’)Jt@›.)!?W§35PhLSÎùN¶£ëk¨²—@[öˆJ2 ŒûÎ†7=Ò¢Ì·mĞÏ^	{Ì’K‰\"æ¨\\wøbµÚoÍ\\Œ3¥®Ï²J	…%¯ñ OĞjCöóÕ6ÒmÖ¹ ™š8ì3jÂ¬c:ÏµŒŠHJ ¡tê·*HOKu“æ“¶Ö”1œ1”v(˜Cjú¨×İË« ò®(\"Æ]åÚ45,/+¤ì Ój^Y~ö¦‹¦—¦êyŠÄˆ\"ºÖ¨¶–ºÆ‹©B–Ÿì† ³lÈª°(Iã:ZB@	¢ht)Š`PÉ&\r£h\\-<hò.´Y5é”Ód¸˜ë Pˆí»ÎX@´œ^7s®AÑt(ğëÃ˜Ò7õ«Z•+-àPØ:MËv#“‚7ŒÃ0Ù	¯\nŒNH¾g-Ö¸¨7¸ƒhÂ7!\0ëÖ£Æçc0ê6`Ş3ÂC˜Xè^¨Â3Œ0A÷]Ãl\$:ºá@æ×‰â|ÖCjì-i·(APß\"£uuÈLÈüƒr0|áØ:àÈŒ\0<&õß`zƒ@tÀ9ƒ ^Ã¼)Á†¼DhŒÃ8/¡¸£ÇH|4@úd\$Áà/ øµ±t4¸È‰¬\",q·DL[Ë#U|äõo-d2ØÉãRIê„¬QÉ×G\$\$ äºPp\r.ù&A˜6 ì„0ÂxRá\\-‚0¼9Cg\\3uÎÀ‚\"Ğ‚Hm,6Ã0ébŸ9ò4ì†õĞtAÒ\rg4¤d`ô ˆn‰5Šµbè«y\"ÎÌ˜Ÿ,Eâ:d¨8²O\"ciPà†Ç€ ç)ğ#‚„R5tÍ’Ú{yğ>'È‹¦Ù: à‡0Ã#A\0c’84†ØËY˜Cå10Ó:´FD—)lÅ¥çşÖaTét›ˆP@@P“Ñ«NöŞ¡¢qµP”Ÿ%Ò„İ„—ğüèCŒrQÌ«¡\" èsÎÚ?H3™ñz7\0æã¾}ç>\r‡4ößz.;2p7éšIIsí0Ñ&CHg„ ‚cM³‚Ã\nxªÈ±«D‚BS\nA)¿öšÁK²‘\\š¨È¸TˆH„b¢ğŠ°EJiJú#)dšÖ†AÖQ Æ¹t©ß=t]%P±¨iüÓŠ¡¡ ‰8§d¢JÂ¶]m5\0 ’HCË»¥t4‚ƒt—;G=ßês’fFA¶Ç¨1LÑpc|HêmÒsk1³±)ADOğÂ¤øræp™´Û¶’ÅAU ”“vÆÑ¨\"e&k&ÃO‡’R¬Y5J”–ªUJp½5ˆÉJ«€È¢Ú·&öÉ?¨«\$Šº7•!7ÅåD¨ €)…˜5&\r„`©@šè\r2)\"¾ûChÉ08ĞôÜ¢ÒZŠÉ*ÅW‘øn	.ND‰Cøi°À¡ÃEÅĞaÅqnÚ¹]‘kaœ£Q0?uõ«µœXVëd–w#«ühÚq±ôÅ¨'cİŒÌmw°Å\$.“oºvÊê—b-y’ÃÕö²€ ~ƒl\nH5÷P¨h8tpRÉ¡2„»®¥l!ç•9*bªÚ±^T‰\r—:%j|³Á²&JàÛç+sú¡-eºR*Šö1l¢³™½&OÈ¬_’Á¤3“][Úğ•k,h3LªĞæZYXÑG\"L++S‰™l¬›I²3¬×tÊzÆ®Döj=\\ZÆu)–n§rhØoacUƒX<õZíM»h;Š¬F€¦Uz	Qv™Hf*Û‰og¡a¹uââV!L2œ¹FÔJ\0ô\r,®…¹¨‹¥=0f4©åsÿ-¸ç>[ÆPIö\"×O¬œĞAAºW¸ĞW¹éD‘!~";break;case"sv":$g="ÃB„C¨€æÃRÌ§!ø(J.™ À¢!”è 3°Ô°#I¸èeL†A²Dd0ˆ§€€Ìi6MÂàQ!†¶3œÎ’“¤ÀÙ:¥3£yÊbkB BS™\nhF˜L¥ÑÓqÌAÍ€¡€Äd3\rFÃqÀät7›ATSI:a6‰&ã<ğÂb2›&')¡HÊd¶ÂÌ7#q˜ßuÂ]D).hD‚š1Ë¤¤àr4ª6è\\ºo0\"ò³„¢?ŒÔ¡îñz™M\nggµÌf‰uéRh¤<#•ÿŒmõ­äw\rŠ7B'[m¦0ä\n*JL[îN^4kMÇhA¸È\n'šü±s5ç¡»˜Nu)ÔÜÉ¬H'ëo™ºŠ2&‚³Ê60#rBñ¼©\"¤0Êš~R<ËËæ9(A˜§ª02^7*¬Zş0¦nHè9<««®ñ<‚P9ƒÈà…Bpê6±€ˆĞÆmËvÖ/8„©C¤b„ƒ²ğã*Ò‹3BÜ6'¬R:60ã8Ü§-\"Ü34ëëŒ3°N+ÃELš„6ÀP˜›¿¯üÕ*¥(è(!cl@™\"òœ«+®‘ˆòƒ/\0J’CÊ>Ğa\n`PÔÁÃ(ÔˆZt¸jä¦ïo#1‹Ô„)·Ì!£<IsàÎd(èŒ”ƒ“Ïƒe&\"ÎÉ€#D…,Ò€M-N˜#:ŠäcHÆ5°tHË^\$P#4‹vmĞ…ÔDjZÌm\r6å½h>é Ü:¦¢@t&‰¡Ğ¦)CÍô<…± Z‘UÅt¾JÁª`@¼¯h4ˆ‹Ô,«aŒî¯H@Ï0D\"`Ò¢:òƒ®È)5«r3Ğ%\"Ù!uß¥#¬„#3á\0Ú2¹¶>9ƒÍ¾ûªãt©ÕÌ˜XÏÃ’[)+r6ÂÉ ÚæWi¤æÙÃ©yæ}v\$Ö‰,hÚF•NªøæŸ¨çW|ºŠ ­eŞ•šë\0;\rŸ³8hº	Å0Š Œš\"9hü	µÃ¯”ÓìëB‹‡‰HĞâŒÁèD44C€æáxïÑ…ÉÇ(£8^™ã Ş¡'½x^İw0‡xÂešèÓ×Ü¨ö84|@AÅiÛ´ã@© :Ã¨ÈıÍµ²-à¤©¿‰ºÁ+ks×8:sİEÒtÈïP9u]`Ê<#ê¯~7vj`|\$£‚c£ÌrîØ›@ÈÔ´@ÌÍ\\™3ä¤û±êÈñ²4†ù¾7|ëÌë7¤ècpÄE)4V*Ì¥tËJ54	™@v’|˜Z\\3¥I³ö‚İ•s6/ê<¸p–šZ\r0È¾—®Æ{1\$¥@@P±!6 æZH0t>ÅXÀRÉHHV…ÑÅ†hx™ã2‡1 s2fÃI‚hÀß.cX~Qã¤|:÷®†Ğé\"èÔ½\"x²J»Ù?„Èÿ‘gLQZ„@LC\"®6)8˜%@Â˜RÑL7¢fšë%&9ĞêƒÎ*yc8›“’vz43±R=¡X®H¹¬r-(5™ˆ÷UÃBiÆ¬[¦&M{Poñán¬æÁ\0›0fX%Õ\r,á9 â”I2AOª1P™kÀæQ4\n<)…Eş|1¬…Óˆ´À@Hêİ]Š)™3Šbÿ\r'5“Ô[„åD”“³Y6¨\n!0’õªf‰HF\nA×ò©MD\r%Óxƒ2ÒˆB*jì²ÜoÔQ¾&”Å@…®ÉO1Œ7æÉ@°ª@¥C!Ä’™ÓåÌ¶cyì3è\$Æ ÅOLí?ªm³UCM#\r€¬5 E¿X”‚xğ”O¶ûP%)\"Èa†Á¨8ñÍÀiT*J0qGJ*v¢\n&ª½€ÓIÑa‚¨u^ªÕ•Íaã¥‰¨AÌš·Ğ#Â)WEØ×“†!*	ƒÅÀ“!_‰3±pà(â˜ÀrB	CÖ¸…Ä¥3|5zµ«Yš¨¬: F˜Á„Ê*uÂœğS¼RòJN,AJ„‹T³H¬a:áX¸p–•\n\n¡âÈšsş/*¦\nm¢âH“]A[Í´÷iXZ\nâOp";break;case"ta":$g="àW* øiÀ¯FÁ\\Hd_†«•Ğô+ÁBQpÌÌ 9‚¢Ğt\\U„«¤êô@‚W¡à(<É\\±”@1	| @(:œ\r†ó	S.WA•èhtå]†R&Êùœñ\\µÌéÓI`ºD®JÉ\$Ôé:º®TÏ X’³`«*ªÉúrj1k€,êÕ…z@%9«Ò5|–Udƒß jä¦¸ˆ¯CˆÈf4†ãÍ~ùL›âg²Éù”Úp:E5ûe&­Ö@.•î¬£ƒËqu­¢»ƒW[•è¬\"¿+@ñm´î\0µ«,-ô­Ò»[Ü×‹&ó¨€Ğa;Dãx€àr4&Ã)œÊs<´!„éâ:\r?¡„Äö8\nRl‰¬Êü¬Î[zR.ì<›ªË\nú¤8N\"ÀÑ0íêä†AN¬*ÚÃ…q`½Ã	&°BÎá%0dB•‘ªBÊ³­(BÖ¶nK‚æ*Îªä9QÜÄB›À4Ã:¾ä”ÂNr\$ƒÂÅ¢¯‘)2¬ª0©\n*Ã[È;Á\0Ê9Cxä¯³ü0oÈ7½ïŞ:\$\ná5O„à9óPÈàEÈŠ ˆ¯ŒR’ƒ´äZÄ©’\0éBnzŞéAêÄ¥¬J<>ãpæ4ãr€K)T¶±Bğ|%(D‹ëFF¸“\r,t©]T–jrõ¹°¢«DÉø¦:=KW-D4:\0´•È©]_¢4¤bçÂ-Ê,«W¨B¾G \rÃz‹Ä6ìO&ËrÌ¤Ê²pŞİñÕŠ€I‰´GÄÎ=´´:2½éF6JrùZÒ{<¹­î„CM,ös|Ÿ8Ê7£-Õ@×ìªZ6|†Y–ª¬L©×\"#s*Mãì«ğü/YC)JËiW±PãËjµ¡š_±°P*Î#•–¸D\$ıc)IJ•6şa+%’].«I‘m‡|\"–Ú£§GZ‡hõõ˜]XlTÒ‘ÕqUh²¸J2FWÛfF;~â–`-ìs­dù¸ò÷O ¡xHğÁ[•ÑÍ¾€d—²§­ñ­å›­ºº#yÁË=0_àñ\rïÍ±ìP¥ì›!^Ø ‚à¥½YêqR«Ë«_ÌÍo-\\æPÅ¡klx\$1s+éµÅ¯æ5Ìu“/=ç}mnB7¸v‹GmÜw]RÕŞö¹‡ª¸áÔzÄ…Û¯±)Ó~ÜCÜ·«·½qÛÿ§ñ,‹ó¶üÑnC6z©P€5ts@PH	\0è&„ĞtÂ˜\n†ĞÚÂØyƒAä.²Ô\"mŸšqŒ«ºõê’'‡ìÿÂş|Rf\rĞ´ñ)èb§ƒ* ª‰R*#€†È‹s`,mİ­àØ²\0QÛ;¡„9 Şƒ0lI”à0¢\"-% k3„g‹5a®1x€ ¨Ï(m!¸<‚\0ê¨ƒ¨cg¼9†`êCé 9‚Ãâ£haá…\$	\nİƒjH§Ü0S]Šï%\$áW9¤´”qH3ïEÆBŞÇ\$Áo’DéôFc¾˜O€f†!ÕQ¤@C\$‰\rÉ?5J¨Ã\"g\0ğ0èšè\"\rĞ:\0æx/ó,Yd™Árjà¾‚ôçÃ¤=àˆMsì’8<á„ b-ÙÓß,,u‡ÎÈ¬räììbşMø„ã_9}Ë°ƒ¤á\\¸‰K{JhÙ®@UöÃ”J³+Á58päy“úMÂ‡\0ÓT»—¡¢_Ì‡1f<É™aŞfË¦šæ”Ô\rÓRCÅK6Ì\0>	&œö1PÜ'ä…àŠŸŞÜ•<!¬ñ”ú™ãT³¦óšN‡ü÷Í…Rm””õ²ı{ûˆ,}ÆPòåÈA^\n ñÇ6U( ç®<#Ä<ªLU¸ÍC“Ìr‘Ú<G©ZÏÉñ¬„€0±P@èÄ;›!„69 ƒÜú–B`¢ªænÅr’{D!SfTo	h\n\n (Š£%¡*ºdÙL¡‡ ´Ø€³§\\Ş›ğÕ,³¬ó€øSÎzOYí­Á=‡ è|ÚvO0¡‡{‡X«\$MÇÂ^‡4ıä2e?57	Dš€PU¨ı0ÑOHg˜`‚·X#ÄÃ¬Š–Ü)… ŒR\r™†Uíñğ­xNhiZ‹0…3>Ty¤4sú¯8ÔJıŸj«VJ°+Ccf×“çUÌ•ı!§Ë@Pi­l·ñ¶Dº#\nB¶x;F+úRÿ)Æã\r¹,@çñ;«8yşb€ú£9ap˜¥(Ó˜¤ñ/]Å9x«:vSq¾\"-ö¾È²õaÙ‚&+Á\$‡“¸ in‘<'İPÑğ‰¡Ä:ôğ“Hm•ôšYĞû¶™CCN6\nä§ãÜp!gdÈŞxh%ˆäßş(\n<)…L‚ˆm|ïaõ‡G‘#NªQ”ã+bJ@ïñ­À&ú|—ì’Uğ1gÒDÁæ@3H\n%°n×¤:‡)¿ZsM·g†Î E¥(Ğ†XÂ§k/ßÊPJEô1^@ÂˆLÎ©z‚¥¥mÀ4štù!³~q\$vh%…&õF]m:_¶ˆD_=DE`îflş1æ&İhfî„5”ğæ;fXy ¡pFşàmµu‚€’}Ãm@ûİ(pá„ğsıŞ’•»*Ï¾8^ú’_MÉ5ˆù^cUõÈCĞË•ïëîJN¹;üå(ñÿ¼Ö@lyä4†0Ö+L½Ö36ÛÚyOÃHfµ¯kƒO%bx½²*…@ŒAÄ2–Ù…\$•î;ªÓ75Wihµ–pÔôáÛzãq'b£Oß-çq¹õËVÇiAçS½òmÛRzA]¸ÿ†Ó;Š;´(ï[s3@Xô\0Âóİ£0³oAûZèãä¥D”Œ‹”	J‡ŸŒÀºDL†ƒõA7¨u'“Î‘â¹f×hyÙ¢w·ˆµ/=ú³à¤Cf·TM\r—%n@yæ(É^0³À7Ûù±¡^B? ¤-¦nüª¯/¹×ÂuUL@Êë€U…ƒë\n	…¸ÔÔœ“wòş“œi,lLH6g˜ Ä\n4fºş˜”2úJGOZÕDˆìí\$Ê£¨ğĞZ®ÔÓ‹^y\0¦ù\0Ê¬ë†Ih>{Îb†–f†înûL2úøMÜüãoî ƒÍ „\"1íòîåÂô`{ã„&Ç/|Ÿ<D'–t00ğe÷g¤üäÁ0şüù£";break;case"th":$g="à\\! ˆMÀ¹@À0tD\0†Â \nX:&\0§€*à\n8Ş\0­	EÃ30‚/\0ZB (^\0µAàK…2\0ª•À&«‰bâ8¸KGàn‚ŒÄà	I”?J\\£)«Šbå.˜®)ˆ\\ò—S§®\"•¼s\0CÙWJ¤¶_6\\+eV¸6r¸JÃ©5kÒá´]ë³8õÄ@%9«9ªæ4·®fv2° #!˜Ğj65˜Æ:ïi\\ (µzÊ³y¾W eÂj‡\0MLrS«‚{q\0¼×§Ú|\\Iq	¾në[­Rã|¸”é¦›©7;ZÁá4	=j„¸´Ş.óùê°Y7Dƒ	ØÊ 7Ä‘¤ìi6LæS˜€èù£€È0xè4\r/èè0ŒOËÚ¶í‘p—²\0@«-±p¢BP¤,ã»JQpXD1’™«jCb¹2ÂÎ±;èó¤…—\$3€¸\$\rü6¹ÃĞ¼J±¶+šçº.º6»”Qó„Ÿ¨1ÚÚå`P¦ö#pÎ¬¢ª²P.åJVİ!ëó\0ğ0@Pª7\roˆî7(ä9\rã’°\"@`Â9½ã Şş>xèpá8Ïã„î9óˆÉ»iúØƒ+ÅÌÂ¿¶)Ã¤Œ6MJÔŸ¥1lY\$ºO*U @¤ÅÅ,ÇÓ£šœ8nƒx\\5²T(¢6/\n5’Œ8ç» ©BNÍH\\I1rlãH¼àÃ”ÄY;rò|¬¨ÕŒIMä&€‹3I £hğ§¤Ë_ÈQÒB1£·,Ûnm1,µÈ;›,«dƒµE„;˜€&iüdÇà(UZÙb­§©!N’ªÌTÚE‰Ü^±º«„›»m†0´A÷\r”ä»nB,å]÷*;\\“IÌwB¬§õÜ9X\\5o}aS{X,î’BÒ ¯Öˆg%'¨å¹‹®ú\"á£ÇPÓƒ,ÅŠªg(Èî’íê+ívë\$#\"LåCIr¢/àøA j„ğ«(b®×wÍ¾ºDÎÚ4é`ZbìÙ`\\iëlœÜé|„•ŠÊ™Ë[Ší:®ìˆŞ,°±d0ïØÎjvÊ«8gN\\gNî¨¸ºŠu‡«¼¥T¸q1ijÃí]GÕ äeSÉU_tÑüÆSÙîúº®ØÄH\$	Ğš&‡B˜§xIÊì)cÍô¨P^-µeÁj.ôyzã%vxÍÄ±B\$?5ğ@œShn‡¼Ä?ğäÿƒ(x@¡¸9†ßyäT­AÑÀè€Qè=A„9ğŞƒ0lJ¡”âœÑP–âÒHí¼ï¼Öšp”i\0ihÜ…@Ş|ƒha\rÁäXCc?Ì3PØ`oéT9‚Ãü¡àaá…*‚ª	[PmJ¡Õ‚€æ\nP{ÌIN¹°Ã3~·	ûŒY(i©27³yX\n‡±4ĞÍ\0¬\rJÀ€ †H´“tNp>†DÜàa=pt3ĞD tÌğ^äÀ.1ú§\$âÁxe\rÀ½=@ é¥\"Ò•%PÎxaÅ`)Ä9Pª[;LE„õdHàlE:©†.HÆ¦Ğ‹SE1ía¤›eÈì\ráÒE0í°Sn®JÀMNè¨`æ¢RmÀ4ÁÕ\"\$Ph‘’:HI))%¤Àw“Rr@Iàå(%¡RŠÀğ^Œ@>	!´8Û(ƒ¤°–Oúƒ pŞÚø ‡!¬÷†•\n›¡Ì€\rÁÑè¬sPsVÊ*¬Ñ\\˜ A-fü¨A ÷†Ç)àx çâ#†#ŞtwTµÍ7@ˆS~\"Äx“Óe3@çú–(\0ÃAÁ\0cœÔ 4†Ø¯İ©:G=\r<¡p«\\R°S„ê¬efã*õ`[•ˆ”+2¶ÀA\0P	@ƒ\nUKã.\nÀ“¶Êw&zæqË¤©•R®‚¾Wãä}±ø?A•µ(0äê	O©ş«Dïe)Zn¢§öE5\r¢²l@ôT7¸|Ô:‰M¨; €Æ(iò@Sj˜{Ãa0‘‡MapÁÎúºVrğ0¦‚3kc§Iä\"ÅG•M^</ÕY±¶Í4XAoº×\\.tcÜ‰]¬íe&KØ[İoqÅ…N¥’ª±a]-…ØáÂáÙYÍ;n~a6NVâD.îi¡u|VI%'¤@ÒÚŠOºˆ ƒûCˆu?‰ü3'\0Û'¤‡µi°1Ä”ñS,Ò†?h>î\"Û¼àß!eq)Ù×ÛÄVÊé=¤e\$\\cL·0\nU\$ÏŞÌ|’È\0–mi¶!BQñ.y\nÂ¦:ôcŸóiÅ©\\óÂèUŠĞf¹‰P|)pSb‰2lH Ån©8Q	€€3Y @}äPF\n•Ò6 ÓA\$VÄ˜™@&ãÜœ™ÂH‚ŠÆê¯8Ï¥¦[\$ı!­6@Ütq·Y²Ëô‚°	|l2óIŠ7ıÎFTVYYçi’²v©ÔMŒñå`Ó{­¨K45-1©ÊÉÛW¡†ÀW‹CHc\ryL€ûüÎ3**em¿Ò°³ğm)şßEPª0-Aád¬V\"Ãk;i5LiRç°Nn=Ö» ÆW²ï_›	A[ï²zpA3°À(&ĞğÒƒÎ·.ç!¯ª8Ü(›éİÉêÁÈœô8WDÃÅ7L¥q¢„ÀËŠYS§…¿<dK¶™fÔ9Ì¾i>İ9é\"Ç:ŒÁ—ûˆ©X	¿:ÔâS¨\nèrnW•v\r£‚,l1X_\r#WÕ›&­§ykê¾”ƒ)ù¥öP¬¡G#	Íˆ³+7j¡r²WwÃA6.	¿G‚	]85üò®¼¡Â9^ş2´P[‘İ%š\nğ";break;case"tr":$g="E6šMÂ	Îi=ÁBQpÌÌ 9‚ˆ†ó™äÂ 3°ÖÆã!”äi6`'“yÈ\\\nb,P!Ú= 2ÀÌ‘H°€Äo<N‡XƒbnŸ§Â)Ì…'‰ÅbæÓ)ØÇ:GX‰ùœ@\nFC1 Ôl7ASv*|%4š F`(¨a1\râ	!®Ã^¦2Q×|%˜O3ã¥Ğßv§‡K…Ês¼ŒfSd†˜kXjyaäÊt5ÁÏXlFó:´Ú‰i–£x½²Æ\\õFša6ˆ3ú¬²]7›F	¸Óº¿™AE=é”É 4É\\¹KªK:åL&àQTÜk7Îğ8ñÊKH0ãFºfe9ˆ<8S™Ôàp’áNÃ™ŞJ2\$ê(@:NØèŸ\rƒ\n„ŸŒÚl4£î0@5»0J€Ÿ©	¢/‰Š¦©ã¢„îS°íBã†:/’B¹l-ĞPÒ45¡\n6»iA`ĞƒH ª`P2ê`éHìÚ<ƒ4mÛ ³@3¦úî¼¯m Ò1ÈQ<,ŸEEˆ(AC|#BJÊÄ¦.8Ğô¨3X³8Âq‘bÔ„£\"lÁ€Lû?-JšİÎlbé„°\\”xc!¸`PĞÍò°äº#Èë– ­ƒ&\r(R”¬¬³–2³kèZüldŒ#ˆòbÕ8#ªúäºb=hºtËW¢Œc Ø	PSğÂÖXu…ŒŒÙ	xÙeK-Jç¹b˜t\"Ğæˆ‹cÍÌ<‹¡hÂ0…£8Î\nÉz!Vî°æÆµĞJİ\r×ÂˆøßpÊ<C£rài=IX²ˆğ6I`Q†C©¨øÚ2'Ë*|9§Ğ¸ô“Šc¨Ü:©ø‘Ÿ\rxÊÍ‰°á\0Ì0PH@7§cêÜŒ,ÜxÛ\rÉàã¢Ã„Œ6e\"8ƒHÖ‹n†C£[‡M£¹èòÑ\$â~k›Ïë,Ÿ9–šÙ;z\"N Œˆ{MÂ‘Fˆ©ã©óGL%êÊ2c\\ZİY-x‹Ê3¡Ğ:ƒ€æáxïÇ…Ö~ÔÉ(Î¦!zg~ƒK‚„Aó×¸+*è0å×—‹+\"È‡xÂk:Ûr9Å¹ú£5ƒ¨X\r‹Àè3CÚ¦ŒM½6ƒì÷èAmÜŒ÷çƒwv¸êÙ2(sTy,Ë6–oáÁğ¼?Åñ¼xïÈí()Ësw1‚&8?ôŠ(>@E0™»ÖFì]šB§mæ\0¦´Y±¹#¬0©œs>qY˜lG|X´BL•I\"wæ	¡5J•xlK¼è’º'Úø±l\$]šA¸ÎE;Ce1ê4â.‰C’N9%•Î‘òBdÃ\\E\$áU†äùK©>\rD³Ò@P9UÎÂÅj@ÁAE/©L)£S\nqP*EP66pò‚Ãleq?•4-Ï‘‡1ˆ%¼çF'Ğ³ª\"ä –èŒHÏ9n\r\nØÌŸ˜~Ì™™ aÒEÉ àTÚã?ÁÉ?u.ÑÁî›é(k£ˆu=¤&ŠtB\0(aL)hÆŒú¹{’†ÓŞ9\$;Dœ”’²!‰ä l½˜‘uBõÓä…®OàLxO (ÖG¢ñÇAQ>>’s#Pì\r¡É‡ NÕV:µPèÓŒhÃ<^áå—º¶–~âxn\$ì±*—€ò^ÀP	áL*Ò>AüÈjóM±:éÊ‰@F	EhG‘)BAÔ<†vEPQÄtí\nk6RƒB0T‹S\n’3 ÅA¨BSaL(„ÇvR¦•‡\$Ï%d‚¦Íùõde=50k!Œ8uR\"IÍ1„\"@š°Œ)†HÊEU±VSÛ95u\0§æ–¢Œ,™+-fÍ3Y[êÕr¬ÕxÄ¡lT×”kMÂ~\"™wÌg°%ìô4–\\O/éê\$4¨TÀ´!0ŞKãc¨6Á²}«jy®yUc5Ú-uYæ~ÕUªGæœIµí™—æ{_9³[Å#”hª=%)²¤.)|–À[‹…†n„Êè³èë 3n/•¶¦40Dƒ\nseÌÁ™LŠ=PËàY¡Tè’Tc?c-ÅáÛ«•Z ëÂ{¶UŞ°*»‚·XoY5„FUg]º:d¥'[€ƒ„­ér–’÷Rœ2Íåp—íëŠşU.Àr";break;case"uk":$g="ĞI4‚É ¿h-`­ì&ÑKÁBQpÌÌ 9‚š	Ørñ ¾h-š¸-}[´¹Zõ¢‚•H`Rø¢„˜®dbèÒrbºh d±éZí¢Œ†Gà‹Hü¢ƒ Í\rõMs6@Se+ÈƒE6œJçTd€Jsh\$g\$æG†­fÉj> ”CˆÈf4†ãÌj¾¯SdRêBû\rh¡åSEÕ6\rVG!TI´ÂV±‘ÌĞÔ{Z‚L•¬éòÊ”i%QÏB×ØÜvUXh£ÚÊZk€Àé7*¦M)4â/ñ55”CBµh¥à´¹æ	 †È ÒHT6\\›hîœt¾vc ’lüV¾–ƒ¡Y…j¦ˆ×¶‰øÔ®pNUf@¦;I“fù«\r:bÕib’ï¾¦ƒÜü’jˆ iš%l»ôh%.Ê\n§Á°{à™;¨y×\$­CC Ië,•#DôÄ–\r£5·ĞŠX?ŠjªĞ²¥‚ÖH¦)Lxİ¦(kfB®Køç‰{–ª)æ‰)Æ¯óªFHm\\¢F ‰\$j¡H!d*’²¬B²ÙÂéƒ´Õ—.C¯\$.)D\næ‰™ÄlbÌ9­kjÄ·«ª\\»´­ÌÊ¾†D’¡Òå¶\rZ\rîqdéš…1#D£&Ï?l‹&@Ô1Á M1Ä\\÷¡É`hr@:¼®Äíªş¦,¼¶‡’Î¢[\nCä*›(m,•¨r¼L„×JÁ4»\"ìœ´£ˆÑGUN/¸˜—;s?K«¡®„s3ªÉBcH¨(˜È‚4^„·ÜœÃ~Õr“}MÃt%Ä°¡pHâÁÎÄ\r^Á2ø[\$¨ÑCkJVÍGš“A\\D[sP×‘B¦XÆhŠ£Ò65Ò„Ô©©\$cõÿ•Ñ—šW(®”şWF’-^&éô+B–©X{7Ç1óŸ™áp´êÊR“¬ê:Ú’“ëÈ;-(lNæÉ³mN¯µ·zÔÅ·¬ØUGHöìÆ-r4iV†&/#Èdå\n4¦sŠ^Âİv@sâîm«1¬	\$Xøˆ4cÄ6³ø@7A\0Ê7uİ‡eÚvC(ğ:vã˜Ò7ÔM¹M˜tZ6ƒ–lë¦ûrt^­y=`ƒ=ıË–:Zæ³7_>V“¸h\\Tôš2bë`\$Œ+ôÙjx\\‚dÿfA„¦\$;¹¬µ5`B…»&Ë)a’ìŞë|J/´GLÚ‹‹æ&/¡õ'ìû–KñY¯Ğ§%¦p‚^Ûúmè}ş™'ş‡ ğ€¤•%@„’L`[jaÍñ¤ÒAŸ93‚°t¦Áƒš²ûòƒ]8'\$9Îl%>è\0µèTI`3ÔyğH¢¶¥„œSÙéNÅÕ6ƒŠ.YÇ7E\0»œQ|™†ˆ‰mJMk¦ÔªqßÉQL2ĞÒƒ(rLÍÊ  ’ˆşt<\$àğ0‚\0Ğƒ(f ˆ4@è˜:à¼;ÉP\\c¬w ¹Úp^íÁxdx„:<0ÜÁ>!eÄº´€xÃ>z§İxh´q.2EM1G(÷Ûê`keÕ2“e²s‰ÓPJSYb…_ÊT,±D’Ö£Õ\\7Âô¥•ÓÊØJm‰i`Ä’Œx\"ùv7à‚BHi\"¤d’JJIi1£Àr“ÊOJ|ğ,¦•UbQNry„\"WË®	¡ÿOñ¢5>ÒQ9X,8DèCGÈaÔ©¾zeY®ù¼×Í“2X¬\r£¢RA§1b1…^/Ä½ V­Pæ°ù•Ğr›lç˜Ï¬®§´=GŸ|@o³NfÅÃ`GJ|[>q|‚dæzr­XEUc’¦UKZ73”a<§³fŸJ97Í-:Â€ €-¬‰é>S\"Ag4QµTú®“\\Ò\":eÓè÷[PØnEÄµ‰öèeJ5„„ÍêQô¦sMóv£ˆ)%>–¾®ÕÓ^Æ}kØ\"vKX£©-ÑÉ¦BËEB‰Y'Áãœ.ãDc\$¥^3\"JlT:À6rí·Ø:ÂiæAuIJ¬0¦‚1æiÉù›VÙ]\n1tWIè¦©¤M£QrqbŞ0™.n¡¢‡C<ŸÅÉÀ¸Y7«Ş©Y…bcr	!u&GÃªÂî«Ud5Şa¦BUÉK8³%=Û¶ŸËX¯aÑ¦+S‡gæ{n‹CDCëx‘A/NÑÒ|Gœ{l!<6gQw6ğšÍhp*ˆ¦r”eÌ‡«©QÀ@xS\nŒ_Ëªt™.c·k>ÜxU%¤|˜&ÛàÌ—2ç#¥8ªä¯JÎC–‰%2îÉå‡¢TĞC9<GDàab<H	¹8KóbH¨Ö6Eo)z½.Á\0S\n!0›Bd!\0B0T\n™â=HºeP>”Ú‘\nq‰{Ğö/u=oÍäXPå‹í:J×ÀÔ\rì­ËšdGõ>!%ŸUÅj†[¨#XP¯i´ùNÌ–úÉ¼kÍX•1†¥ØXƒT-ŠÚrÈØ\rulÅ<¦‰àCy¡°¼«²ò˜X¾3PÉÙ3½lèª×Ú»<ª½-¬Ò‚İÚÿ,+OoĞÜÍÌµ.Î,@@B F àDç@n‘ĞºÖ¤ŸGGË@‹Úæ¦`Æã„gÎ\\Î.¢;÷‰peo×á¼i7qËfÌV{¦	›³”ÃOA¢°h7_ÙÕÍ&sriğ¼—/! t…’ê7D¥¦¡T‚)ã,89•JN\$ßBßÎŠğëaJ-ù©3åøvÕ1«Îş±Î¤»;õ¤'xjëÆ}†çÈÉ±s Æ	‰Ä×a\0–•ßCµlg‰zúâ‰!¤q5wÅTg†àê-M:6q–»PĞZ­u-®n%pœeaİæÔ³S#B¡5>Ò²*i±æ'©uˆR4£í#è4jôPqü&ÂÑŠ3—eÕô0";break;case"vi":$g="Bp®”&á†³‚š *ó(J.™„0Q,ĞÃZŒâ¤)vƒ@Tf™\nípj£pº*ÃV˜ÍÃC`á]¦ÌrY<•#\$b\$L2–€@%9¥ÅIÄô×ŒÆÎ“„œ§4Ë…€¡€Äd3\rFÃqÀät9N1 QŠE3Ú¡±hÄj[—J;±ºŠo—ç\nÓ(©Ubµ´da¬®ÆIÂ¾Ri¦Då\0\0A)÷XŞ8@q:g!ÏC½_#yÃÌ¸™6:‚¶ëÑÚ‹Ì.—òŠšíK;×.ğ›­Àƒ}FÊÍ¼S06ÂÁ½†¡Œ÷\\İÅv¯ëàÄN5°ªn5›çx!”är7œ¥ÄC	ĞÂ1#˜Êõã(æÍã¢&:ƒóæ;¿#\"\\! %:8!KÚHÈ+°Úœ0RĞ7±®úwC(\$F]“áÒ]“+°æ0¡Ò9©jjP ˜eî„Fdš²c@êœãJ*Ì#ìÓŠX„\n\npEÉš44…K\nÁd‹Âñ”È@3Êè&È!\0Úï3ZŒì0ß9Ê¤ŒHÃ(©\"Ÿ;¯mhÃ#ˆCJV« %há>%*lœ°ù‡Î¢m)è	RÜ˜„ˆA¯°íòƒ, Óõ\r”Eñ*iH\$“@Â70ÌCŒ‹Èò:¡@æLpÑª PH…¡ gd†³áXén	~Å/E,1§Lòa”MË]é@ğêÑu*pM¤ê	\n,³<ÄàÊÅS²º†™'HAyĞcdÇG—tŒÎÁÉÒJpSŞÙS©…Ş5˜eC#àˆur¦ÖÜ„ó²8Ñ(ôB±v	Ğš&‡B˜¦cÎ\\<‹¡hÚ6…£ È›ÖÊ²ìÈ\"\r1¸Ä6@j@@üßcsÿ¢Gz8å¦Œ£Åx7cLQgõšf9ÅC@6-\0P²7³Cåã0Ì6;c*]635ğ]”õ«¼PŒüæ¸eª'A£“èA?­í8êï³xûVÍ\r‘hX\"åÌ½\\j±¿³UÀAeÔ72£\n]†ÈHîï@Î¥˜×ŒÚ@ëª®ÂÈ6í£—1êãHÈûâ†4lã0z\r è8aĞ^şˆ\\0öıÈ\\üŒáxÊ7ã\$PşêŞàD{ÚPèíŒáà^0‡Ép¡Ğê!@é»Jjw HPé~:qüŒÌª³TD¡Ë\n¥…Ğ­SuM±§ uPRê%¯á¼Wò^[ÍyïD;½7ª³×Oeí½¦¤öÚª(àˆ¤ãzˆ0¡&OÈ6¾§ØÂIhg‹a0°”°º-sht‘—•.DĞ4)%ªÕ†ÆùÑ@ á¤6Âò€Ãöv!È6†UrC2¼iáÌ:†0ÇC0uŠÁ°7¦‚	Èé4@hŞ.VÎïÃsç!±¬B¢âBEèÅ£Têháw=¡n‚‚€H\nÑi¢<%Èâ3HDÈ7£´.Š!O}rN‡\0äNÓDñyÍ ôĞPB\nq°;ÅçX}°(aÍÆp@vã”¿\rÁÀ:¡\$…’¹ì~»€Îòb¼Áhá6ôÚ´IÀƒ:e ¡„0¦‚3\r/õ ¦d²ªÉJs\$‰(34ˆÅĞ«®±i §,¢šjØHh*’¤­p ç0F‚¡Ñ9]-ìµI–\0'K±Å'éå{†‰JEÅIÄ?‰ ©Ù6gy@¤—’\$[!ëwÒÆ\$æ.ÇĞ;g5cğtİ³¸„\r<½²”@èFUŸ¶xÛÉ!CÅ–ğ Â˜TpÎ!Æ“˜&»\\’g8\"hfÆ	¹9'gÑb-ëş ¤RÉa†+x(qD™‘@dwIÑ¡PÔ¨‹Y #ñDØ#I&sÜLè9òt1ŒaBDÉÂ1=†Z¤iæÃ‚mu'}ÑÙ™7:'<	–u6R	sšE“ì‚?‚h_úzbU#„ä:)H C-ZÄ-Wè_êÓˆ-	^Y”èŒ2>5-7¿–ï…Ğ±1©)M@ª0-‘!Zğ	OY‘2uYxıDHñ êÉ³Û,zn8\n‡Eìé™KX‚ˆuVp<“\0£’rÂ\"“/®u_ò†•«é¶&Ú\\pÅØ‹3eÚ³†ui¯­ägÊuY›4æ¢æáli-0¤à¨w…-ÈÑÁÁHˆr2I:°av#U¡Ø±”aE…„–.–±á€n©[Ö‚ÒÃ'÷t¥FÍ**ºÊ¼åDÀ\$o4¾ÁÎÓ5{l®N";break;case"zh":$g="æA*ês•\\šr¤îõâ|%ÌÂ:\$\nr.®„ö2Šr/d²È»[8Ğ S™8€r©!T¡\\¸s¦’I4¢b§r¬ñ•Ğ€Js!J¥“É:Ú2r«STâ¢”\n†Ìh5\rÇSRº9QÉ÷*-Y(eÈ—B†­+²¯Î…òFZI9PªYj^F•X9‘ªê¼Pæ¸ÜÜÉÔ¥2s&Ö’Eƒ¡~™Œª®·yc‘~¨¦#}K•r¶s®Ôûkõ|¿iµ-rÙÍ€Á)c(¸ÊC«İ¦#*ÛJ!A–R\nõk¡P€Œ/Wît¢¢ZœU9ÓêWJQ3ÓWãq¨*é'Os%îdbÊ¯C9Ô¿Mnr;NáPÁ)ÅÁZâ´'1Tœ¥‰*†J;’©§)nY5ªª®¨’ç9XS#%’ÊîœÄAns–%ÙÊO-ç30¥*\\OœÄ¹lt’å¢0]Œñ6r‘²Ê^’-‰8´å\0Jœ¤Ù|r—¥\nÃ‘)V½Š»l½¯§)2ï@Q)n„‘«Kğı+)3¤«ú'<×(MÌáÊ]—QsÅ¡ã AR–LëI SA b¥¤8s–’’N]œÄ\"†^‘§9zW%¤s]î‘AÉ±ÑÊEtIÌE•1j¨’IW)©i:R9TŒÙÒQ5L±	^œ¤y#XM!,ü 5ÕxŸBöm@?‹ÁÎG\n£¼\$	Ğš&‡B˜¦cÍÜ<‹¡pÚ6…Ã ÈV•i==‹)M8»ª0æD¤²WÀXDØTAĞÙK´`PØ:Ijs”ÅÙÎ]ç!tC1¤â E2ôÁ9ëDå6I…!bt‘‘X¹1ñ˜“HdzW–ê5¦DÇI\$¶qÒC£ey*Æ‘…VOåò±tÆ„ùĞXP¶îˆ# Ú4Ã(åŸ?ÇA\0AÉ\"¢D»yvVƒ@4C(Ì„C@è:˜t…ã¿;É³Ãxä3…ã(ÜŒƒxÜ0ƒO,„AóÌ…òDYb%	jt“¥D‡xÂÈÁxM:mD/F{F¥‘¬æ«6ªèÌU3¿p±eŞ„ú•ºîûÎ÷¾ïüÂğüNÇ²\\oÈòc(ğ:rCŸ5É„J8|ÿaği+Øöxq,\\œÅ™*›A…qsÁÈ.D(å‚Y\r‹ñ\nêQPat»^'O‰ó>¦4VŠAÌ\$Äj®(\"q\"V¤\"„û^@CœM‹‚#	È„JÉ`Å¼4˜ÒPP§.¢0Æ™8¬[î.ÊÁá\nA\0P	A‚C”³`X¨te×¼R4`‰©73ÂxsáJ€ç/ĞÊqV+Öh£1¤¾	3ƒ\nO¹ù?g,¹ pxK8æñ´øGèæP‡Ü^,hrÇ µ¤Ñ@cL2.\0€!…0¤'‘j³üğ²ÎZŒrˆÖ^@¶¯0˜( M‰Á:Cƒ˜WTôÒÄyJ?Ã”Q	åV(Âs	á8cé‡‚\$“ˆæÂöÛ2Ú“m\0sï“…há,AWRêİiW\n<)…FzÌY1DşY4¬#Ê‘_†-‚¶¨-e»\n\"N©ñÊ\$Hğ»‘ˆñ^IÏ €wüC„`¨ù!ıˆ°¦BaL'Æ¾b’æNÊRü|8(mlœã êEÌ\$1…áå“6%…… ¤TpYó`éP›ÈH¼-)#é‰â^,%b9`Á“^ˆğ†Æa½“©0C©>\$\råô_‰¸fi…qñ(L‹aË D\";¡T*`ZÚh*ôÚ¼ª4`âq#«åYŠ“¦°±««\"P\\¯2T!išC @‰CL\\„\n…KfàìµòmËèébú*œ!^ÅÍwªBñ\rˆ¡@:ìy3Lòuª	ohò_©Ö%\"\\LI-\"\0¹›'/R=l\"0´¡uòrËH¤*	3Æ­qN	i*¦6)%((­)Ò¯0Í=Wª´ŠR,";break;case"zh-tw":$g="ä^¨ê%Ó•\\šr¥ÑÎõâ|%ÌÂ:\$\ns¡.ešUÈ¸E9PK72©(æP¢h)Ê…@º:i	%“Êcè§Je åR)Ü«{º	Nd TâPˆ£\\ªÔÃ•8¨CˆÈf4†ãÌaS@/%Èäû•N‹¦¬’Ndâ%Ğ³C¹’É—B…Q+–¹Öê‡Bñ_MK,ª\$õÆçu»ŞowÔfš‚T9®WK´ÍÊW¹•ˆ§2mizX:P	—*‘½_/Ùg*eSLK¶Ûˆú™Î¹^9×HÌ\rºÛÕ7ºŒZz>‹ êÔ0)È¿Nï\nÙr!U=R\n¤ôÉÖ^¯ÜéJÅÑTçO©](ÅI–Ø^Ü«¥]EÌJ4\$yhr•ä2^?[ ô½eCr‘º^[#åk¢Ö‘g1'¤)ÌT'9jB)#›,§%')näªª»hV’èùdô=OaĞ@§IBO¤òàs¥Â¦K©¤¹Jºç12A\$±&ë8mQd€¨ÁlY»r—%ò\0J£Ô€D&©¹HiË8¬)^r“*ÁÊ\\gA2‡@1DµäÉv—”ªi`\\…É>Ïä-æ1‡IAC“er2ò:¡@æ©¤Ä¶HÀPH…Á gR†ªi N(kÈ]—g1GÊ‡9{IÄq\$ı‘àRzŸ éq¤å|@—Ñ_sùZH\$kW–Î±|C9Të‰.¬'¤%¨—ä!ÊC—ItW\\×B“J(d\r£i—e´ØG—Ê²°\$	Ğš&‡B˜¦cÎ<‹¡pÚ6…Ã ÉcÙ\$™ü“(ÁL@Á±(@s±ÊJ“øÊ•ã¸Ô5CÑDB6Šú¬\rƒ äË•I6Q0DÑÈ]Ì±È^K¢¥8£õÏtÑÌR2.Ñ#r	Ò@‘Ê4†‡Dw„t’\n©|F\$9t%4ND'äVC£]9Oa:€F¥@‚2\r£HÜ2ZÆ´×ÌB•Ê6‚D-i\"!\0Ğ9£0z\r è8aĞ^ı\\0ï›÷\0\rãÎŒ£p^2\rãpÂ:\r=ˆ^Ç1H4EòD^šKç<¯G(aã|B˜Ê9}¨İ®Åz™\$pPVÊFµEŸ[³Ì^ÔÀr—)Ës×9ÏtI¾ïã—QÕupÊÀnz®Ø |àÙ8å\"Uæ<æL%…É£ W	2á”…£”F	c,ô£Ök¢ıŠ€(…[d'±ŠÑH†„i\0U‚ÀC¯¦8ÛDûxA£œM¤! q	°æLèhM4‘¹›åâÜÊ&ÄÄ†É{)D(ŒÇÅ^.Òùÿ@\"Ñ2‚\0 ƒŠuÅ1ïÊSÊ|…H˜!bj…¹¨@XqMJœÌT„¢“+æOhV+\0†9gÂ€^5ƒTk\r\0èbôs)â†#–Ê¢=DÌ[(›d±0µÈM\0C\naH#\0èÃ[­0\rï¾2/\rQ1/Ã”K\nãğ-‘ËT%äÄ™È`9…pµHá²”X‚'\$9A5¨ –83š9DP D¦ĞS!Ì'„àæ3,Í—QÊ/„ó{>Ë\0LPPæ\"	îˆ±Ò%ÄâM#š<Ò'E–\n<)…@@Å\n\0ª(œÀap&Êh/Å	doÌdØ›L2“!ZAˆAi‚ ÏŠõ^|„\0š{A*vH…G¢,)…˜N\rìç9Í¡'\nH,ªŠ?¥ô¨øx+Ÿ@è…@ï‰“Âú\r)§1*9HKéITIÍª•mÊ@J¼\ru[R&}	ƒ/cxŸèœF’\0#Â2\r‡.¶9„ù?5ÓM)C®+ø¹Dä˜rÂETaì\n¡P#ĞpqP9´]gÑ@5È›)É#­Mˆ±:ˆÅyÖö\0J•|&J†@€51=C„Úœâpt›N<:=–àWÇD\$\"×¯öüÁ *‹Vb‡Šã(*¯…\0)³BsˆÃ¢ò\nh‰rßÍõWè8¡‹©ô¬‹:#bô—YEÑ{SzŠ%Å^„ÁÌªÕiâ¢fL¿+Å2í¤(é#à";break;}$wg=array();foreach(explode("\n",lzw_decompress($g))as$X)$wg[]=(strpos($X,"\t")?explode("\t",$X):$X);return$wg;}if(!$wg){$wg=get_translations($ba);$_SESSION["translations"]=$wg;}if(extension_loaded('pdo')){class
Min_PDO{var$_result,$server_info,$affected_rows,$errno,$error,$pdo;function
__construct(){global$b;$Me=array_search("SQL",$b->operators);if($Me!==false)unset($b->operators[$Me]);}function
dsn($Pb,$V,$E,$C=array()){$C[PDO::ATTR_ERRMODE]=PDO::ERRMODE_SILENT;$C[PDO::ATTR_STATEMENT_CLASS]=array('Min_PDOStatement');try{$this->pdo=new
PDO($Pb,$V,$E,$C);}catch(Exception$ec){auth_error(h($ec->getMessage()));}$this->server_info=@$this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);}function
quote($P){return$this->pdo->quote($P);}function
query($F,$Dg=false){$G=$this->pdo->query($F);$this->error="";if(!$G){list(,$this->errno,$this->error)=$this->pdo->errorInfo();if(!$this->error)$this->error=lang(21);return
false;}$this->store_result($G);return$G;}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result($G=null){if(!$G){$G=$this->_result;if(!$G)return
false;}if($G->columnCount()){$G->num_rows=$G->rowCount();return$G;}$this->affected_rows=$G->rowCount();return
true;}function
next_result(){if(!$this->_result)return
false;$this->_result->_offset=0;return@$this->_result->nextRowset();}function
result($F,$o=0){$G=$this->query($F);if(!$G)return
false;$I=$G->fetch();return$I[$o];}}class
Min_PDOStatement
extends
PDOStatement{var$_offset=0,$num_rows;function
fetch_assoc(){return$this->fetch(PDO::FETCH_ASSOC);}function
fetch_row(){return$this->fetch(PDO::FETCH_NUM);}function
fetch_field(){$I=(object)$this->getColumnMeta($this->_offset++);$I->orgtable=$I->table;$I->orgname=$I->name;$I->charsetnr=(in_array("blob",(array)$I->flags)?63:0);return$I;}}}$Mb=array();function
add_driver($t,$B){global$Mb;$Mb[$t]=$B;}class
Min_SQL{var$_conn;function
__construct($h){$this->_conn=$h;}function
select($Q,$K,$Z,$Jc,$se=array(),$z=1,$D=0,$Re=false){global$b,$x;$pd=(count($Jc)<count($K));$F=$b->selectQueryBuild($K,$Z,$Jc,$se,$z,$D);if(!$F)$F="SELECT".limit(($_GET["page"]!="last"&&$z!=""&&$Jc&&$pd&&$x=="sql"?"SQL_CALC_FOUND_ROWS ":"").implode(", ",$K)."\nFROM ".table($Q),($Z?"\nWHERE ".implode(" AND ",$Z):"").($Jc&&$pd?"\nGROUP BY ".implode(", ",$Jc):"").($se?"\nORDER BY ".implode(", ",$se):""),($z!=""?+$z:null),($D?$z*$D:0),"\n");$Qf=microtime(true);$H=$this->_conn->query($F);if($Re)echo$b->selectQuery($F,$Qf,!$H);return$H;}function
delete($Q,$Ye,$z=0){$F="FROM ".table($Q);return
queries("DELETE".($z?limit1($Q,$F,$Ye):" $F$Ye"));}function
update($Q,$N,$Ye,$z=0,$L="\n"){$Rg=array();foreach($N
as$y=>$X)$Rg[]="$y = $X";$F=table($Q)." SET$L".implode(",$L",$Rg);return
queries("UPDATE".($z?limit1($Q,$F,$Ye,$L):" $F$Ye"));}function
insert($Q,$N){return
queries("INSERT INTO ".table($Q).($N?" (".implode(", ",array_keys($N)).")\nVALUES (".implode(", ",$N).")":" DEFAULT VALUES"));}function
insertUpdate($Q,$J,$Pe){return
false;}function
begin(){return
queries("BEGIN");}function
commit(){return
queries("COMMIT");}function
rollback(){return
queries("ROLLBACK");}function
slowQuery($F,$kg){}function
convertSearch($u,$X,$o){return$u;}function
value($X,$o){return(method_exists($this->_conn,'value')?$this->_conn->value($X,$o):(is_resource($X)?stream_get_contents($X):$X));}function
quoteBinary($rf){return
q($rf);}function
warnings(){return'';}function
tableHelp($B){}}$Mb["sqlite"]="SQLite 3";$Mb["sqlite2"]="SQLite 2";if(isset($_GET["sqlite"])||isset($_GET["sqlite2"])){define("DRIVER",(isset($_GET["sqlite"])?"sqlite":"sqlite2"));if(class_exists(isset($_GET["sqlite"])?"SQLite3":"SQLiteDatabase")){if(isset($_GET["sqlite"])){class
Min_SQLite{var$extension="SQLite3",$server_info,$affected_rows,$errno,$error,$_link;function
__construct($q){$this->_link=new
SQLite3($q);$Tg=$this->_link->version();$this->server_info=$Tg["versionString"];}function
query($F){$G=@$this->_link->query($F);$this->error="";if(!$G){$this->errno=$this->_link->lastErrorCode();$this->error=$this->_link->lastErrorMsg();return
false;}elseif($G->numColumns())return
new
Min_Result($G);$this->affected_rows=$this->_link->changes();return
true;}function
quote($P){return(is_utf8($P)?"'".$this->_link->escapeString($P)."'":"x'".reset(unpack('H*',$P))."'");}function
store_result(){return$this->_result;}function
result($F,$o=0){$G=$this->query($F);if(!is_object($G))return
false;$I=$G->_result->fetchArray();return$I[$o];}}class
Min_Result{var$_result,$_offset=0,$num_rows;function
__construct($G){$this->_result=$G;}function
fetch_assoc(){return$this->_result->fetchArray(SQLITE3_ASSOC);}function
fetch_row(){return$this->_result->fetchArray(SQLITE3_NUM);}function
fetch_field(){$e=$this->_offset++;$T=$this->_result->columnType($e);return(object)array("name"=>$this->_result->columnName($e),"type"=>$T,"charsetnr"=>($T==SQLITE3_BLOB?63:0),);}function
__desctruct(){return$this->_result->finalize();}}}else{class
Min_SQLite{var$extension="SQLite",$server_info,$affected_rows,$error,$_link;function
__construct($q){$this->server_info=sqlite_libversion();$this->_link=new
SQLiteDatabase($q);}function
query($F,$Dg=false){$Vd=($Dg?"unbufferedQuery":"query");$G=@$this->_link->$Vd($F,SQLITE_BOTH,$n);$this->error="";if(!$G){$this->error=$n;return
false;}elseif($G===true){$this->affected_rows=$this->changes();return
true;}return
new
Min_Result($G);}function
quote($P){return"'".sqlite_escape_string($P)."'";}function
store_result(){return$this->_result;}function
result($F,$o=0){$G=$this->query($F);if(!is_object($G))return
false;$I=$G->_result->fetch();return$I[$o];}}class
Min_Result{var$_result,$_offset=0,$num_rows;function
__construct($G){$this->_result=$G;if(method_exists($G,'numRows'))$this->num_rows=$G->numRows();}function
fetch_assoc(){$I=$this->_result->fetch(SQLITE_ASSOC);if(!$I)return
false;$H=array();foreach($I
as$y=>$X)$H[idf_unescape($y)]=$X;return$H;}function
fetch_row(){return$this->_result->fetch(SQLITE_NUM);}function
fetch_field(){$B=$this->_result->fieldName($this->_offset++);$He='(\[.*]|"(?:[^"]|"")*"|(.+))';if(preg_match("~^($He\\.)?$He\$~",$B,$A)){$Q=($A[3]!=""?$A[3]:idf_unescape($A[2]));$B=($A[5]!=""?$A[5]:idf_unescape($A[4]));}return(object)array("name"=>$B,"orgname"=>$B,"orgtable"=>$Q,);}}}}elseif(extension_loaded("pdo_sqlite")){class
Min_SQLite
extends
Min_PDO{var$extension="PDO_SQLite";function
__construct($q){$this->dsn(DRIVER.":$q","","");}}}if(class_exists("Min_SQLite")){class
Min_DB
extends
Min_SQLite{function
__construct(){parent::__construct(":memory:");$this->query("PRAGMA foreign_keys = 1");}function
select_db($q){if(is_readable($q)&&$this->query("ATTACH ".$this->quote(preg_match("~(^[/\\\\]|:)~",$q)?$q:dirname($_SERVER["SCRIPT_FILENAME"])."/$q")." AS a")){parent::__construct($q);$this->query("PRAGMA foreign_keys = 1");$this->query("PRAGMA busy_timeout = 500");return
true;}return
false;}function
multi_query($F){return$this->_result=$this->query($F);}function
next_result(){return
false;}}}class
Min_Driver
extends
Min_SQL{function
insertUpdate($Q,$J,$Pe){$Rg=array();foreach($J
as$N)$Rg[]="(".implode(", ",$N).")";return
queries("REPLACE INTO ".table($Q)." (".implode(", ",array_keys(reset($J))).") VALUES\n".implode(",\n",$Rg));}function
tableHelp($B){if($B=="sqlite_sequence")return"fileformat2.html#seqtab";if($B=="sqlite_master")return"fileformat2.html#$B";}}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
connect(){global$b;list(,,$E)=$b->credentials();if($E!="")return
lang(22);return
new
Min_DB;}function
get_databases(){return
array();}function
limit($F,$Z,$z,$he=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z".($he?" OFFSET $he":""):"");}function
limit1($Q,$F,$Z,$L="\n"){global$h;return(preg_match('~^INTO~',$F)||$h->result("SELECT sqlite_compileoption_used('ENABLE_UPDATE_DELETE_LIMIT')")?limit($F,$Z,1,0,$L):" $F WHERE rowid = (SELECT rowid FROM ".table($Q).$Z.$L."LIMIT 1)");}function
db_collation($l,$bb){global$h;return$h->result("PRAGMA encoding");}function
engines(){return
array();}function
logged_user(){return
get_current_user();}function
tables_list(){return
get_key_vals("SELECT name, type FROM sqlite_master WHERE type IN ('table', 'view') ORDER BY (name = 'sqlite_sequence'), name");}function
count_tables($k){return
array();}function
table_status($B=""){global$h;$H=array();foreach(get_rows("SELECT name AS Name, type AS Engine, 'rowid' AS Oid, '' AS Auto_increment FROM sqlite_master WHERE type IN ('table', 'view') ".($B!=""?"AND name = ".q($B):"ORDER BY name"))as$I){$I["Rows"]=$h->result("SELECT COUNT(*) FROM ".idf_escape($I["Name"]));$H[$I["Name"]]=$I;}foreach(get_rows("SELECT * FROM sqlite_sequence",null,"")as$I)$H[$I["name"]]["Auto_increment"]=$I["seq"];return($B!=""?$H[$B]:$H);}function
is_view($R){return$R["Engine"]=="view";}function
fk_support($R){global$h;return!$h->result("SELECT sqlite_compileoption_used('OMIT_FOREIGN_KEY')");}function
fields($Q){global$h;$H=array();$Pe="";foreach(get_rows("PRAGMA table_info(".table($Q).")")as$I){$B=$I["name"];$T=strtolower($I["type"]);$Db=$I["dflt_value"];$H[$B]=array("field"=>$B,"type"=>(preg_match('~int~i',$T)?"integer":(preg_match('~char|clob|text~i',$T)?"text":(preg_match('~blob~i',$T)?"blob":(preg_match('~real|floa|doub~i',$T)?"real":"numeric")))),"full_type"=>$T,"default"=>(preg_match("~'(.*)'~",$Db,$A)?str_replace("''","'",$A[1]):($Db=="NULL"?null:$Db)),"null"=>!$I["notnull"],"privileges"=>array("select"=>1,"insert"=>1,"update"=>1),"primary"=>$I["pk"],);if($I["pk"]){if($Pe!="")$H[$Pe]["auto_increment"]=false;elseif(preg_match('~^integer$~i',$T))$H[$B]["auto_increment"]=true;$Pe=$B;}}$Nf=$h->result("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q));preg_match_all('~(("[^"]*+")+|[a-z0-9_]+)\s+text\s+COLLATE\s+(\'[^\']+\'|\S+)~i',$Nf,$Nd,PREG_SET_ORDER);foreach($Nd
as$A){$B=str_replace('""','"',preg_replace('~^"|"$~','',$A[1]));if($H[$B])$H[$B]["collation"]=trim($A[3],"'");}return$H;}function
indexes($Q,$i=null){global$h;if(!is_object($i))$i=$h;$H=array();$Nf=$i->result("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q));if(preg_match('~\bPRIMARY\s+KEY\s*\((([^)"]+|"[^"]*"|`[^`]*`)++)~i',$Nf,$A)){$H[""]=array("type"=>"PRIMARY","columns"=>array(),"lengths"=>array(),"descs"=>array());preg_match_all('~((("[^"]*+")+|(?:`[^`]*+`)+)|(\S+))(\s+(ASC|DESC))?(,\s*|$)~i',$A[1],$Nd,PREG_SET_ORDER);foreach($Nd
as$A){$H[""]["columns"][]=idf_unescape($A[2]).$A[4];$H[""]["descs"][]=(preg_match('~DESC~i',$A[5])?'1':null);}}if(!$H){foreach(fields($Q)as$B=>$o){if($o["primary"])$H[""]=array("type"=>"PRIMARY","columns"=>array($B),"lengths"=>array(),"descs"=>array(null));}}$Of=get_key_vals("SELECT name, sql FROM sqlite_master WHERE type = 'index' AND tbl_name = ".q($Q),$i);foreach(get_rows("PRAGMA index_list(".table($Q).")",$i)as$I){$B=$I["name"];$v=array("type"=>($I["unique"]?"UNIQUE":"INDEX"));$v["lengths"]=array();$v["descs"]=array();foreach(get_rows("PRAGMA index_info(".idf_escape($B).")",$i)as$qf){$v["columns"][]=$qf["name"];$v["descs"][]=null;}if(preg_match('~^CREATE( UNIQUE)? INDEX '.preg_quote(idf_escape($B).' ON '.idf_escape($Q),'~').' \((.*)\)$~i',$Of[$B],$ef)){preg_match_all('/("[^"]*+")+( DESC)?/',$ef[2],$Nd);foreach($Nd[2]as$y=>$X){if($X)$v["descs"][$y]='1';}}if(!$H[""]||$v["type"]!="UNIQUE"||$v["columns"]!=$H[""]["columns"]||$v["descs"]!=$H[""]["descs"]||!preg_match("~^sqlite_~",$B))$H[$B]=$v;}return$H;}function
foreign_keys($Q){$H=array();foreach(get_rows("PRAGMA foreign_key_list(".table($Q).")")as$I){$Cc=&$H[$I["id"]];if(!$Cc)$Cc=$I;$Cc["source"][]=$I["from"];$Cc["target"][]=$I["to"];}return$H;}function
view($B){global$h;return
array("select"=>preg_replace('~^(?:[^`"[]+|`[^`]*`|"[^"]*")* AS\s+~iU','',$h->result("SELECT sql FROM sqlite_master WHERE name = ".q($B))));}function
collations(){return(isset($_GET["create"])?get_vals("PRAGMA collation_list",1):array());}function
information_schema($l){return
false;}function
error(){global$h;return
h($h->error);}function
check_sqlite_name($B){global$h;$kc="db|sdb|sqlite";if(!preg_match("~^[^\\0]*\\.($kc)\$~",$B)){$h->error=lang(23,str_replace("|",", ",$kc));return
false;}return
true;}function
create_database($l,$d){global$h;if(file_exists($l)){$h->error=lang(24);return
false;}if(!check_sqlite_name($l))return
false;try{$_=new
Min_SQLite($l);}catch(Exception$ec){$h->error=$ec->getMessage();return
false;}$_->query('PRAGMA encoding = "UTF-8"');$_->query('CREATE TABLE adminer (i)');$_->query('DROP TABLE adminer');return
true;}function
drop_databases($k){global$h;$h->__construct(":memory:");foreach($k
as$l){if(!@unlink($l)){$h->error=lang(24);return
false;}}return
true;}function
rename_database($B,$d){global$h;if(!check_sqlite_name($B))return
false;$h->__construct(":memory:");$h->error=lang(24);return@rename(DB,$B);}function
auto_increment(){return" PRIMARY KEY".(DRIVER=="sqlite"?" AUTOINCREMENT":"");}function
alter_table($Q,$B,$p,$_c,$gb,$Yb,$d,$Ea,$Ee){global$h;$Ng=($Q==""||$_c);foreach($p
as$o){if($o[0]!=""||!$o[1]||$o[2]){$Ng=true;break;}}$c=array();$xe=array();foreach($p
as$o){if($o[1]){$c[]=($Ng?$o[1]:"ADD ".implode($o[1]));if($o[0]!="")$xe[$o[0]]=$o[1][0];}}if(!$Ng){foreach($c
as$X){if(!queries("ALTER TABLE ".table($Q)." $X"))return
false;}if($Q!=$B&&!queries("ALTER TABLE ".table($Q)." RENAME TO ".table($B)))return
false;}elseif(!recreate_table($Q,$B,$c,$xe,$_c,$Ea))return
false;if($Ea){queries("BEGIN");queries("UPDATE sqlite_sequence SET seq = $Ea WHERE name = ".q($B));if(!$h->affected_rows)queries("INSERT INTO sqlite_sequence (name, seq) VALUES (".q($B).", $Ea)");queries("COMMIT");}return
true;}function
recreate_table($Q,$B,$p,$xe,$_c,$Ea,$w=array()){global$h;if($Q!=""){if(!$p){foreach(fields($Q)as$y=>$o){if($w)$o["auto_increment"]=0;$p[]=process_field($o,$o);$xe[$y]=idf_escape($y);}}$Qe=false;foreach($p
as$o){if($o[6])$Qe=true;}$Ob=array();foreach($w
as$y=>$X){if($X[2]=="DROP"){$Ob[$X[1]]=true;unset($w[$y]);}}foreach(indexes($Q)as$td=>$v){$f=array();foreach($v["columns"]as$y=>$e){if(!$xe[$e])continue
2;$f[]=$xe[$e].($v["descs"][$y]?" DESC":"");}if(!$Ob[$td]){if($v["type"]!="PRIMARY"||!$Qe)$w[]=array($v["type"],$td,$f);}}foreach($w
as$y=>$X){if($X[0]=="PRIMARY"){unset($w[$y]);$_c[]="  PRIMARY KEY (".implode(", ",$X[2]).")";}}foreach(foreign_keys($Q)as$td=>$Cc){foreach($Cc["source"]as$y=>$e){if(!$xe[$e])continue
2;$Cc["source"][$y]=idf_unescape($xe[$e]);}if(!isset($_c[" $td"]))$_c[]=" ".format_foreign_key($Cc);}queries("BEGIN");}foreach($p
as$y=>$o)$p[$y]="  ".implode($o);$p=array_merge($p,array_filter($_c));$eg=($Q==$B?"adminer_$B":$B);if(!queries("CREATE TABLE ".table($eg)." (\n".implode(",\n",$p)."\n)"))return
false;if($Q!=""){if($xe&&!queries("INSERT INTO ".table($eg)." (".implode(", ",$xe).") SELECT ".implode(", ",array_map('idf_escape',array_keys($xe)))." FROM ".table($Q)))return
false;$Bg=array();foreach(triggers($Q)as$_g=>$lg){$zg=trigger($_g);$Bg[]="CREATE TRIGGER ".idf_escape($_g)." ".implode(" ",$lg)." ON ".table($B)."\n$zg[Statement]";}$Ea=$Ea?0:$h->result("SELECT seq FROM sqlite_sequence WHERE name = ".q($Q));if(!queries("DROP TABLE ".table($Q))||($Q==$B&&!queries("ALTER TABLE ".table($eg)." RENAME TO ".table($B)))||!alter_indexes($B,$w))return
false;if($Ea)queries("UPDATE sqlite_sequence SET seq = $Ea WHERE name = ".q($B));foreach($Bg
as$zg){if(!queries($zg))return
false;}queries("COMMIT");}return
true;}function
index_sql($Q,$T,$B,$f){return"CREATE $T ".($T!="INDEX"?"INDEX ":"").idf_escape($B!=""?$B:uniqid($Q."_"))." ON ".table($Q)." $f";}function
alter_indexes($Q,$c){foreach($c
as$Pe){if($Pe[0]=="PRIMARY")return
recreate_table($Q,$Q,array(),array(),array(),0,$c);}foreach(array_reverse($c)as$X){if(!queries($X[2]=="DROP"?"DROP INDEX ".idf_escape($X[1]):index_sql($Q,$X[0],$X[1],"(".implode(", ",$X[2]).")")))return
false;}return
true;}function
truncate_tables($S){return
apply_queries("DELETE FROM",$S);}function
drop_views($Vg){return
apply_queries("DROP VIEW",$Vg);}function
drop_tables($S){return
apply_queries("DROP TABLE",$S);}function
move_tables($S,$Vg,$dg){return
false;}function
trigger($B){global$h;if($B=="")return
array("Statement"=>"BEGIN\n\t;\nEND");$u='(?:[^`"\s]+|`[^`]*`|"[^"]*")+';$Ag=trigger_options();preg_match("~^CREATE\\s+TRIGGER\\s*$u\\s*(".implode("|",$Ag["Timing"]).")\\s+([a-z]+)(?:\\s+OF\\s+($u))?\\s+ON\\s*$u\\s*(?:FOR\\s+EACH\\s+ROW\\s)?(.*)~is",$h->result("SELECT sql FROM sqlite_master WHERE type = 'trigger' AND name = ".q($B)),$A);$ge=$A[3];return
array("Timing"=>strtoupper($A[1]),"Event"=>strtoupper($A[2]).($ge?" OF":""),"Of"=>idf_unescape($ge),"Trigger"=>$B,"Statement"=>$A[4],);}function
triggers($Q){$H=array();$Ag=trigger_options();foreach(get_rows("SELECT * FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q))as$I){preg_match('~^CREATE\s+TRIGGER\s*(?:[^`"\s]+|`[^`]*`|"[^"]*")+\s*('.implode("|",$Ag["Timing"]).')\s*(.*?)\s+ON\b~i',$I["sql"],$A);$H[$I["name"]]=array($A[1],$A[2]);}return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
begin(){return
queries("BEGIN");}function
last_id(){global$h;return$h->result("SELECT LAST_INSERT_ROWID()");}function
explain($h,$F){return$h->query("EXPLAIN QUERY PLAN $F");}function
found_rows($R,$Z){}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($tf){return
true;}function
create_sql($Q,$Ea,$Uf){global$h;$H=$h->result("SELECT sql FROM sqlite_master WHERE type IN ('table', 'view') AND name = ".q($Q));foreach(indexes($Q)as$B=>$v){if($B=='')continue;$H.=";\n\n".index_sql($Q,$v['type'],$B,"(".implode(", ",array_map('idf_escape',$v['columns'])).")");}return$H;}function
truncate_sql($Q){return"DELETE FROM ".table($Q);}function
use_sql($j){}function
trigger_sql($Q){return
implode(get_vals("SELECT sql || ';;\n' FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q)));}function
show_variables(){global$h;$H=array();foreach(array("auto_vacuum","cache_size","count_changes","default_cache_size","empty_result_callbacks","encoding","foreign_keys","full_column_names","fullfsync","journal_mode","journal_size_limit","legacy_file_format","locking_mode","page_size","max_page_count","read_uncommitted","recursive_triggers","reverse_unordered_selects","secure_delete","short_column_names","synchronous","temp_store","temp_store_directory","schema_version","integrity_check","quick_check")as$y)$H[$y]=$h->result("PRAGMA $y");return$H;}function
show_status(){$H=array();foreach(get_vals("PRAGMA compile_options")as$qe){list($y,$X)=explode("=",$qe,2);$H[$y]=$X;}return$H;}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
support($oc){return
preg_match('~^(columns|database|drop_col|dump|indexes|descidx|move_col|sql|status|table|trigger|variables|view|view_trigger)$~',$oc);}function
driver_config(){$U=array("integer"=>0,"real"=>0,"numeric"=>0,"text"=>0,"blob"=>0);return
array('possible_drivers'=>array((isset($_GET["sqlite"])?"SQLite3":"SQLite"),"PDO_SQLite"),'jush'=>"sqlite",'types'=>$U,'structured_types'=>array_keys($U),'unsigned'=>array(),'operators'=>array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL"),'functions'=>array("hex","length","lower","round","unixepoch","upper"),'grouping'=>array("avg","count","count distinct","group_concat","max","min","sum"),'edit_functions'=>array(array(),array("integer|real|numeric"=>"+/-","text"=>"||",)),);}}$Mb["pgsql"]="PostgreSQL";if(isset($_GET["pgsql"])){define("DRIVER","pgsql");if(extension_loaded("pgsql")){class
Min_DB{var$extension="PgSQL",$_link,$_result,$_string,$_database=true,$server_info,$affected_rows,$error,$timeout;function
_error($bc,$n){if(ini_bool("html_errors"))$n=html_entity_decode(strip_tags($n));$n=preg_replace('~^[^:]*: ~','',$n);$this->error=$n;}function
connect($M,$V,$E){global$b;$l=$b->database();set_error_handler(array($this,'_error'));$this->_string="host='".str_replace(":","' port='",addcslashes($M,"'\\"))."' user='".addcslashes($V,"'\\")."' password='".addcslashes($E,"'\\")."'";$this->_link=@pg_connect("$this->_string dbname='".($l!=""?addcslashes($l,"'\\"):"postgres")."'",PGSQL_CONNECT_FORCE_NEW);if(!$this->_link&&$l!=""){$this->_database=false;$this->_link=@pg_connect("$this->_string dbname='postgres'",PGSQL_CONNECT_FORCE_NEW);}restore_error_handler();if($this->_link){$Tg=pg_version($this->_link);$this->server_info=$Tg["server"];pg_set_client_encoding($this->_link,"UTF8");}return(bool)$this->_link;}function
quote($P){return"'".pg_escape_string($this->_link,$P)."'";}function
value($X,$o){return($o["type"]=="bytea"&&$X!==null?pg_unescape_bytea($X):$X);}function
quoteBinary($P){return"'".pg_escape_bytea($this->_link,$P)."'";}function
select_db($j){global$b;if($j==$b->database())return$this->_database;$H=@pg_connect("$this->_string dbname='".addcslashes($j,"'\\")."'",PGSQL_CONNECT_FORCE_NEW);if($H)$this->_link=$H;return$H;}function
close(){$this->_link=@pg_connect("$this->_string dbname='postgres'");}function
query($F,$Dg=false){$G=@pg_query($this->_link,$F);$this->error="";if(!$G){$this->error=pg_last_error($this->_link);$H=false;}elseif(!pg_num_fields($G)){$this->affected_rows=pg_affected_rows($G);$H=true;}else$H=new
Min_Result($G);if($this->timeout){$this->timeout=0;$this->query("RESET statement_timeout");}return$H;}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=0){$G=$this->query($F);if(!$G||!$G->num_rows)return
false;return
pg_fetch_result($G->_result,0,$o);}function
warnings(){return
h(pg_last_notice($this->_link));}}class
Min_Result{var$_result,$_offset=0,$num_rows;function
__construct($G){$this->_result=$G;$this->num_rows=pg_num_rows($G);}function
fetch_assoc(){return
pg_fetch_assoc($this->_result);}function
fetch_row(){return
pg_fetch_row($this->_result);}function
fetch_field(){$e=$this->_offset++;$H=new
stdClass;if(function_exists('pg_field_table'))$H->orgtable=pg_field_table($this->_result,$e);$H->name=pg_field_name($this->_result,$e);$H->orgname=$H->name;$H->type=pg_field_type($this->_result,$e);$H->charsetnr=($H->type=="bytea"?63:0);return$H;}function
__destruct(){pg_free_result($this->_result);}}}elseif(extension_loaded("pdo_pgsql")){class
Min_DB
extends
Min_PDO{var$extension="PDO_PgSQL",$timeout;function
connect($M,$V,$E){global$b;$l=$b->database();$this->dsn("pgsql:host='".str_replace(":","' port='",addcslashes($M,"'\\"))."' client_encoding=utf8 dbname='".($l!=""?addcslashes($l,"'\\"):"postgres")."'",$V,$E);return
true;}function
select_db($j){global$b;return($b->database()==$j);}function
quoteBinary($rf){return
q($rf);}function
query($F,$Dg=false){$H=parent::query($F,$Dg);if($this->timeout){$this->timeout=0;parent::query("RESET statement_timeout");}return$H;}function
warnings(){return'';}function
close(){}}}class
Min_Driver
extends
Min_SQL{function
insertUpdate($Q,$J,$Pe){global$h;foreach($J
as$N){$Kg=array();$Z=array();foreach($N
as$y=>$X){$Kg[]="$y = $X";if(isset($Pe[idf_unescape($y)]))$Z[]="$y = $X";}if(!(($Z&&queries("UPDATE ".table($Q)." SET ".implode(", ",$Kg)." WHERE ".implode(" AND ",$Z))&&$h->affected_rows)||queries("INSERT INTO ".table($Q)." (".implode(", ",array_keys($N)).") VALUES (".implode(", ",$N).")")))return
false;}return
true;}function
slowQuery($F,$kg){$this->_conn->query("SET statement_timeout = ".(1000*$kg));$this->_conn->timeout=1000*$kg;return$F;}function
convertSearch($u,$X,$o){return(preg_match('~char|text'.(!preg_match('~LIKE~',$X["op"])?'|date|time(stamp)?|boolean|uuid|'.number_type():'').'~',$o["type"])?$u:"CAST($u AS text)");}function
quoteBinary($rf){return$this->_conn->quoteBinary($rf);}function
warnings(){return$this->_conn->warnings();}function
tableHelp($B){$Fd=array("information_schema"=>"infoschema","pg_catalog"=>"catalog",);$_=$Fd[$_GET["ns"]];if($_)return"$_-".str_replace("_","-",$B).".html";}}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
connect(){global$b,$U,$Tf;$h=new
Min_DB;$wb=$b->credentials();if($h->connect($wb[0],$wb[1],$wb[2])){if(min_version(9,0,$h)){$h->query("SET application_name = 'Adminer'");if(min_version(9.2,0,$h)){$Tf[lang(25)][]="json";$U["json"]=4294967295;if(min_version(9.4,0,$h)){$Tf[lang(25)][]="jsonb";$U["jsonb"]=4294967295;}}}return$h;}return$h->error;}function
get_databases(){return
get_vals("SELECT datname FROM pg_database WHERE has_database_privilege(datname, 'CONNECT') ORDER BY datname");}function
limit($F,$Z,$z,$he=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z".($he?" OFFSET $he":""):"");}function
limit1($Q,$F,$Z,$L="\n"){return(preg_match('~^INTO~',$F)?limit($F,$Z,1,0,$L):" $F".(is_view(table_status1($Q))?$Z:" WHERE ctid = (SELECT ctid FROM ".table($Q).$Z.$L."LIMIT 1)"));}function
db_collation($l,$bb){global$h;return$h->result("SELECT datcollate FROM pg_database WHERE datname = ".q($l));}function
engines(){return
array();}function
logged_user(){global$h;return$h->result("SELECT user");}function
tables_list(){$F="SELECT table_name, table_type FROM information_schema.tables WHERE table_schema = current_schema()";if(support('materializedview'))$F.="
UNION ALL
SELECT matviewname, 'MATERIALIZED VIEW'
FROM pg_matviews
WHERE schemaname = current_schema()";$F.="
ORDER BY 1";return
get_key_vals($F);}function
count_tables($k){return
array();}function
table_status($B=""){$H=array();foreach(get_rows("SELECT c.relname AS \"Name\", CASE c.relkind WHEN 'r' THEN 'table' WHEN 'm' THEN 'materialized view' ELSE 'view' END AS \"Engine\", pg_relation_size(c.oid) AS \"Data_length\", pg_total_relation_size(c.oid) - pg_relation_size(c.oid) AS \"Index_length\", obj_description(c.oid, 'pg_class') AS \"Comment\", ".(min_version(12)?"''":"CASE WHEN c.relhasoids THEN 'oid' ELSE '' END")." AS \"Oid\", c.reltuples as \"Rows\", n.nspname
FROM pg_class c
JOIN pg_namespace n ON(n.nspname = current_schema() AND n.oid = c.relnamespace)
WHERE relkind IN ('r', 'm', 'v', 'f', 'p')
".($B!=""?"AND relname = ".q($B):"ORDER BY relname"))as$I)$H[$I["Name"]]=$I;return($B!=""?$H[$B]:$H);}function
is_view($R){return
in_array($R["Engine"],array("view","materialized view"));}function
fk_support($R){return
true;}function
fields($Q){$H=array();$wa=array('timestamp without time zone'=>'timestamp','timestamp with time zone'=>'timestamptz',);foreach(get_rows("SELECT a.attname AS field, format_type(a.atttypid, a.atttypmod) AS full_type, pg_get_expr(d.adbin, d.adrelid) AS default, a.attnotnull::int, col_description(c.oid, a.attnum) AS comment".(min_version(10)?", a.attidentity":"")."
FROM pg_class c
JOIN pg_namespace n ON c.relnamespace = n.oid
JOIN pg_attribute a ON c.oid = a.attrelid
LEFT JOIN pg_attrdef d ON c.oid = d.adrelid AND a.attnum = d.adnum
WHERE c.relname = ".q($Q)."
AND n.nspname = current_schema()
AND NOT a.attisdropped
AND a.attnum > 0
ORDER BY a.attnum")as$I){preg_match('~([^([]+)(\((.*)\))?([a-z ]+)?((\[[0-9]*])*)$~',$I["full_type"],$A);list(,$T,$Cd,$I["length"],$sa,$ya)=$A;$I["length"].=$ya;$Ta=$T.$sa;if(isset($wa[$Ta])){$I["type"]=$wa[$Ta];$I["full_type"]=$I["type"].$Cd.$ya;}else{$I["type"]=$T;$I["full_type"]=$I["type"].$Cd.$sa.$ya;}if(in_array($I['attidentity'],array('a','d')))$I['default']='GENERATED '.($I['attidentity']=='d'?'BY DEFAULT':'ALWAYS').' AS IDENTITY';$I["null"]=!$I["attnotnull"];$I["auto_increment"]=$I['attidentity']||preg_match('~^nextval\(~i',$I["default"]);$I["privileges"]=array("insert"=>1,"select"=>1,"update"=>1);if(preg_match('~(.+)::[^,)]+(.*)~',$I["default"],$A))$I["default"]=($A[1]=="NULL"?null:idf_unescape($A[1]).$A[2]);$H[$I["field"]]=$I;}return$H;}function
indexes($Q,$i=null){global$h;if(!is_object($i))$i=$h;$H=array();$bg=$i->result("SELECT oid FROM pg_class WHERE relnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema()) AND relname = ".q($Q));$f=get_key_vals("SELECT attnum, attname FROM pg_attribute WHERE attrelid = $bg AND attnum > 0",$i);foreach(get_rows("SELECT relname, indisunique::int, indisprimary::int, indkey, indoption, (indpred IS NOT NULL)::int as indispartial FROM pg_index i, pg_class ci WHERE i.indrelid = $bg AND ci.oid = i.indexrelid",$i)as$I){$ff=$I["relname"];$H[$ff]["type"]=($I["indispartial"]?"INDEX":($I["indisprimary"]?"PRIMARY":($I["indisunique"]?"UNIQUE":"INDEX")));$H[$ff]["columns"]=array();foreach(explode(" ",$I["indkey"])as$gd)$H[$ff]["columns"][]=$f[$gd];$H[$ff]["descs"]=array();foreach(explode(" ",$I["indoption"])as$hd)$H[$ff]["descs"][]=($hd&1?'1':null);$H[$ff]["lengths"]=array();}return$H;}function
foreign_keys($Q){global$ke;$H=array();foreach(get_rows("SELECT conname, condeferrable::int AS deferrable, pg_get_constraintdef(oid) AS definition
FROM pg_constraint
WHERE conrelid = (SELECT pc.oid FROM pg_class AS pc INNER JOIN pg_namespace AS pn ON (pn.oid = pc.relnamespace) WHERE pc.relname = ".q($Q)." AND pn.nspname = current_schema())
AND contype = 'f'::char
ORDER BY conkey, conname")as$I){if(preg_match('~FOREIGN KEY\s*\((.+)\)\s*REFERENCES (.+)\((.+)\)(.*)$~iA',$I['definition'],$A)){$I['source']=array_map('idf_unescape',array_map('trim',explode(',',$A[1])));if(preg_match('~^(("([^"]|"")+"|[^"]+)\.)?"?("([^"]|"")+"|[^"]+)$~',$A[2],$Md)){$I['ns']=idf_unescape($Md[2]);$I['table']=idf_unescape($Md[4]);}$I['target']=array_map('idf_unescape',array_map('trim',explode(',',$A[3])));$I['on_delete']=(preg_match("~ON DELETE ($ke)~",$A[4],$Md)?$Md[1]:'NO ACTION');$I['on_update']=(preg_match("~ON UPDATE ($ke)~",$A[4],$Md)?$Md[1]:'NO ACTION');$H[$I['conname']]=$I;}}return$H;}function
constraints($Q){global$ke;$H=array();foreach(get_rows("SELECT conname, consrc
FROM pg_catalog.pg_constraint
INNER JOIN pg_catalog.pg_namespace ON pg_constraint.connamespace = pg_namespace.oid
INNER JOIN pg_catalog.pg_class ON pg_constraint.conrelid = pg_class.oid AND pg_constraint.connamespace = pg_class.relnamespace
WHERE pg_constraint.contype = 'c'
AND conrelid != 0 -- handle only CONSTRAINTs here, not TYPES
AND nspname = current_schema()
AND relname = ".q($Q)."
ORDER BY connamespace, conname")as$I)$H[$I['conname']]=$I['consrc'];return$H;}function
view($B){global$h;return
array("select"=>trim($h->result("SELECT pg_get_viewdef(".$h->result("SELECT oid FROM pg_class WHERE relnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema()) AND relname = ".q($B)).")")));}function
collations(){return
array();}function
information_schema($l){return($l=="information_schema");}function
error(){global$h;$H=h($h->error);if(preg_match('~^(.*\n)?([^\n]*)\n( *)\^(\n.*)?$~s',$H,$A))$H=$A[1].preg_replace('~((?:[^&]|&[^;]*;){'.strlen($A[3]).'})(.*)~','\1<b>\2</b>',$A[2]).$A[4];return
nl_br($H);}function
create_database($l,$d){return
queries("CREATE DATABASE ".idf_escape($l).($d?" ENCODING ".idf_escape($d):""));}function
drop_databases($k){global$h;$h->close();return
apply_queries("DROP DATABASE",$k,'idf_escape');}function
rename_database($B,$d){return
queries("ALTER DATABASE ".idf_escape(DB)." RENAME TO ".idf_escape($B));}function
auto_increment(){return"";}function
alter_table($Q,$B,$p,$_c,$gb,$Yb,$d,$Ea,$Ee){$c=array();$Xe=array();if($Q!=""&&$Q!=$B)$Xe[]="ALTER TABLE ".table($Q)." RENAME TO ".table($B);foreach($p
as$o){$e=idf_escape($o[0]);$X=$o[1];if(!$X)$c[]="DROP $e";else{$Qg=$X[5];unset($X[5]);if($o[0]==""){if(isset($X[6]))$X[1]=($X[1]==" bigint"?" big":($X[1]==" smallint"?" small":" "))."serial";$c[]=($Q!=""?"ADD ":"  ").implode($X);if(isset($X[6]))$c[]=($Q!=""?"ADD":" ")." PRIMARY KEY ($X[0])";}else{if($e!=$X[0])$Xe[]="ALTER TABLE ".table($B)." RENAME $e TO $X[0]";$c[]="ALTER $e TYPE$X[1]";if(!$X[6]){$c[]="ALTER $e ".($X[3]?"SET$X[3]":"DROP DEFAULT");$c[]="ALTER $e ".($X[2]==" NULL"?"DROP NOT":"SET").$X[2];}}if($o[0]!=""||$Qg!="")$Xe[]="COMMENT ON COLUMN ".table($B).".$X[0] IS ".($Qg!=""?substr($Qg,9):"''");}}$c=array_merge($c,$_c);if($Q=="")array_unshift($Xe,"CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)");elseif($c)array_unshift($Xe,"ALTER TABLE ".table($Q)."\n".implode(",\n",$c));if($Q!=""||$gb!="")$Xe[]="COMMENT ON TABLE ".table($B)." IS ".q($gb);if($Ea!=""){}foreach($Xe
as$F){if(!queries($F))return
false;}return
true;}function
alter_indexes($Q,$c){$ub=array();$Nb=array();$Xe=array();foreach($c
as$X){if($X[0]!="INDEX")$ub[]=($X[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($X[1]):"\nADD".($X[1]!=""?" CONSTRAINT ".idf_escape($X[1]):"")." $X[0] ".($X[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$X[2]).")");elseif($X[2]=="DROP")$Nb[]=idf_escape($X[1]);else$Xe[]="CREATE INDEX ".idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q)." (".implode(", ",$X[2]).")";}if($ub)array_unshift($Xe,"ALTER TABLE ".table($Q).implode(",",$ub));if($Nb)array_unshift($Xe,"DROP INDEX ".implode(", ",$Nb));foreach($Xe
as$F){if(!queries($F))return
false;}return
true;}function
truncate_tables($S){return
queries("TRUNCATE ".implode(", ",array_map('table',$S)));return
true;}function
drop_views($Vg){return
drop_tables($Vg);}function
drop_tables($S){foreach($S
as$Q){$O=table_status($Q);if(!queries("DROP ".strtoupper($O["Engine"])." ".table($Q)))return
false;}return
true;}function
move_tables($S,$Vg,$dg){foreach(array_merge($S,$Vg)as$Q){$O=table_status($Q);if(!queries("ALTER ".strtoupper($O["Engine"])." ".table($Q)." SET SCHEMA ".idf_escape($dg)))return
false;}return
true;}function
trigger($B,$Q){if($B=="")return
array("Statement"=>"EXECUTE PROCEDURE ()");$f=array();$Z="WHERE trigger_schema = current_schema() AND event_object_table = ".q($Q)." AND trigger_name = ".q($B);foreach(get_rows("SELECT * FROM information_schema.triggered_update_columns $Z")as$I)$f[]=$I["event_object_column"];$H=array();foreach(get_rows('SELECT trigger_name AS "Trigger", action_timing AS "Timing", event_manipulation AS "Event", \'FOR EACH \' || action_orientation AS "Type", action_statement AS "Statement" FROM information_schema.triggers '."$Z ORDER BY event_manipulation DESC")as$I){if($f&&$I["Event"]=="UPDATE")$I["Event"].=" OF";$I["Of"]=implode(", ",$f);if($H)$I["Event"].=" OR $H[Event]";$H=$I;}return$H;}function
triggers($Q){$H=array();foreach(get_rows("SELECT * FROM information_schema.triggers WHERE trigger_schema = current_schema() AND event_object_table = ".q($Q))as$I){$zg=trigger($I["trigger_name"],$Q);$H[$zg["Trigger"]]=array($zg["Timing"],$zg["Event"]);}return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE","INSERT OR UPDATE","INSERT OR UPDATE OF","DELETE OR INSERT","DELETE OR UPDATE","DELETE OR UPDATE OF","DELETE OR INSERT OR UPDATE","DELETE OR INSERT OR UPDATE OF"),"Type"=>array("FOR EACH ROW","FOR EACH STATEMENT"),);}function
routine($B,$T){$J=get_rows('SELECT routine_definition AS definition, LOWER(external_language) AS language, *
FROM information_schema.routines
WHERE routine_schema = current_schema() AND specific_name = '.q($B));$H=$J[0];$H["returns"]=array("type"=>$H["type_udt_name"]);$H["fields"]=get_rows('SELECT parameter_name AS field, data_type AS type, character_maximum_length AS length, parameter_mode AS inout
FROM information_schema.parameters
WHERE specific_schema = current_schema() AND specific_name = '.q($B).'
ORDER BY ordinal_position');return$H;}function
routines(){return
get_rows('SELECT specific_name AS "SPECIFIC_NAME", routine_type AS "ROUTINE_TYPE", routine_name AS "ROUTINE_NAME", type_udt_name AS "DTD_IDENTIFIER"
FROM information_schema.routines
WHERE routine_schema = current_schema()
ORDER BY SPECIFIC_NAME');}function
routine_languages(){return
get_vals("SELECT LOWER(lanname) FROM pg_catalog.pg_language");}function
routine_id($B,$I){$H=array();foreach($I["fields"]as$o)$H[]=$o["type"];return
idf_escape($B)."(".implode(", ",$H).")";}function
last_id(){return
0;}function
explain($h,$F){return$h->query("EXPLAIN $F");}function
found_rows($R,$Z){global$h;if(preg_match("~ rows=([0-9]+)~",$h->result("EXPLAIN SELECT * FROM ".idf_escape($R["Name"]).($Z?" WHERE ".implode(" AND ",$Z):"")),$ef))return$ef[1];return
false;}function
types(){return
get_vals("SELECT typname
FROM pg_type
WHERE typnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema())
AND typtype IN ('b','d','e')
AND typelem = 0");}function
schemas(){return
get_vals("SELECT nspname FROM pg_namespace ORDER BY nspname");}function
get_schema(){global$h;return$h->result("SELECT current_schema()");}function
set_schema($sf,$i=null){global$h,$U,$Tf;if(!$i)$i=$h;$H=$i->query("SET search_path TO ".idf_escape($sf));foreach(types()as$T){if(!isset($U[$T])){$U[$T]=0;$Tf[lang(26)][]=$T;}}return$H;}function
foreign_keys_sql($Q){$H="";$O=table_status($Q);$xc=foreign_keys($Q);ksort($xc);foreach($xc
as$wc=>$vc)$H.="ALTER TABLE ONLY ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." ADD CONSTRAINT ".idf_escape($wc)." $vc[definition] ".($vc['deferrable']?'DEFERRABLE':'NOT DEFERRABLE').";\n";return($H?"$H\n":$H);}function
create_sql($Q,$Ea,$Uf){global$h;$H='';$of=array();$Af=array();$O=table_status($Q);if(is_view($O)){$Ug=view($Q);return
rtrim("CREATE VIEW ".idf_escape($Q)." AS $Ug[select]",";");}$p=fields($Q);$w=indexes($Q);ksort($w);$pb=constraints($Q);if(!$O||empty($p))return
false;$H="CREATE TABLE ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." (\n    ";foreach($p
as$pc=>$o){$De=idf_escape($o['field']).' '.$o['full_type'].default_value($o).($o['attnotnull']?" NOT NULL":"");$of[]=$De;if(preg_match('~nextval\(\'([^\']+)\'\)~',$o['default'],$Nd)){$_f=$Nd[1];$Mf=reset(get_rows(min_version(10)?"SELECT *, cache_size AS cache_value FROM pg_sequences WHERE schemaname = current_schema() AND sequencename = ".q($_f):"SELECT * FROM $_f"));$Af[]=($Uf=="DROP+CREATE"?"DROP SEQUENCE IF EXISTS $_f;\n":"")."CREATE SEQUENCE $_f INCREMENT $Mf[increment_by] MINVALUE $Mf[min_value] MAXVALUE $Mf[max_value]".($Ea&&$Mf['last_value']?" START $Mf[last_value]":"")." CACHE $Mf[cache_value];";}}if(!empty($Af))$H=implode("\n\n",$Af)."\n\n$H";foreach($w
as$bd=>$v){switch($v['type']){case'UNIQUE':$of[]="CONSTRAINT ".idf_escape($bd)." UNIQUE (".implode(', ',array_map('idf_escape',$v['columns'])).")";break;case'PRIMARY':$of[]="CONSTRAINT ".idf_escape($bd)." PRIMARY KEY (".implode(', ',array_map('idf_escape',$v['columns'])).")";break;}}foreach($pb
as$lb=>$nb)$of[]="CONSTRAINT ".idf_escape($lb)." CHECK $nb";$H.=implode(",\n    ",$of)."\n) WITH (oids = ".($O['Oid']?'true':'false').");";foreach($w
as$bd=>$v){if($v['type']=='INDEX'){$f=array();foreach($v['columns']as$y=>$X)$f[]=idf_escape($X).($v['descs'][$y]?" DESC":"");$H.="\n\nCREATE INDEX ".idf_escape($bd)." ON ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." USING btree (".implode(', ',$f).");";}}if($O['Comment'])$H.="\n\nCOMMENT ON TABLE ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." IS ".q($O['Comment']).";";foreach($p
as$pc=>$o){if($o['comment'])$H.="\n\nCOMMENT ON COLUMN ".idf_escape($O['nspname']).".".idf_escape($O['Name']).".".idf_escape($pc)." IS ".q($o['comment']).";";}return
rtrim($H,';');}function
truncate_sql($Q){return"TRUNCATE ".table($Q);}function
trigger_sql($Q){$O=table_status($Q);$H="";foreach(triggers($Q)as$yg=>$xg){$zg=trigger($yg,$O['Name']);$H.="\nCREATE TRIGGER ".idf_escape($zg['Trigger'])." $zg[Timing] $zg[Event] ON ".idf_escape($O["nspname"]).".".idf_escape($O['Name'])." $zg[Type] $zg[Statement];;\n";}return$H;}function
use_sql($j){return"\connect ".idf_escape($j);}function
show_variables(){return
get_key_vals("SHOW ALL");}function
process_list(){return
get_rows("SELECT * FROM pg_stat_activity ORDER BY ".(min_version(9.2)?"pid":"procpid"));}function
show_status(){}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
support($oc){return
preg_match('~^(database|table|columns|sql|indexes|descidx|comment|view|'.(min_version(9.3)?'materializedview|':'').'scheme|routine|processlist|sequence|trigger|type|variables|drop_col|kill|dump)$~',$oc);}function
kill_process($X){return
queries("SELECT pg_terminate_backend(".number($X).")");}function
connection_id(){return"SELECT pg_backend_pid()";}function
max_connections(){global$h;return$h->result("SHOW max_connections");}function
driver_config(){$U=array();$Tf=array();foreach(array(lang(27)=>array("smallint"=>5,"integer"=>10,"bigint"=>19,"boolean"=>1,"numeric"=>0,"real"=>7,"double precision"=>16,"money"=>20),lang(28)=>array("date"=>13,"time"=>17,"timestamp"=>20,"timestamptz"=>21,"interval"=>0),lang(25)=>array("character"=>0,"character varying"=>0,"text"=>0,"tsquery"=>0,"tsvector"=>0,"uuid"=>0,"xml"=>0),lang(29)=>array("bit"=>0,"bit varying"=>0,"bytea"=>0),lang(30)=>array("cidr"=>43,"inet"=>43,"macaddr"=>17,"txid_snapshot"=>0),lang(31)=>array("box"=>0,"circle"=>0,"line"=>0,"lseg"=>0,"path"=>0,"point"=>0,"polygon"=>0),)as$y=>$X){$U+=$X;$Tf[$y]=array_keys($X);}return
array('possible_drivers'=>array("PgSQL","PDO_PgSQL"),'jush'=>"pgsql",'types'=>$U,'structured_types'=>$Tf,'unsigned'=>array(),'operators'=>array("=","<",">","<=",">=","!=","~","!~","LIKE","LIKE %%","ILIKE","ILIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL"),'functions'=>array("char_length","lower","round","to_hex","to_timestamp","upper"),'grouping'=>array("avg","count","count distinct","max","min","sum"),'edit_functions'=>array(array("char"=>"md5","date|time"=>"now",),array(number_type()=>"+/-","date|time"=>"+ interval/- interval","char|text"=>"||",)),);}}$Mb["oracle"]="Oracle (beta)";if(isset($_GET["oracle"])){define("DRIVER","oracle");if(extension_loaded("oci8")){class
Min_DB{var$extension="oci8",$_link,$_result,$server_info,$affected_rows,$errno,$error;var$_current_db;function
_error($bc,$n){if(ini_bool("html_errors"))$n=html_entity_decode(strip_tags($n));$n=preg_replace('~^[^:]*: ~','',$n);$this->error=$n;}function
connect($M,$V,$E){$this->_link=@oci_new_connect($V,$E,$M,"AL32UTF8");if($this->_link){$this->server_info=oci_server_version($this->_link);return
true;}$n=oci_error();$this->error=$n["message"];return
false;}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($j){$this->_current_db=$j;return
true;}function
query($F,$Dg=false){$G=oci_parse($this->_link,$F);$this->error="";if(!$G){$n=oci_error($this->_link);$this->errno=$n["code"];$this->error=$n["message"];return
false;}set_error_handler(array($this,'_error'));$H=@oci_execute($G);restore_error_handler();if($H){if(oci_num_fields($G))return
new
Min_Result($G);$this->affected_rows=oci_num_rows($G);oci_free_statement($G);}return$H;}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=1){$G=$this->query($F);if(!is_object($G)||!oci_fetch($G->_result))return
false;return
oci_result($G->_result,$o);}}class
Min_Result{var$_result,$_offset=1,$num_rows;function
__construct($G){$this->_result=$G;}function
_convert($I){foreach((array)$I
as$y=>$X){if(is_a($X,'OCI-Lob'))$I[$y]=$X->load();}return$I;}function
fetch_assoc(){return$this->_convert(oci_fetch_assoc($this->_result));}function
fetch_row(){return$this->_convert(oci_fetch_row($this->_result));}function
fetch_field(){$e=$this->_offset++;$H=new
stdClass;$H->name=oci_field_name($this->_result,$e);$H->orgname=$H->name;$H->type=oci_field_type($this->_result,$e);$H->charsetnr=(preg_match("~raw|blob|bfile~",$H->type)?63:0);return$H;}function
__destruct(){oci_free_statement($this->_result);}}}elseif(extension_loaded("pdo_oci")){class
Min_DB
extends
Min_PDO{var$extension="PDO_OCI";var$_current_db;function
connect($M,$V,$E){$this->dsn("oci:dbname=//$M;charset=AL32UTF8",$V,$E);return
true;}function
select_db($j){$this->_current_db=$j;return
true;}}}class
Min_Driver
extends
Min_SQL{function
begin(){return
true;}function
insertUpdate($Q,$J,$Pe){global$h;foreach($J
as$N){$Kg=array();$Z=array();foreach($N
as$y=>$X){$Kg[]="$y = $X";if(isset($Pe[idf_unescape($y)]))$Z[]="$y = $X";}if(!(($Z&&queries("UPDATE ".table($Q)." SET ".implode(", ",$Kg)." WHERE ".implode(" AND ",$Z))&&$h->affected_rows)||queries("INSERT INTO ".table($Q)." (".implode(", ",array_keys($N)).") VALUES (".implode(", ",$N).")")))return
false;}return
true;}}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
connect(){global$b;$h=new
Min_DB;$wb=$b->credentials();if($h->connect($wb[0],$wb[1],$wb[2]))return$h;return$h->error;}function
get_databases(){return
get_vals("SELECT tablespace_name FROM user_tablespaces ORDER BY 1");}function
limit($F,$Z,$z,$he=0,$L=" "){return($he?" * FROM (SELECT t.*, rownum AS rnum FROM (SELECT $F$Z) t WHERE rownum <= ".($z+$he).") WHERE rnum > $he":($z!==null?" * FROM (SELECT $F$Z) WHERE rownum <= ".($z+$he):" $F$Z"));}function
limit1($Q,$F,$Z,$L="\n"){return" $F$Z";}function
db_collation($l,$bb){global$h;return$h->result("SELECT value FROM nls_database_parameters WHERE parameter = 'NLS_CHARACTERSET'");}function
engines(){return
array();}function
logged_user(){global$h;return$h->result("SELECT USER FROM DUAL");}function
get_current_db(){global$h;$l=$h->_current_db?$h->_current_db:DB;unset($h->_current_db);return$l;}function
where_owner($Oe,$ze="owner"){if(!$_GET["ns"])return'';return"$Oe$ze = sys_context('USERENV', 'CURRENT_SCHEMA')";}function
views_table($f){$ze=where_owner('');return"(SELECT $f FROM all_views WHERE ".($ze?$ze:"rownum < 0").")";}function
tables_list(){$Ug=views_table("view_name");$ze=where_owner(" AND ");return
get_key_vals("SELECT table_name, 'table' FROM all_tables WHERE tablespace_name = ".q(DB)."$ze
UNION SELECT view_name, 'view' FROM $Ug
ORDER BY 1");}function
count_tables($k){global$h;$H=array();foreach($k
as$l)$H[$l]=$h->result("SELECT COUNT(*) FROM all_tables WHERE tablespace_name = ".q($l));return$H;}function
table_status($B=""){$H=array();$uf=q($B);$l=get_current_db();$Ug=views_table("view_name");$ze=where_owner(" AND ");foreach(get_rows('SELECT table_name "Name", \'table\' "Engine", avg_row_len * num_rows "Data_length", num_rows "Rows" FROM all_tables WHERE tablespace_name = '.q($l).$ze.($B!=""?" AND table_name = $uf":"")."
UNION SELECT view_name, 'view', 0, 0 FROM $Ug".($B!=""?" WHERE view_name = $uf":"")."
ORDER BY 1")as$I){if($B!="")return$I;$H[$I["Name"]]=$I;}return$H;}function
is_view($R){return$R["Engine"]=="view";}function
fk_support($R){return
true;}function
fields($Q){$H=array();$ze=where_owner(" AND ");foreach(get_rows("SELECT * FROM all_tab_columns WHERE table_name = ".q($Q)."$ze ORDER BY column_id")as$I){$T=$I["DATA_TYPE"];$Cd="$I[DATA_PRECISION],$I[DATA_SCALE]";if($Cd==",")$Cd=$I["CHAR_COL_DECL_LENGTH"];$H[$I["COLUMN_NAME"]]=array("field"=>$I["COLUMN_NAME"],"full_type"=>$T.($Cd?"($Cd)":""),"type"=>strtolower($T),"length"=>$Cd,"default"=>$I["DATA_DEFAULT"],"null"=>($I["NULLABLE"]=="Y"),"privileges"=>array("insert"=>1,"select"=>1,"update"=>1),);}return$H;}function
indexes($Q,$i=null){$H=array();$ze=where_owner(" AND ","aic.table_owner");foreach(get_rows("SELECT aic.*, ac.constraint_type, atc.data_default
FROM all_ind_columns aic
LEFT JOIN all_constraints ac ON aic.index_name = ac.constraint_name AND aic.table_name = ac.table_name AND aic.index_owner = ac.owner
LEFT JOIN all_tab_cols atc ON aic.column_name = atc.column_name AND aic.table_name = atc.table_name AND aic.index_owner = atc.owner
WHERE aic.table_name = ".q($Q)."$ze
ORDER BY ac.constraint_type, aic.column_position",$i)as$I){$bd=$I["INDEX_NAME"];$eb=$I["DATA_DEFAULT"];$eb=($eb?trim($eb,'"'):$I["COLUMN_NAME"]);$H[$bd]["type"]=($I["CONSTRAINT_TYPE"]=="P"?"PRIMARY":($I["CONSTRAINT_TYPE"]=="U"?"UNIQUE":"INDEX"));$H[$bd]["columns"][]=$eb;$H[$bd]["lengths"][]=($I["CHAR_LENGTH"]&&$I["CHAR_LENGTH"]!=$I["COLUMN_LENGTH"]?$I["CHAR_LENGTH"]:null);$H[$bd]["descs"][]=($I["DESCEND"]&&$I["DESCEND"]=="DESC"?'1':null);}return$H;}function
view($B){$Ug=views_table("view_name, text");$J=get_rows('SELECT text "select" FROM '.$Ug.' WHERE view_name = '.q($B));return
reset($J);}function
collations(){return
array();}function
information_schema($l){return
false;}function
error(){global$h;return
h($h->error);}function
explain($h,$F){$h->query("EXPLAIN PLAN FOR $F");return$h->query("SELECT * FROM plan_table");}function
found_rows($R,$Z){}function
auto_increment(){return"";}function
alter_table($Q,$B,$p,$_c,$gb,$Yb,$d,$Ea,$Ee){$c=$Nb=array();$ve=($Q?fields($Q):array());foreach($p
as$o){$X=$o[1];if($X&&$o[0]!=""&&idf_escape($o[0])!=$X[0])queries("ALTER TABLE ".table($Q)." RENAME COLUMN ".idf_escape($o[0])." TO $X[0]");$ue=$ve[$o[0]];if($X&&$ue){$je=process_field($ue,$ue);if($X[2]==$je[2])$X[2]="";}if($X)$c[]=($Q!=""?($o[0]!=""?"MODIFY (":"ADD ("):"  ").implode($X).($Q!=""?")":"");else$Nb[]=idf_escape($o[0]);}if($Q=="")return
queries("CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)");return(!$c||queries("ALTER TABLE ".table($Q)."\n".implode("\n",$c)))&&(!$Nb||queries("ALTER TABLE ".table($Q)." DROP (".implode(", ",$Nb).")"))&&($Q==$B||queries("ALTER TABLE ".table($Q)." RENAME TO ".table($B)));}function
alter_indexes($Q,$c){$Nb=array();$Xe=array();foreach($c
as$X){if($X[0]!="INDEX"){$X[2]=preg_replace('~ DESC$~','',$X[2]);$ub=($X[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($X[1]):"\nADD".($X[1]!=""?" CONSTRAINT ".idf_escape($X[1]):"")." $X[0] ".($X[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$X[2]).")");array_unshift($Xe,"ALTER TABLE ".table($Q).$ub);}elseif($X[2]=="DROP")$Nb[]=idf_escape($X[1]);else$Xe[]="CREATE INDEX ".idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q)." (".implode(", ",$X[2]).")";}if($Nb)array_unshift($Xe,"DROP INDEX ".implode(", ",$Nb));foreach($Xe
as$F){if(!queries($F))return
false;}return
true;}function
foreign_keys($Q){$H=array();$F="SELECT c_list.CONSTRAINT_NAME as NAME,
c_src.COLUMN_NAME as SRC_COLUMN,
c_dest.OWNER as DEST_DB,
c_dest.TABLE_NAME as DEST_TABLE,
c_dest.COLUMN_NAME as DEST_COLUMN,
c_list.DELETE_RULE as ON_DELETE
FROM ALL_CONSTRAINTS c_list, ALL_CONS_COLUMNS c_src, ALL_CONS_COLUMNS c_dest
WHERE c_list.CONSTRAINT_NAME = c_src.CONSTRAINT_NAME
AND c_list.R_CONSTRAINT_NAME = c_dest.CONSTRAINT_NAME
AND c_list.CONSTRAINT_TYPE = 'R'
AND c_src.TABLE_NAME = ".q($Q);foreach(get_rows($F)as$I)$H[$I['NAME']]=array("db"=>$I['DEST_DB'],"table"=>$I['DEST_TABLE'],"source"=>array($I['SRC_COLUMN']),"target"=>array($I['DEST_COLUMN']),"on_delete"=>$I['ON_DELETE'],"on_update"=>null,);return$H;}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($Vg){return
apply_queries("DROP VIEW",$Vg);}function
drop_tables($S){return
apply_queries("DROP TABLE",$S);}function
last_id(){return
0;}function
schemas(){$H=get_vals("SELECT DISTINCT owner FROM dba_segments WHERE owner IN (SELECT username FROM dba_users WHERE default_tablespace NOT IN ('SYSTEM','SYSAUX')) ORDER BY 1");return($H?$H:get_vals("SELECT DISTINCT owner FROM all_tables WHERE tablespace_name = ".q(DB)." ORDER BY 1"));}function
get_schema(){global$h;return$h->result("SELECT sys_context('USERENV', 'SESSION_USER') FROM dual");}function
set_schema($tf,$i=null){global$h;if(!$i)$i=$h;return$i->query("ALTER SESSION SET CURRENT_SCHEMA = ".idf_escape($tf));}function
show_variables(){return
get_key_vals('SELECT name, display_value FROM v$parameter');}function
process_list(){return
get_rows('SELECT sess.process AS "process", sess.username AS "user", sess.schemaname AS "schema", sess.status AS "status", sess.wait_class AS "wait_class", sess.seconds_in_wait AS "seconds_in_wait", sql.sql_text AS "sql_text", sess.machine AS "machine", sess.port AS "port"
FROM v$session sess LEFT OUTER JOIN v$sql sql
ON sql.sql_id = sess.sql_id
WHERE sess.type = \'USER\'
ORDER BY PROCESS
');}function
show_status(){$J=get_rows('SELECT * FROM v$instance');return
reset($J);}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
support($oc){return
preg_match('~^(columns|database|drop_col|indexes|descidx|processlist|scheme|sql|status|table|variables|view)$~',$oc);}function
driver_config(){$U=array();$Tf=array();foreach(array(lang(27)=>array("number"=>38,"binary_float"=>12,"binary_double"=>21),lang(28)=>array("date"=>10,"timestamp"=>29,"interval year"=>12,"interval day"=>28),lang(25)=>array("char"=>2000,"varchar2"=>4000,"nchar"=>2000,"nvarchar2"=>4000,"clob"=>4294967295,"nclob"=>4294967295),lang(29)=>array("raw"=>2000,"long raw"=>2147483648,"blob"=>4294967295,"bfile"=>4294967296),)as$y=>$X){$U+=$X;$Tf[$y]=array_keys($X);}return
array('possible_drivers'=>array("OCI8","PDO_OCI"),'jush'=>"oracle",'types'=>$U,'structured_types'=>$Tf,'unsigned'=>array(),'operators'=>array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL"),'functions'=>array("length","lower","round","upper"),'grouping'=>array("avg","count","count distinct","max","min","sum"),'edit_functions'=>array(array("date"=>"current_date","timestamp"=>"current_timestamp",),array("number|float|double"=>"+/-","date|timestamp"=>"+ interval/- interval","char|clob"=>"||",)),);}}$Mb["mssql"]="MS SQL (beta)";if(isset($_GET["mssql"])){define("DRIVER","mssql");if(extension_loaded("sqlsrv")){class
Min_DB{var$extension="sqlsrv",$_link,$_result,$server_info,$affected_rows,$errno,$error;function
_get_error(){$this->error="";foreach(sqlsrv_errors()as$n){$this->errno=$n["code"];$this->error.="$n[message]\n";}$this->error=rtrim($this->error);}function
connect($M,$V,$E){global$b;$l=$b->database();$mb=array("UID"=>$V,"PWD"=>$E,"CharacterSet"=>"UTF-8");if($l!="")$mb["Database"]=$l;$this->_link=@sqlsrv_connect(preg_replace('~:~',',',$M),$mb);if($this->_link){$id=sqlsrv_server_info($this->_link);$this->server_info=$id['SQLServerVersion'];}else$this->_get_error();return(bool)$this->_link;}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($j){return$this->query("USE ".idf_escape($j));}function
query($F,$Dg=false){$G=sqlsrv_query($this->_link,$F);$this->error="";if(!$G){$this->_get_error();return
false;}return$this->store_result($G);}function
multi_query($F){$this->_result=sqlsrv_query($this->_link,$F);$this->error="";if(!$this->_result){$this->_get_error();return
false;}return
true;}function
store_result($G=null){if(!$G)$G=$this->_result;if(!$G)return
false;if(sqlsrv_field_metadata($G))return
new
Min_Result($G);$this->affected_rows=sqlsrv_rows_affected($G);return
true;}function
next_result(){return$this->_result?sqlsrv_next_result($this->_result):null;}function
result($F,$o=0){$G=$this->query($F);if(!is_object($G))return
false;$I=$G->fetch_row();return$I[$o];}}class
Min_Result{var$_result,$_offset=0,$_fields,$num_rows;function
__construct($G){$this->_result=$G;}function
_convert($I){foreach((array)$I
as$y=>$X){if(is_a($X,'DateTime'))$I[$y]=$X->format("Y-m-d H:i:s");}return$I;}function
fetch_assoc(){return$this->_convert(sqlsrv_fetch_array($this->_result,SQLSRV_FETCH_ASSOC));}function
fetch_row(){return$this->_convert(sqlsrv_fetch_array($this->_result,SQLSRV_FETCH_NUMERIC));}function
fetch_field(){if(!$this->_fields)$this->_fields=sqlsrv_field_metadata($this->_result);$o=$this->_fields[$this->_offset++];$H=new
stdClass;$H->name=$o["Name"];$H->orgname=$o["Name"];$H->type=($o["Type"]==1?254:0);return$H;}function
seek($he){for($s=0;$s<$he;$s++)sqlsrv_fetch($this->_result);}function
__destruct(){sqlsrv_free_stmt($this->_result);}}}elseif(extension_loaded("mssql")){class
Min_DB{var$extension="MSSQL",$_link,$_result,$server_info,$affected_rows,$error;function
connect($M,$V,$E){$this->_link=@mssql_connect($M,$V,$E);if($this->_link){$G=$this->query("SELECT SERVERPROPERTY('ProductLevel'), SERVERPROPERTY('Edition')");if($G){$I=$G->fetch_row();$this->server_info=$this->result("sp_server_info 2",2)." [$I[0]] $I[1]";}}else$this->error=mssql_get_last_message();return(bool)$this->_link;}function
quote($P){return"'".str_replace("'","''",$P)."'";}function
select_db($j){return
mssql_select_db($j);}function
query($F,$Dg=false){$G=@mssql_query($F,$this->_link);$this->error="";if(!$G){$this->error=mssql_get_last_message();return
false;}if($G===true){$this->affected_rows=mssql_rows_affected($this->_link);return
true;}return
new
Min_Result($G);}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
mssql_next_result($this->_result->_result);}function
result($F,$o=0){$G=$this->query($F);if(!is_object($G))return
false;return
mssql_result($G->_result,0,$o);}}class
Min_Result{var$_result,$_offset=0,$_fields,$num_rows;function
__construct($G){$this->_result=$G;$this->num_rows=mssql_num_rows($G);}function
fetch_assoc(){return
mssql_fetch_assoc($this->_result);}function
fetch_row(){return
mssql_fetch_row($this->_result);}function
num_rows(){return
mssql_num_rows($this->_result);}function
fetch_field(){$H=mssql_fetch_field($this->_result);$H->orgtable=$H->table;$H->orgname=$H->name;return$H;}function
seek($he){mssql_data_seek($this->_result,$he);}function
__destruct(){mssql_free_result($this->_result);}}}elseif(extension_loaded("pdo_dblib")){class
Min_DB
extends
Min_PDO{var$extension="PDO_DBLIB";function
connect($M,$V,$E){$this->dsn("dblib:charset=utf8;host=".str_replace(":",";unix_socket=",preg_replace('~:(\d)~',';port=\1',$M)),$V,$E);return
true;}function
select_db($j){return$this->query("USE ".idf_escape($j));}}}class
Min_Driver
extends
Min_SQL{function
insertUpdate($Q,$J,$Pe){foreach($J
as$N){$Kg=array();$Z=array();foreach($N
as$y=>$X){$Kg[]="$y = $X";if(isset($Pe[idf_unescape($y)]))$Z[]="$y = $X";}if(!queries("MERGE ".table($Q)." USING (VALUES(".implode(", ",$N).")) AS source (c".implode(", c",range(1,count($N))).") ON ".implode(" AND ",$Z)." WHEN MATCHED THEN UPDATE SET ".implode(", ",$Kg)." WHEN NOT MATCHED THEN INSERT (".implode(", ",array_keys($N)).") VALUES (".implode(", ",$N).");"))return
false;}return
true;}function
begin(){return
queries("BEGIN TRANSACTION");}}function
idf_escape($u){return"[".str_replace("]","]]",$u)."]";}function
table($u){return($_GET["ns"]!=""?idf_escape($_GET["ns"]).".":"").idf_escape($u);}function
connect(){global$b;$h=new
Min_DB;$wb=$b->credentials();if($h->connect($wb[0],$wb[1],$wb[2]))return$h;return$h->error;}function
get_databases(){return
get_vals("SELECT name FROM sys.databases WHERE name NOT IN ('master', 'tempdb', 'model', 'msdb')");}function
limit($F,$Z,$z,$he=0,$L=" "){return($z!==null?" TOP (".($z+$he).")":"")." $F$Z";}function
limit1($Q,$F,$Z,$L="\n"){return
limit($F,$Z,1,0,$L);}function
db_collation($l,$bb){global$h;return$h->result("SELECT collation_name FROM sys.databases WHERE name = ".q($l));}function
engines(){return
array();}function
logged_user(){global$h;return$h->result("SELECT SUSER_NAME()");}function
tables_list(){return
get_key_vals("SELECT name, type_desc FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ORDER BY name");}function
count_tables($k){global$h;$H=array();foreach($k
as$l){$h->select_db($l);$H[$l]=$h->result("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES");}return$H;}function
table_status($B=""){$H=array();foreach(get_rows("SELECT ao.name AS Name, ao.type_desc AS Engine, (SELECT value FROM fn_listextendedproperty(default, 'SCHEMA', schema_name(schema_id), 'TABLE', ao.name, null, null)) AS Comment FROM sys.all_objects AS ao WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ".($B!=""?"AND name = ".q($B):"ORDER BY name"))as$I){if($B!="")return$I;$H[$I["Name"]]=$I;}return$H;}function
is_view($R){return$R["Engine"]=="VIEW";}function
fk_support($R){return
true;}function
fields($Q){$hb=get_key_vals("SELECT objname, cast(value as varchar(max)) FROM fn_listextendedproperty('MS_DESCRIPTION', 'schema', ".q(get_schema()).", 'table', ".q($Q).", 'column', NULL)");$H=array();foreach(get_rows("SELECT c.max_length, c.precision, c.scale, c.name, c.is_nullable, c.is_identity, c.collation_name, t.name type, CAST(d.definition as text) [default]
FROM sys.all_columns c
JOIN sys.all_objects o ON c.object_id = o.object_id
JOIN sys.types t ON c.user_type_id = t.user_type_id
LEFT JOIN sys.default_constraints d ON c.default_object_id = d.parent_column_id
WHERE o.schema_id = SCHEMA_ID(".q(get_schema()).") AND o.type IN ('S', 'U', 'V') AND o.name = ".q($Q))as$I){$T=$I["type"];$Cd=(preg_match("~char|binary~",$T)?$I["max_length"]:($T=="decimal"?"$I[precision],$I[scale]":""));$H[$I["name"]]=array("field"=>$I["name"],"full_type"=>$T.($Cd?"($Cd)":""),"type"=>$T,"length"=>$Cd,"default"=>$I["default"],"null"=>$I["is_nullable"],"auto_increment"=>$I["is_identity"],"collation"=>$I["collation_name"],"privileges"=>array("insert"=>1,"select"=>1,"update"=>1),"primary"=>$I["is_identity"],"comment"=>$hb[$I["name"]],);}return$H;}function
indexes($Q,$i=null){$H=array();foreach(get_rows("SELECT i.name, key_ordinal, is_unique, is_primary_key, c.name AS column_name, is_descending_key
FROM sys.indexes i
INNER JOIN sys.index_columns ic ON i.object_id = ic.object_id AND i.index_id = ic.index_id
INNER JOIN sys.columns c ON ic.object_id = c.object_id AND ic.column_id = c.column_id
WHERE OBJECT_NAME(i.object_id) = ".q($Q),$i)as$I){$B=$I["name"];$H[$B]["type"]=($I["is_primary_key"]?"PRIMARY":($I["is_unique"]?"UNIQUE":"INDEX"));$H[$B]["lengths"]=array();$H[$B]["columns"][$I["key_ordinal"]]=$I["column_name"];$H[$B]["descs"][$I["key_ordinal"]]=($I["is_descending_key"]?'1':null);}return$H;}function
view($B){global$h;return
array("select"=>preg_replace('~^(?:[^[]|\[[^]]*])*\s+AS\s+~isU','',$h->result("SELECT VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = SCHEMA_NAME() AND TABLE_NAME = ".q($B))));}function
collations(){$H=array();foreach(get_vals("SELECT name FROM fn_helpcollations()")as$d)$H[preg_replace('~_.*~','',$d)][]=$d;return$H;}function
information_schema($l){return
false;}function
error(){global$h;return
nl_br(h(preg_replace('~^(\[[^]]*])+~m','',$h->error)));}function
create_database($l,$d){return
queries("CREATE DATABASE ".idf_escape($l).(preg_match('~^[a-z0-9_]+$~i',$d)?" COLLATE $d":""));}function
drop_databases($k){return
queries("DROP DATABASE ".implode(", ",array_map('idf_escape',$k)));}function
rename_database($B,$d){if(preg_match('~^[a-z0-9_]+$~i',$d))queries("ALTER DATABASE ".idf_escape(DB)." COLLATE $d");queries("ALTER DATABASE ".idf_escape(DB)." MODIFY NAME = ".idf_escape($B));return
true;}function
auto_increment(){return" IDENTITY".($_POST["Auto_increment"]!=""?"(".number($_POST["Auto_increment"]).",1)":"")." PRIMARY KEY";}function
alter_table($Q,$B,$p,$_c,$gb,$Yb,$d,$Ea,$Ee){$c=array();$hb=array();foreach($p
as$o){$e=idf_escape($o[0]);$X=$o[1];if(!$X)$c["DROP"][]=" COLUMN $e";else{$X[1]=preg_replace("~( COLLATE )'(\\w+)'~",'\1\2',$X[1]);$hb[$o[0]]=$X[5];unset($X[5]);if($o[0]=="")$c["ADD"][]="\n  ".implode("",$X).($Q==""?substr($_c[$X[0]],16+strlen($X[0])):"");else{unset($X[6]);if($e!=$X[0])queries("EXEC sp_rename ".q(table($Q).".$e").", ".q(idf_unescape($X[0])).", 'COLUMN'");$c["ALTER COLUMN ".implode("",$X)][]="";}}}if($Q=="")return
queries("CREATE TABLE ".table($B)." (".implode(",",(array)$c["ADD"])."\n)");if($Q!=$B)queries("EXEC sp_rename ".q(table($Q)).", ".q($B));if($_c)$c[""]=$_c;foreach($c
as$y=>$X){if(!queries("ALTER TABLE ".idf_escape($B)." $y".implode(",",$X)))return
false;}foreach($hb
as$y=>$X){$gb=substr($X,9);queries("EXEC sp_dropextendedproperty @name = N'MS_Description', @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table', @level1name = ".q($B).", @level2type = N'Column', @level2name = ".q($y));queries("EXEC sp_addextendedproperty @name = N'MS_Description', @value = ".$gb.", @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table', @level1name = ".q($B).", @level2type = N'Column', @level2name = ".q($y));}return
true;}function
alter_indexes($Q,$c){$v=array();$Nb=array();foreach($c
as$X){if($X[2]=="DROP"){if($X[0]=="PRIMARY")$Nb[]=idf_escape($X[1]);else$v[]=idf_escape($X[1])." ON ".table($Q);}elseif(!queries(($X[0]!="PRIMARY"?"CREATE $X[0] ".($X[0]!="INDEX"?"INDEX ":"").idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q):"ALTER TABLE ".table($Q)." ADD PRIMARY KEY")." (".implode(", ",$X[2]).")"))return
false;}return(!$v||queries("DROP INDEX ".implode(", ",$v)))&&(!$Nb||queries("ALTER TABLE ".table($Q)." DROP ".implode(", ",$Nb)));}function
last_id(){global$h;return$h->result("SELECT SCOPE_IDENTITY()");}function
explain($h,$F){$h->query("SET SHOWPLAN_ALL ON");$H=$h->query($F);$h->query("SET SHOWPLAN_ALL OFF");return$H;}function
found_rows($R,$Z){}function
foreign_keys($Q){$H=array();foreach(get_rows("EXEC sp_fkeys @fktable_name = ".q($Q))as$I){$Cc=&$H[$I["FK_NAME"]];$Cc["db"]=$I["PKTABLE_QUALIFIER"];$Cc["table"]=$I["PKTABLE_NAME"];$Cc["source"][]=$I["FKCOLUMN_NAME"];$Cc["target"][]=$I["PKCOLUMN_NAME"];}return$H;}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($Vg){return
queries("DROP VIEW ".implode(", ",array_map('table',$Vg)));}function
drop_tables($S){return
queries("DROP TABLE ".implode(", ",array_map('table',$S)));}function
move_tables($S,$Vg,$dg){return
apply_queries("ALTER SCHEMA ".idf_escape($dg)." TRANSFER",array_merge($S,$Vg));}function
trigger($B){if($B=="")return
array();$J=get_rows("SELECT s.name [Trigger],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(s.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(s.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing],
c.text
FROM sysobjects s
JOIN syscomments c ON s.id = c.id
WHERE s.xtype = 'TR' AND s.name = ".q($B));$H=reset($J);if($H)$H["Statement"]=preg_replace('~^.+\s+AS\s+~isU','',$H["text"]);return$H;}function
triggers($Q){$H=array();foreach(get_rows("SELECT sys1.name,
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing]
FROM sysobjects sys1
JOIN sysobjects sys2 ON sys1.parent_obj = sys2.id
WHERE sys1.xtype = 'TR' AND sys2.name = ".q($Q))as$I)$H[$I["name"]]=array($I["Timing"],$I["Event"]);return$H;}function
trigger_options(){return
array("Timing"=>array("AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("AS"),);}function
schemas(){return
get_vals("SELECT name FROM sys.schemas");}function
get_schema(){global$h;if($_GET["ns"]!="")return$_GET["ns"];return$h->result("SELECT SCHEMA_NAME()");}function
set_schema($sf){return
true;}function
use_sql($j){return"USE ".idf_escape($j);}function
show_variables(){return
array();}function
show_status(){return
array();}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
support($oc){return
preg_match('~^(comment|columns|database|drop_col|indexes|descidx|scheme|sql|table|trigger|view|view_trigger)$~',$oc);}function
driver_config(){$U=array();$Tf=array();foreach(array(lang(27)=>array("tinyint"=>3,"smallint"=>5,"int"=>10,"bigint"=>20,"bit"=>1,"decimal"=>0,"real"=>12,"float"=>53,"smallmoney"=>10,"money"=>20),lang(28)=>array("date"=>10,"smalldatetime"=>19,"datetime"=>19,"datetime2"=>19,"time"=>8,"datetimeoffset"=>10),lang(25)=>array("char"=>8000,"varchar"=>8000,"text"=>2147483647,"nchar"=>4000,"nvarchar"=>4000,"ntext"=>1073741823),lang(29)=>array("binary"=>8000,"varbinary"=>8000,"image"=>2147483647),)as$y=>$X){$U+=$X;$Tf[$y]=array_keys($X);}return
array('possible_drivers'=>array("SQLSRV","MSSQL","PDO_DBLIB"),'jush'=>"mssql",'types'=>$U,'structured_types'=>$Tf,'unsigned'=>array(),'operators'=>array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL"),'functions'=>array("len","lower","round","upper"),'grouping'=>array("avg","count","count distinct","max","min","sum"),'edit_functions'=>array(array("date|time"=>"getdate",),array("int|decimal|real|float|money|datetime"=>"+/-","char|text"=>"+",)),);}}$Mb["mongo"]="MongoDB (alpha)";if(isset($_GET["mongo"])){define("DRIVER","mongo");if(class_exists('MongoDB')){class
Min_DB{var$extension="Mongo",$server_info=MongoClient::VERSION,$error,$last_id,$_link,$_db;function
connect($Lg,$C){try{$this->_link=new
MongoClient($Lg,$C);if($C["password"]!=""){$C["password"]="";try{new
MongoClient($Lg,$C);$this->error=lang(22);}catch(Exception$Qb){}}}catch(Exception$Qb){$this->error=$Qb->getMessage();}}function
query($F){return
false;}function
select_db($j){try{$this->_db=$this->_link->selectDB($j);return
true;}catch(Exception$ec){$this->error=$ec->getMessage();return
false;}}function
quote($P){return$P;}}class
Min_Result{var$num_rows,$_rows=array(),$_offset=0,$_charset=array();function
__construct($G){foreach($G
as$rd){$I=array();foreach($rd
as$y=>$X){if(is_a($X,'MongoBinData'))$this->_charset[$y]=63;$I[$y]=(is_a($X,'MongoId')?"ObjectId(\"$X\")":(is_a($X,'MongoDate')?gmdate("Y-m-d H:i:s",$X->sec)." GMT":(is_a($X,'MongoBinData')?$X->bin:(is_a($X,'MongoRegex')?"$X":(is_object($X)?get_class($X):$X)))));}$this->_rows[]=$I;foreach($I
as$y=>$X){if(!isset($this->_rows[0][$y]))$this->_rows[0][$y]=null;}}$this->num_rows=count($this->_rows);}function
fetch_assoc(){$I=current($this->_rows);if(!$I)return$I;$H=array();foreach($this->_rows[0]as$y=>$X)$H[$y]=$I[$y];next($this->_rows);return$H;}function
fetch_row(){$H=$this->fetch_assoc();if(!$H)return$H;return
array_values($H);}function
fetch_field(){$ud=array_keys($this->_rows[0]);$B=$ud[$this->_offset++];return(object)array('name'=>$B,'charsetnr'=>$this->_charset[$B],);}}class
Min_Driver
extends
Min_SQL{public$Pe="_id";function
select($Q,$K,$Z,$Jc,$se=array(),$z=1,$D=0,$Re=false){$K=($K==array("*")?array():array_fill_keys($K,true));$Jf=array();foreach($se
as$X){$X=preg_replace('~ DESC$~','',$X,1,$sb);$Jf[$X]=($sb?-1:1);}return
new
Min_Result($this->_conn->_db->selectCollection($Q)->find(array(),$K)->sort($Jf)->limit($z!=""?+$z:0)->skip($D*$z));}function
insert($Q,$N){try{$H=$this->_conn->_db->selectCollection($Q)->insert($N);$this->_conn->errno=$H['code'];$this->_conn->error=$H['err'];$this->_conn->last_id=$N['_id'];return!$H['err'];}catch(Exception$ec){$this->_conn->error=$ec->getMessage();return
false;}}}function
get_databases($yc){global$h;$H=array();$Bb=$h->_link->listDBs();foreach($Bb['databases']as$l)$H[]=$l['name'];return$H;}function
count_tables($k){global$h;$H=array();foreach($k
as$l)$H[$l]=count($h->_link->selectDB($l)->getCollectionNames(true));return$H;}function
tables_list(){global$h;return
array_fill_keys($h->_db->getCollectionNames(true),'table');}function
drop_databases($k){global$h;foreach($k
as$l){$kf=$h->_link->selectDB($l)->drop();if(!$kf['ok'])return
false;}return
true;}function
indexes($Q,$i=null){global$h;$H=array();foreach($h->_db->selectCollection($Q)->getIndexInfo()as$v){$Hb=array();foreach($v["key"]as$e=>$T)$Hb[]=($T==-1?'1':null);$H[$v["name"]]=array("type"=>($v["name"]=="_id_"?"PRIMARY":($v["unique"]?"UNIQUE":"INDEX")),"columns"=>array_keys($v["key"]),"lengths"=>array(),"descs"=>$Hb,);}return$H;}function
fields($Q){return
fields_from_edit();}function
found_rows($R,$Z){global$h;return$h->_db->selectCollection($_GET["select"])->count($Z);}$pe=array("=");}elseif(class_exists('MongoDB\Driver\Manager')){class
Min_DB{var$extension="MongoDB",$server_info=MONGODB_VERSION,$affected_rows,$error,$last_id;var$_link;var$_db,$_db_name;function
connect($Lg,$C){$Xa='MongoDB\Driver\Manager';$this->_link=new$Xa($Lg,$C);$this->executeCommand('admin',array('ping'=>1));}function
executeCommand($l,$fb){$Xa='MongoDB\Driver\Command';try{return$this->_link->executeCommand($l,new$Xa($fb));}catch(Exception$Qb){$this->error=$Qb->getMessage();return
array();}}function
executeBulkWrite($be,$Qa,$tb){try{$nf=$this->_link->executeBulkWrite($be,$Qa);$this->affected_rows=$nf->$tb();return
true;}catch(Exception$Qb){$this->error=$Qb->getMessage();return
false;}}function
query($F){return
false;}function
select_db($j){$this->_db_name=$j;return
true;}function
quote($P){return$P;}}class
Min_Result{var$num_rows,$_rows=array(),$_offset=0,$_charset=array();function
__construct($G){foreach($G
as$rd){$I=array();foreach($rd
as$y=>$X){if(is_a($X,'MongoDB\BSON\Binary'))$this->_charset[$y]=63;$I[$y]=(is_a($X,'MongoDB\BSON\ObjectID')?'MongoDB\BSON\ObjectID("'."$X\")":(is_a($X,'MongoDB\BSON\UTCDatetime')?$X->toDateTime()->format('Y-m-d H:i:s'):(is_a($X,'MongoDB\BSON\Binary')?$X->getData():(is_a($X,'MongoDB\BSON\Regex')?"$X":(is_object($X)||is_array($X)?json_encode($X,256):$X)))));}$this->_rows[]=$I;foreach($I
as$y=>$X){if(!isset($this->_rows[0][$y]))$this->_rows[0][$y]=null;}}$this->num_rows=count($this->_rows);}function
fetch_assoc(){$I=current($this->_rows);if(!$I)return$I;$H=array();foreach($this->_rows[0]as$y=>$X)$H[$y]=$I[$y];next($this->_rows);return$H;}function
fetch_row(){$H=$this->fetch_assoc();if(!$H)return$H;return
array_values($H);}function
fetch_field(){$ud=array_keys($this->_rows[0]);$B=$ud[$this->_offset++];return(object)array('name'=>$B,'charsetnr'=>$this->_charset[$B],);}}class
Min_Driver
extends
Min_SQL{public$Pe="_id";function
select($Q,$K,$Z,$Jc,$se=array(),$z=1,$D=0,$Re=false){global$h;$K=($K==array("*")?array():array_fill_keys($K,1));if(count($K)&&!isset($K['_id']))$K['_id']=0;$Z=where_to_query($Z);$Jf=array();foreach($se
as$X){$X=preg_replace('~ DESC$~','',$X,1,$sb);$Jf[$X]=($sb?-1:1);}if(isset($_GET['limit'])&&is_numeric($_GET['limit'])&&$_GET['limit']>0)$z=$_GET['limit'];$z=min(200,max(1,(int)$z));$Gf=$D*$z;$Xa='MongoDB\Driver\Query';try{return
new
Min_Result($h->_link->executeQuery("$h->_db_name.$Q",new$Xa($Z,array('projection'=>$K,'limit'=>$z,'skip'=>$Gf,'sort'=>$Jf))));}catch(Exception$Qb){$h->error=$Qb->getMessage();return
false;}}function
update($Q,$N,$Ye,$z=0,$L="\n"){global$h;$l=$h->_db_name;$Z=sql_query_where_parser($Ye);$Xa='MongoDB\Driver\BulkWrite';$Qa=new$Xa(array());if(isset($N['_id']))unset($N['_id']);$gf=array();foreach($N
as$y=>$Y){if($Y=='NULL'){$gf[$y]=1;unset($N[$y]);}}$Kg=array('$set'=>$N);if(count($gf))$Kg['$unset']=$gf;$Qa->update($Z,$Kg,array('upsert'=>false));return$h->executeBulkWrite("$l.$Q",$Qa,'getModifiedCount');}function
delete($Q,$Ye,$z=0){global$h;$l=$h->_db_name;$Z=sql_query_where_parser($Ye);$Xa='MongoDB\Driver\BulkWrite';$Qa=new$Xa(array());$Qa->delete($Z,array('limit'=>$z));return$h->executeBulkWrite("$l.$Q",$Qa,'getDeletedCount');}function
insert($Q,$N){global$h;$l=$h->_db_name;$Xa='MongoDB\Driver\BulkWrite';$Qa=new$Xa(array());if($N['_id']=='')unset($N['_id']);$Qa->insert($N);return$h->executeBulkWrite("$l.$Q",$Qa,'getInsertedCount');}}function
get_databases($yc){global$h;$H=array();foreach($h->executeCommand('admin',array('listDatabases'=>1))as$Bb){foreach($Bb->databases
as$l)$H[]=$l->name;}return$H;}function
count_tables($k){$H=array();return$H;}function
tables_list(){global$h;$cb=array();foreach($h->executeCommand($h->_db_name,array('listCollections'=>1))as$G)$cb[$G->name]='table';return$cb;}function
drop_databases($k){return
false;}function
indexes($Q,$i=null){global$h;$H=array();foreach($h->executeCommand($h->_db_name,array('listIndexes'=>$Q))as$v){$Hb=array();$f=array();foreach(get_object_vars($v->key)as$e=>$T){$Hb[]=($T==-1?'1':null);$f[]=$e;}$H[$v->name]=array("type"=>($v->name=="_id_"?"PRIMARY":(isset($v->unique)?"UNIQUE":"INDEX")),"columns"=>$f,"lengths"=>array(),"descs"=>$Hb,);}return$H;}function
fields($Q){global$m;$p=fields_from_edit();if(!$p){$G=$m->select($Q,array("*"),null,null,array(),10);if($G){while($I=$G->fetch_assoc()){foreach($I
as$y=>$X){$I[$y]=null;$p[$y]=array("field"=>$y,"type"=>"string","null"=>($y!=$m->primary),"auto_increment"=>($y==$m->primary),"privileges"=>array("insert"=>1,"select"=>1,"update"=>1,),);}}}}return$p;}function
found_rows($R,$Z){global$h;$Z=where_to_query($Z);$rg=$h->executeCommand($h->_db_name,array('count'=>$R['Name'],'query'=>$Z))->toArray();return$rg[0]->n;}function
sql_query_where_parser($Ye){$Ye=preg_replace('~^\sWHERE \(?\(?(.+?)\)?\)?$~','\1',$Ye);$dh=explode(' AND ',$Ye);$eh=explode(') OR (',$Ye);$Z=array();foreach($dh
as$bh)$Z[]=trim($bh);if(count($eh)==1)$eh=array();elseif(count($eh)>1)$Z=array();return
where_to_query($Z,$eh);}function
where_to_query($Zg=array(),$ah=array()){global$b;$_b=array();foreach(array('and'=>$Zg,'or'=>$ah)as$T=>$Z){if(is_array($Z)){foreach($Z
as$hc){list($ab,$ne,$X)=explode(" ",$hc,3);if($ab=="_id"&&preg_match('~^(MongoDB\\\\BSON\\\\ObjectID)\("(.+)"\)$~',$X,$A)){list(,$Xa,$X)=$A;$X=new$Xa($X);}if(!in_array($ne,$b->operators))continue;if(preg_match('~^\(f\)(.+)~',$ne,$A)){$X=(float)$X;$ne=$A[1];}elseif(preg_match('~^\(date\)(.+)~',$ne,$A)){$Ab=new
DateTime($X);$Xa='MongoDB\BSON\UTCDatetime';$X=new$Xa($Ab->getTimestamp()*1000);$ne=$A[1];}switch($ne){case'=':$ne='$eq';break;case'!=':$ne='$ne';break;case'>':$ne='$gt';break;case'<':$ne='$lt';break;case'>=':$ne='$gte';break;case'<=':$ne='$lte';break;case'regex':$ne='$regex';break;default:continue
2;}if($T=='and')$_b['$and'][]=array($ab=>array($ne=>$X));elseif($T=='or')$_b['$or'][]=array($ab=>array($ne=>$X));}}}return$_b;}$pe=array("=","!=",">","<",">=","<=","regex","(f)=","(f)!=","(f)>","(f)<","(f)>=","(f)<=","(date)=","(date)!=","(date)>","(date)<","(date)>=","(date)<=",);}function
table($u){return$u;}function
idf_escape($u){return$u;}function
table_status($B="",$nc=false){$H=array();foreach(tables_list()as$Q=>$T){$H[$Q]=array("Name"=>$Q);if($B==$Q)return$H[$Q];}return$H;}function
create_database($l,$d){return
true;}function
last_id(){global$h;return$h->last_id;}function
error(){global$h;return
h($h->error);}function
collations(){return
array();}function
logged_user(){global$b;$wb=$b->credentials();return$wb[1];}function
connect(){global$b;$h=new
Min_DB;list($M,$V,$E)=$b->credentials();$C=array();if($V.$E!=""){$C["username"]=$V;$C["password"]=$E;}$l=$b->database();if($l!="")$C["db"]=$l;if(($Da=getenv("MONGO_AUTH_SOURCE")))$C["authSource"]=$Da;$h->connect("mongodb://$M",$C);if($h->error)return$h->error;return$h;}function
alter_indexes($Q,$c){global$h;foreach($c
as$X){list($T,$B,$N)=$X;if($N=="DROP")$H=$h->_db->command(array("deleteIndexes"=>$Q,"index"=>$B));else{$f=array();foreach($N
as$e){$e=preg_replace('~ DESC$~','',$e,1,$sb);$f[$e]=($sb?-1:1);}$H=$h->_db->selectCollection($Q)->ensureIndex($f,array("unique"=>($T=="UNIQUE"),"name"=>$B,));}if($H['errmsg']){$h->error=$H['errmsg'];return
false;}}return
true;}function
support($oc){return
preg_match("~database|indexes|descidx~",$oc);}function
db_collation($l,$bb){}function
information_schema(){}function
is_view($R){}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
foreign_keys($Q){return
array();}function
fk_support($R){}function
engines(){return
array();}function
alter_table($Q,$B,$p,$_c,$gb,$Yb,$d,$Ea,$Ee){global$h;if($Q==""){$h->_db->createCollection($B);return
true;}}function
drop_tables($S){global$h;foreach($S
as$Q){$kf=$h->_db->selectCollection($Q)->drop();if(!$kf['ok'])return
false;}return
true;}function
truncate_tables($S){global$h;foreach($S
as$Q){$kf=$h->_db->selectCollection($Q)->remove();if(!$kf['ok'])return
false;}return
true;}function
driver_config(){global$pe;return
array('possible_drivers'=>array("mongo","mongodb"),'jush'=>"mongo",'operators'=>$pe,'functions'=>array(),'grouping'=>array(),'edit_functions'=>array(array("json")),);}}$Mb["elastic"]="Elasticsearch (beta)";if(isset($_GET["elastic"])){define("DRIVER","elastic");if(function_exists('json_decode')&&ini_bool('allow_url_fopen')){class
Min_DB{var$extension="JSON",$server_info,$errno,$error,$_url,$_db;function
rootQuery($Ge,$qb=array(),$Vd='GET'){@ini_set('track_errors',1);$rc=@file_get_contents("$this->_url/".ltrim($Ge,'/'),false,stream_context_create(array('http'=>array('method'=>$Vd,'content'=>$qb===null?$qb:json_encode($qb),'header'=>'Content-Type: application/json','ignore_errors'=>1,))));if(!$rc){$this->error=$php_errormsg;return$rc;}if(!preg_match('~^HTTP/[0-9.]+ 2~i',$http_response_header[0])){$this->error=lang(32)." $http_response_header[0]";return
false;}$H=json_decode($rc,true);if($H===null){$this->errno=json_last_error();if(function_exists('json_last_error_msg'))$this->error=json_last_error_msg();else{$ob=get_defined_constants(true);foreach($ob['json']as$B=>$Y){if($Y==$this->errno&&preg_match('~^JSON_ERROR_~',$B)){$this->error=$B;break;}}}}return$H;}function
query($Ge,$qb=array(),$Vd='GET'){return$this->rootQuery(($this->_db!=""?"$this->_db/":"/").ltrim($Ge,'/'),$qb,$Vd);}function
connect($M,$V,$E){preg_match('~^(https?://)?(.*)~',$M,$A);$this->_url=($A[1]?$A[1]:"http://")."$V:$E@$A[2]";$H=$this->query('');if($H)$this->server_info=$H['version']['number'];return(bool)$H;}function
select_db($j){$this->_db=$j;return
true;}function
quote($P){return$P;}}class
Min_Result{var$num_rows,$_rows;function
__construct($J){$this->num_rows=count($J);$this->_rows=$J;reset($this->_rows);}function
fetch_assoc(){$H=current($this->_rows);next($this->_rows);return$H;}function
fetch_row(){return
array_values($this->fetch_assoc());}}}class
Min_Driver
extends
Min_SQL{function
select($Q,$K,$Z,$Jc,$se=array(),$z=1,$D=0,$Re=false){global$b;$_b=array();$F="$Q/_search";if($K!=array("*"))$_b["fields"]=$K;if($se){$Jf=array();foreach($se
as$ab){$ab=preg_replace('~ DESC$~','',$ab,1,$sb);$Jf[]=($sb?array($ab=>"desc"):$ab);}$_b["sort"]=$Jf;}if($z){$_b["size"]=+$z;if($D)$_b["from"]=($D*$z);}foreach($Z
as$X){list($ab,$ne,$X)=explode(" ",$X,3);if($ab=="_id")$_b["query"]["ids"]["values"][]=$X;elseif($ab.$X!=""){$fg=array("term"=>array(($ab!=""?$ab:"_all")=>$X));if($ne=="=")$_b["query"]["filtered"]["filter"]["and"][]=$fg;else$_b["query"]["filtered"]["query"]["bool"]["must"][]=$fg;}}if($_b["query"]&&!$_b["query"]["filtered"]["query"]&&!$_b["query"]["ids"])$_b["query"]["filtered"]["query"]=array("match_all"=>array());$Qf=microtime(true);$uf=$this->_conn->query($F,$_b);if($Re)echo$b->selectQuery("$F: ".json_encode($_b),$Qf,!$uf);if(!$uf)return
false;$H=array();foreach($uf['hits']['hits']as$Vc){$I=array();if($K==array("*"))$I["_id"]=$Vc["_id"];$p=$Vc['_source'];if($K!=array("*")){$p=array();foreach($K
as$y)$p[$y]=$Vc['fields'][$y];}foreach($p
as$y=>$X){if($_b["fields"])$X=$X[0];$I[$y]=(is_array($X)?json_encode($X):$X);}$H[]=$I;}return
new
Min_Result($H);}function
update($T,$cf,$Ye,$z=0,$L="\n"){$Fe=preg_split('~ *= *~',$Ye);if(count($Fe)==2){$t=trim($Fe[1]);$F="$T/$t";return$this->_conn->query($F,$cf,'POST');}return
false;}function
insert($T,$cf){$t="";$F="$T/$t";$kf=$this->_conn->query($F,$cf,'POST');$this->_conn->last_id=$kf['_id'];return$kf['created'];}function
delete($T,$Ye,$z=0){$Zc=array();if(is_array($_GET["where"])&&$_GET["where"]["_id"])$Zc[]=$_GET["where"]["_id"];if(is_array($_POST['check'])){foreach($_POST['check']as$Sa){$Fe=preg_split('~ *= *~',$Sa);if(count($Fe)==2)$Zc[]=trim($Fe[1]);}}$this->_conn->affected_rows=0;foreach($Zc
as$t){$F="{$T}/{$t}";$kf=$this->_conn->query($F,'{}','DELETE');if(is_array($kf)&&$kf['found']==true)$this->_conn->affected_rows++;}return$this->_conn->affected_rows;}}function
connect(){global$b;$h=new
Min_DB;list($M,$V,$E)=$b->credentials();if($E!=""&&$h->connect($M,$V,""))return
lang(22);if($h->connect($M,$V,$E))return$h;return$h->error;}function
support($oc){return
preg_match("~database|table|columns~",$oc);}function
logged_user(){global$b;$wb=$b->credentials();return$wb[1];}function
get_databases(){global$h;$H=$h->rootQuery('_aliases');if($H){$H=array_keys($H);sort($H,SORT_STRING);}return$H;}function
collations(){return
array();}function
db_collation($l,$bb){}function
engines(){return
array();}function
count_tables($k){global$h;$H=array();$G=$h->query('_stats');if($G&&$G['indices']){$fd=$G['indices'];foreach($fd
as$ed=>$Rf){$dd=$Rf['total']['indexing'];$H[$ed]=$dd['index_total'];}}return$H;}function
tables_list(){global$h;if(min_version(6))return
array('_doc'=>'table');$H=$h->query('_mapping');if($H)$H=array_fill_keys(array_keys($H[$h->_db]["mappings"]),'table');return$H;}function
table_status($B="",$nc=false){global$h;$uf=$h->query("_search",array("size"=>0,"aggregations"=>array("count_by_type"=>array("terms"=>array("field"=>"_type")))),"POST");$H=array();if($uf){$S=$uf["aggregations"]["count_by_type"]["buckets"];foreach($S
as$Q){$H[$Q["key"]]=array("Name"=>$Q["key"],"Engine"=>"table","Rows"=>$Q["doc_count"],);if($B!=""&&$B==$Q["key"])return$H[$B];}}return$H;}function
error(){global$h;return
h($h->error);}function
information_schema(){}function
is_view($R){}function
indexes($Q,$i=null){return
array(array("type"=>"PRIMARY","columns"=>array("_id")),);}function
fields($Q){global$h;$Jd=array();if(min_version(6)){$G=$h->query("_mapping");if($G)$Jd=$G[$h->_db]['mappings']['properties'];}else{$G=$h->query("$Q/_mapping");if($G){$Jd=$G[$Q]['properties'];if(!$Jd)$Jd=$G[$h->_db]['mappings'][$Q]['properties'];}}$H=array();if($Jd){foreach($Jd
as$B=>$o){$H[$B]=array("field"=>$B,"full_type"=>$o["type"],"type"=>$o["type"],"privileges"=>array("insert"=>1,"select"=>1,"update"=>1),);if($o["properties"]){unset($H[$B]["privileges"]["insert"]);unset($H[$B]["privileges"]["update"]);}}}return$H;}function
foreign_keys($Q){return
array();}function
table($u){return$u;}function
idf_escape($u){return$u;}function
convert_field($o){}function
unconvert_field($o,$H){return$H;}function
fk_support($R){}function
found_rows($R,$Z){return
null;}function
create_database($l){global$h;return$h->rootQuery(urlencode($l),null,'PUT');}function
drop_databases($k){global$h;return$h->rootQuery(urlencode(implode(',',$k)),array(),'DELETE');}function
alter_table($Q,$B,$p,$_c,$gb,$Yb,$d,$Ea,$Ee){global$h;$Ue=array();foreach($p
as$lc){$pc=trim($lc[1][0]);$qc=trim($lc[1][1]?$lc[1][1]:"text");$Ue[$pc]=array('type'=>$qc);}if(!empty($Ue))$Ue=array('properties'=>$Ue);return$h->query("_mapping/{$B}",$Ue,'PUT');}function
drop_tables($S){global$h;$H=true;foreach($S
as$Q)$H=$H&&$h->query(urlencode($Q),array(),'DELETE');return$H;}function
last_id(){global$h;return$h->last_id;}function
driver_config(){$U=array();$Tf=array();foreach(array(lang(27)=>array("long"=>3,"integer"=>5,"short"=>8,"byte"=>10,"double"=>20,"float"=>66,"half_float"=>12,"scaled_float"=>21),lang(28)=>array("date"=>10),lang(25)=>array("string"=>65535,"text"=>65535),lang(29)=>array("binary"=>255),)as$y=>$X){$U+=$X;$Tf[$y]=array_keys($X);}return
array('possible_drivers'=>array("json + allow_url_fopen"),'jush'=>"elastic",'operators'=>array("=","query"),'functions'=>array(),'grouping'=>array(),'edit_functions'=>array(array("json")),'types'=>$U,'structured_types'=>$Tf,);}}class
Adminer{var$operators=array("<=",">=");var$_values=array();function
name(){return"<a href='https://www.adminer.org/editor/'".target_blank()." id='h1'>".lang(33)."</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_password());}function
connectSsl(){}function
permanentLogin($ub=false){return
password_file($ub);}function
bruteForceKey(){return$_SERVER["REMOTE_ADDR"];}function
serverName($M){}function
database(){global$h;if($h){$k=$this->databases(false);return(!$k?$h->result("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1)"):$k[(information_schema($k[0])?1:0)]);}}function
schemas(){return
schemas();}function
databases($yc=true){return
get_databases($yc);}function
queryTimeout(){return
5;}function
headers(){}function
csp(){return
csp();}function
head(){return
true;}function
css(){$H=array();$q="adminer.css";if(file_exists($q))$H[]=$q;return$H;}function
loginForm(){echo"<table cellspacing='0' class='layout'>\n",$this->loginFormField('username','<tr><th>'.lang(34).'<td>','<input type="hidden" name="auth[driver]" value="server"><input name="auth[username]" id="username" value="'.h($_GET["username"]).'" autocomplete="username" autocapitalize="off">'.script("focus(qs('#username'));")),$this->loginFormField('password','<tr><th>'.lang(35).'<td>','<input type="password" name="auth[password]" autocomplete="current-password">'."\n"),"</table>\n","<p><input type='submit' value='".lang(36)."'>\n",checkbox("auth[permanent]",1,$_COOKIE["adminer_permanent"],lang(37))."\n";}function
loginFormField($B,$Tc,$Y){return$Tc.$Y;}function
login($Hd,$E){return
true;}function
tableName($Zf){return
h($Zf["Comment"]!=""?$Zf["Comment"]:$Zf["Name"]);}function
fieldName($o,$se=0){return
h(preg_replace('~\s+\[.*\]$~','',($o["comment"]!=""?$o["comment"]:$o["field"])));}function
selectLinks($Zf,$N=""){$a=$Zf["Name"];if($N!==null)echo'<p class="tabs"><a href="'.h(ME.'edit='.urlencode($a).$N).'">'.lang(38)."</a>\n";}function
foreignKeys($Q){return
foreign_keys($Q);}function
backwardKeys($Q,$Yf){$H=array();foreach(get_rows("SELECT TABLE_NAME, CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = ".q($this->database())."
AND REFERENCED_TABLE_SCHEMA = ".q($this->database())."
AND REFERENCED_TABLE_NAME = ".q($Q)."
ORDER BY ORDINAL_POSITION",null,"")as$I)$H[$I["TABLE_NAME"]]["keys"][$I["CONSTRAINT_NAME"]][$I["COLUMN_NAME"]]=$I["REFERENCED_COLUMN_NAME"];foreach($H
as$y=>$X){$B=$this->tableName(table_status($y,true));if($B!=""){$uf=preg_quote($Yf);$L="(:|\\s*-)?\\s+";$H[$y]["name"]=(preg_match("(^$uf$L(.+)|^(.+?)$L$uf\$)iu",$B,$A)?$A[2].$A[3]:$B);}else
unset($H[$y]);}return$H;}function
backwardKeysPrint($Ia,$I){foreach($Ia
as$Q=>$Ha){foreach($Ha["keys"]as$db){$_=ME.'select='.urlencode($Q);$s=0;foreach($db
as$e=>$X)$_.=where_link($s++,$e,$I[$X]);echo"<a href='".h($_)."'>".h($Ha["name"])."</a>";$_=ME.'edit='.urlencode($Q);foreach($db
as$e=>$X)$_.="&set".urlencode("[".bracket_escape($e)."]")."=".urlencode($I[$X]);echo"<a href='".h($_)."' title='".lang(38)."'>+</a> ";}}}function
selectQuery($F,$Qf,$mc=false){return"<!--\n".str_replace("--","--><!-- ",$F)."\n(".format_time($Qf).")\n-->\n";}function
rowDescription($Q){foreach(fields($Q)as$o){if(preg_match("~varchar|character varying~",$o["type"]))return
idf_escape($o["field"]);}return"";}function
rowDescriptions($J,$Bc){$H=$J;foreach($J[0]as$y=>$X){if(list($Q,$t,$B)=$this->_foreignColumn($Bc,$y)){$Zc=array();foreach($J
as$I)$Zc[$I[$y]]=q($I[$y]);$Gb=$this->_values[$Q];if(!$Gb)$Gb=get_key_vals("SELECT $t, $B FROM ".table($Q)." WHERE $t IN (".implode(", ",$Zc).")");foreach($J
as$Zd=>$I){if(isset($I[$y]))$H[$Zd][$y]=(string)$Gb[$I[$y]];}}}return$H;}function
selectLink($X,$o){}function
selectVal($X,$_,$o,$we){$H=$X;$_=h($_);if(preg_match('~blob|bytea~',$o["type"])&&!is_utf8($X)){$H=lang(39,strlen($we));if(preg_match("~^(GIF|\xFF\xD8\xFF|\x89PNG\x0D\x0A\x1A\x0A)~",$we))$H="<img src='$_' alt='$H'>";}if(like_bool($o)&&$H!="")$H=(preg_match('~^(1|t|true|y|yes|on)$~i',$X)?lang(40):lang(41));if($_)$H="<a href='$_'".(is_url($_)?target_blank():"").">$H</a>";if(!$_&&!like_bool($o)&&preg_match(number_type(),$o["type"]))$H="<div class='number'>$H</div>";elseif(preg_match('~date~',$o["type"]))$H="<div class='datetime'>$H</div>";return$H;}function
editVal($X,$o){if(preg_match('~date|timestamp~',$o["type"])&&$X!==null)return
preg_replace('~^(\d{2}(\d+))-(0?(\d+))-(0?(\d+))~',lang(42),$X);return$X;}function
selectColumnsPrint($K,$f){}function
selectSearchPrint($Z,$f,$w){$Z=(array)$_GET["where"];echo'<fieldset id="fieldset-search"><legend>'.lang(43)."</legend><div>\n";$ud=array();foreach($Z
as$y=>$X)$ud[$X["col"]]=$y;$s=0;$p=fields($_GET["select"]);foreach($f
as$B=>$Fb){$o=$p[$B];if(preg_match("~enum~",$o["type"])||like_bool($o)){$y=$ud[$B];$s--;echo"<div>".h($Fb)."<input type='hidden' name='where[$s][col]' value='".h($B)."'>:",(like_bool($o)?" <select name='where[$s][val]'>".optionlist(array(""=>"",lang(41),lang(40)),$Z[$y]["val"],true)."</select>":enum_input("checkbox"," name='where[$s][val][]'",$o,(array)$Z[$y]["val"],($o["null"]?0:null))),"</div>\n";unset($f[$B]);}elseif(is_array($C=$this->_foreignKeyOptions($_GET["select"],$B))){if($p[$B]["null"])$C[0]='('.lang(7).')';$y=$ud[$B];$s--;echo"<div>".h($Fb)."<input type='hidden' name='where[$s][col]' value='".h($B)."'><input type='hidden' name='where[$s][op]' value='='>: <select name='where[$s][val]'>".optionlist($C,$Z[$y]["val"],true)."</select></div>\n";unset($f[$B]);}}$s=0;foreach($Z
as$X){if(($X["col"]==""||$f[$X["col"]])&&"$X[col]$X[val]"!=""){echo"<div><select name='where[$s][col]'><option value=''>(".lang(44).")".optionlist($f,$X["col"],true)."</select>",html_select("where[$s][op]",array(-1=>"")+$this->operators,$X["op"]),"<input type='search' name='where[$s][val]' value='".h($X["val"])."'>".script("mixin(qsl('input'), {onkeydown: selectSearchKeydown, onsearch: selectSearchSearch});","")."</div>\n";$s++;}}echo"<div><select name='where[$s][col]'><option value=''>(".lang(44).")".optionlist($f,null,true)."</select>",script("qsl('select').onchange = selectAddRow;",""),html_select("where[$s][op]",array(-1=>"")+$this->operators),"<input type='search' name='where[$s][val]'></div>",script("mixin(qsl('input'), {onchange: function () { this.parentNode.firstChild.onchange(); }, onsearch: selectSearchSearch});"),"</div></fieldset>\n";}function
selectOrderPrint($se,$f,$w){$te=array();foreach($w
as$y=>$v){$se=array();foreach($v["columns"]as$X)$se[]=$f[$X];if(count(array_filter($se,'strlen'))>1&&$y!="PRIMARY")$te[$y]=implode(", ",$se);}if($te){echo'<fieldset><legend>'.lang(45)."</legend><div>","<select name='index_order'>".optionlist(array(""=>"")+$te,($_GET["order"][0]!=""?"":$_GET["index_order"]),true)."</select>","</div></fieldset>\n";}if($_GET["order"])echo"<div style='display: none;'>".hidden_fields(array("order"=>array(1=>reset($_GET["order"])),"desc"=>($_GET["desc"]?array(1=>1):array()),))."</div>\n";}function
selectLimitPrint($z){echo"<fieldset><legend>".lang(46)."</legend><div>";echo
html_select("limit",array("","50","100"),$z),"</div></fieldset>\n";}function
selectLengthPrint($hg){}function
selectActionPrint($w){echo"<fieldset><legend>".lang(47)."</legend><div>","<input type='submit' value='".lang(48)."'>","</div></fieldset>\n";}function
selectCommandPrint(){return
true;}function
selectImportPrint(){return
true;}function
selectEmailPrint($Vb,$f){if($Vb){print_fieldset("email",lang(49),$_POST["email_append"]);echo"<div>",script("qsl('div').onkeydown = partialArg(bodyKeydown, 'email');"),"<p>".lang(50).": <input name='email_from' value='".h($_POST?$_POST["email_from"]:$_COOKIE["adminer_email"])."'>\n",lang(51).": <input name='email_subject' value='".h($_POST["email_subject"])."'>\n","<p><textarea name='email_message' rows='15' cols='75'>".h($_POST["email_message"].($_POST["email_append"]?'{$'."$_POST[email_addition]}":""))."</textarea>\n","<p>".script("qsl('p').onkeydown = partialArg(bodyKeydown, 'email_append');","").html_select("email_addition",$f,$_POST["email_addition"])."<input type='submit' name='email_append' value='".lang(11)."'>\n";echo"<p>".lang(52).": <input type='file' name='email_files[]'>".script("qsl('input').onchange = emailFileChange;"),"<p>".(count($Vb)==1?'<input type="hidden" name="email_field" value="'.h(key($Vb)).'">':html_select("email_field",$Vb)),"<input type='submit' name='email' value='".lang(53)."'>".confirm(),"</div>\n","</div></fieldset>\n";}}function
selectColumnsProcess($f,$w){return
array(array(),array());}function
selectSearchProcess($p,$w){global$m;$H=array();foreach((array)$_GET["where"]as$y=>$Z){$ab=$Z["col"];$ne=$Z["op"];$X=$Z["val"];if(($y<0?"":$ab).$X!=""){$ib=array();foreach(($ab!=""?array($ab=>$p[$ab]):$p)as$B=>$o){if($ab!=""||is_numeric($X)||!preg_match(number_type(),$o["type"])){$B=idf_escape($B);if($ab!=""&&$o["type"]=="enum")$ib[]=(in_array(0,$X)?"$B IS NULL OR ":"")."$B IN (".implode(", ",array_map('intval',$X)).")";else{$ig=preg_match('~char|text|enum|set~',$o["type"]);$Y=$this->processInput($o,(!$ne&&$ig&&preg_match('~^[^%]+$~',$X)?"%$X%":$X));$ib[]=$m->convertSearch($B,$X,$o).($Y=="NULL"?" IS".($ne==">="?" NOT":"")." $Y":(in_array($ne,$this->operators)||$ne=="="?" $ne $Y":($ig?" LIKE $Y":" IN (".str_replace(",","', '",$Y).")")));if($y<0&&$X=="0")$ib[]="$B IS NULL";}}}$H[]=($ib?"(".implode(" OR ",$ib).")":"1 = 0");}}return$H;}function
selectOrderProcess($p,$w){$cd=$_GET["index_order"];if($cd!="")unset($_GET["order"][1]);if($_GET["order"])return
array(idf_escape(reset($_GET["order"])).($_GET["desc"]?" DESC":""));foreach(($cd!=""?array($w[$cd]):$w)as$v){if($cd!=""||$v["type"]=="INDEX"){$Oc=array_filter($v["descs"]);$Fb=false;foreach($v["columns"]as$X){if(preg_match('~date|timestamp~',$p[$X]["type"])){$Fb=true;break;}}$H=array();foreach($v["columns"]as$y=>$X)$H[]=idf_escape($X).(($Oc?$v["descs"][$y]:$Fb)?" DESC":"");return$H;}}return
array();}function
selectLimitProcess(){return(isset($_GET["limit"])?$_GET["limit"]:"50");}function
selectLengthProcess(){return"100";}function
selectEmailProcess($Z,$Bc){if($_POST["email_append"])return
true;if($_POST["email"]){$yf=0;if($_POST["all"]||$_POST["check"]){$o=idf_escape($_POST["email_field"]);$Vf=$_POST["email_subject"];$Td=$_POST["email_message"];preg_match_all('~\{\$([a-z0-9_]+)\}~i',"$Vf.$Td",$Nd);$J=get_rows("SELECT DISTINCT $o".($Nd[1]?", ".implode(", ",array_map('idf_escape',array_unique($Nd[1]))):"")." FROM ".table($_GET["select"])." WHERE $o IS NOT NULL AND $o != ''".($Z?" AND ".implode(" AND ",$Z):"").($_POST["all"]?"":" AND ((".implode(") OR (",array_map('where_check',(array)$_POST["check"]))."))"));$p=fields($_GET["select"]);foreach($this->rowDescriptions($J,$Bc)as$I){$if=array('{\\'=>'{');foreach($Nd[1]as$X)$if['{$'."$X}"]=$this->editVal($I[$X],$p[$X]);$Ub=$I[$_POST["email_field"]];if(is_mail($Ub)&&send_mail($Ub,strtr($Vf,$if),strtr($Td,$if),$_POST["email_from"],$_FILES["email_files"]))$yf++;}}cookie("adminer_email",$_POST["email_from"]);redirect(remove_from_uri(),lang(54,$yf));}return
false;}function
selectQueryBuild($K,$Z,$Jc,$se,$z,$D){return"";}function
messageQuery($F,$jg,$mc=false){return" <span class='time'>".@date("H:i:s")."</span><!--\n".str_replace("--","--><!-- ",$F)."\n".($jg?"($jg)\n":"")."-->";}function
editRowPrint($Q,$p,$I,$Kg){}function
editFunctions($o){$H=array();if($o["null"]&&preg_match('~blob~',$o["type"]))$H["NULL"]=lang(7);$H[""]=($o["null"]||$o["auto_increment"]||like_bool($o)?"":"*");if(preg_match('~date|time~',$o["type"]))$H["now"]=lang(55);if(preg_match('~_(md5|sha1)$~i',$o["field"],$A))$H[]=strtolower($A[1]);return$H;}function
editInput($Q,$o,$Ba,$Y){if($o["type"]=="enum")return(isset($_GET["select"])?"<label><input type='radio'$Ba value='-1' checked><i>".lang(8)."</i></label> ":"").enum_input("radio",$Ba,$o,($Y||isset($_GET["select"])?$Y:0),($o["null"]?"":null));$C=$this->_foreignKeyOptions($Q,$o["field"],$Y);if($C!==null)return(is_array($C)?"<select$Ba>".optionlist($C,$Y,true)."</select>":"<input value='".h($Y)."'$Ba class='hidden'>"."<input value='".h($C)."' class='jsonly'>"."<div></div>".script("qsl('input').oninput = partial(whisper, '".ME."script=complete&source=".urlencode($Q)."&field=".urlencode($o["field"])."&value=');
qsl('div').onclick = whisperClick;",""));if(like_bool($o))return'<input type="checkbox" value="1"'.(preg_match('~^(1|t|true|y|yes|on)$~i',$Y)?' checked':'')."$Ba>";$Uc="";if(preg_match('~time~',$o["type"]))$Uc=lang(56);if(preg_match('~date|timestamp~',$o["type"]))$Uc=lang(57).($Uc?" [$Uc]":"");if($Uc)return"<input value='".h($Y)."'$Ba> ($Uc)";if(preg_match('~_(md5|sha1)$~i',$o["field"]))return"<input type='password' value='".h($Y)."'$Ba>";return'';}function
editHint($Q,$o,$Y){return(preg_match('~\s+(\[.*\])$~',($o["comment"]!=""?$o["comment"]:$o["field"]),$A)?h(" $A[1]"):'');}function
processInput($o,$Y,$r=""){if($r=="now")return"$r()";$H=$Y;if(preg_match('~date|timestamp~',$o["type"])&&preg_match('(^'.str_replace('\$1','(?P<p1>\d*)',preg_replace('~(\\\\\\$([2-6]))~','(?P<p\2>\d{1,2})',preg_quote(lang(42)))).'(.*))',$Y,$A))$H=($A["p1"]!=""?$A["p1"]:($A["p2"]!=""?($A["p2"]<70?20:19).$A["p2"]:gmdate("Y")))."-$A[p3]$A[p4]-$A[p5]$A[p6]".end($A);$H=($o["type"]=="bit"&&preg_match('~^[0-9]+$~',$Y)?$H:q($H));if($Y==""&&like_bool($o))$H="'0'";elseif($Y==""&&($o["null"]||!preg_match('~char|text~',$o["type"])))$H="NULL";elseif(preg_match('~^(md5|sha1)$~',$r))$H="$r($H)";return
unconvert_field($o,$H);}function
dumpOutput(){return
array();}function
dumpFormat(){return
array('csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpDatabase($l){}function
dumpTable($Q,$Uf,$qd=0){echo"\xef\xbb\xbf";}function
dumpData($Q,$Uf,$F){global$h;$G=$h->query($F,1);if($G){while($I=$G->fetch_assoc()){if($Uf=="table"){dump_csv(array_keys($I));$Uf="INSERT";}dump_csv($I);}}}function
dumpFilename($Yc){return
friendly_url($Yc);}function
dumpHeaders($Yc,$Xd=false){$ic="csv";header("Content-Type: text/csv; charset=utf-8");return$ic;}function
importServerPath(){}function
homepage(){return
true;}function
navigation($Wd){global$ca;echo'<h1>
',$this->name(),' <span class="version">',$ca,'</span>
<a href="https://www.adminer.org/editor/#download"',target_blank(),' id="version">',(version_compare($ca,$_COOKIE["adminer_version"])<0?h($_COOKIE["adminer_version"]):""),'</a>
</h1>
';if($Wd=="auth"){$uc=true;foreach((array)$_SESSION["pwds"]as$Sg=>$Cf){foreach($Cf[""]as$V=>$E){if($E!==null){if($uc){echo"<ul id='logins'>",script("mixin(qs('#logins'), {onmouseover: menuOver, onmouseout: menuOut});");$uc=false;}echo"<li><a href='".h(auth_url($Sg,"",$V))."'>".($V!=""?h($V):"<i>".lang(7)."</i>")."</a>\n";}}}}else{$this->databasesPrint($Wd);if($Wd!="db"&&$Wd!="ns"){$R=table_status('',true);if(!$R)echo"<p class='message'>".lang(9)."\n";else$this->tablesPrint($R);}}}function
databasesPrint($Wd){}function
tablesPrint($S){echo"<ul id='tables'>",script("mixin(qs('#tables'), {onmouseover: menuOver, onmouseout: menuOut});");foreach($S
as$I){echo'<li>';$B=$this->tableName($I);if(isset($I["Engine"])&&$B!="")echo"<a href='".h(ME).'select='.urlencode($I["Name"])."'".bold($_GET["select"]==$I["Name"]||$_GET["edit"]==$I["Name"],"select")." title='".lang(58)."'>$B</a>\n";}echo"</ul>\n";}function
_foreignColumn($Bc,$e){foreach((array)$Bc[$e]as$Ac){if(count($Ac["source"])==1){$B=$this->rowDescription($Ac["table"]);if($B!=""){$t=idf_escape($Ac["target"][0]);return
array($Ac["table"],$t,$B);}}}}function
_foreignKeyOptions($Q,$e,$Y=null){global$h;if(list($dg,$t,$B)=$this->_foreignColumn(column_foreign_keys($Q),$e)){$H=&$this->_values[$dg];if($H===null){$R=table_status($dg);$H=($R["Rows"]>1000?"":array(""=>"")+get_key_vals("SELECT $t, $B FROM ".table($dg)." ORDER BY 2"));}if(!$H&&$Y!==null)return$h->result("SELECT $B FROM ".table($dg)." WHERE $t = ".q($Y));return$H;}}}$b=(function_exists('adminer_object')?adminer_object():new
Adminer);$Mb=array("server"=>"MySQL")+$Mb;if(!defined("DRIVER")){define("DRIVER","server");if(extension_loaded("mysqli")){class
Min_DB
extends
MySQLi{var$extension="MySQLi";function
__construct(){parent::init();}function
connect($M="",$V="",$E="",$j=null,$Le=null,$If=null){global$b;mysqli_report(MYSQLI_REPORT_OFF);list($Wc,$Le)=explode(":",$M,2);$Pf=$b->connectSsl();if($Pf)$this->ssl_set($Pf['key'],$Pf['cert'],$Pf['ca'],'','');$H=@$this->real_connect(($M!=""?$Wc:ini_get("mysqli.default_host")),($M.$V!=""?$V:ini_get("mysqli.default_user")),($M.$V.$E!=""?$E:ini_get("mysqli.default_pw")),$j,(is_numeric($Le)?$Le:ini_get("mysqli.default_port")),(!is_numeric($Le)?$Le:$If),($Pf?64:0));$this->options(MYSQLI_OPT_LOCAL_INFILE,false);return$H;}function
set_charset($Ra){if(parent::set_charset($Ra))return
true;parent::set_charset('utf8');return$this->query("SET NAMES $Ra");}function
result($F,$o=0){$G=$this->query($F);if(!$G)return
false;$I=$G->fetch_array();return$I[$o];}function
quote($P){return"'".$this->escape_string($P)."'";}}}elseif(extension_loaded("mysql")&&!((ini_bool("sql.safe_mode")||ini_bool("mysql.allow_local_infile"))&&extension_loaded("pdo_mysql"))){class
Min_DB{var$extension="MySQL",$server_info,$affected_rows,$errno,$error,$_link,$_result;function
connect($M,$V,$E){if(ini_bool("mysql.allow_local_infile")){$this->error=lang(59,"'mysql.allow_local_infile'","MySQLi","PDO_MySQL");return
false;}$this->_link=@mysql_connect(($M!=""?$M:ini_get("mysql.default_host")),("$M$V"!=""?$V:ini_get("mysql.default_user")),("$M$V$E"!=""?$E:ini_get("mysql.default_password")),true,131072);if($this->_link)$this->server_info=mysql_get_server_info($this->_link);else$this->error=mysql_error();return(bool)$this->_link;}function
set_charset($Ra){if(function_exists('mysql_set_charset')){if(mysql_set_charset($Ra,$this->_link))return
true;mysql_set_charset('utf8',$this->_link);}return$this->query("SET NAMES $Ra");}function
quote($P){return"'".mysql_real_escape_string($P,$this->_link)."'";}function
select_db($j){return
mysql_select_db($j,$this->_link);}function
query($F,$Dg=false){$G=@($Dg?mysql_unbuffered_query($F,$this->_link):mysql_query($F,$this->_link));$this->error="";if(!$G){$this->errno=mysql_errno($this->_link);$this->error=mysql_error($this->_link);return
false;}if($G===true){$this->affected_rows=mysql_affected_rows($this->_link);$this->info=mysql_info($this->_link);return
true;}return
new
Min_Result($G);}function
multi_query($F){return$this->_result=$this->query($F);}function
store_result(){return$this->_result;}function
next_result(){return
false;}function
result($F,$o=0){$G=$this->query($F);if(!$G||!$G->num_rows)return
false;return
mysql_result($G->_result,0,$o);}}class
Min_Result{var$num_rows,$_result,$_offset=0;function
__construct($G){$this->_result=$G;$this->num_rows=mysql_num_rows($G);}function
fetch_assoc(){return
mysql_fetch_assoc($this->_result);}function
fetch_row(){return
mysql_fetch_row($this->_result);}function
fetch_field(){$H=mysql_fetch_field($this->_result,$this->_offset++);$H->orgtable=$H->table;$H->orgname=$H->name;$H->charsetnr=($H->blob?63:0);return$H;}function
__destruct(){mysql_free_result($this->_result);}}}elseif(extension_loaded("pdo_mysql")){class
Min_DB
extends
Min_PDO{var$extension="PDO_MySQL";function
connect($M,$V,$E){global$b;$C=array(PDO::MYSQL_ATTR_LOCAL_INFILE=>false);$Pf=$b->connectSsl();if($Pf){if(!empty($Pf['key']))$C[PDO::MYSQL_ATTR_SSL_KEY]=$Pf['key'];if(!empty($Pf['cert']))$C[PDO::MYSQL_ATTR_SSL_CERT]=$Pf['cert'];if(!empty($Pf['ca']))$C[PDO::MYSQL_ATTR_SSL_CA]=$Pf['ca'];}$this->dsn("mysql:charset=utf8;host=".str_replace(":",";unix_socket=",preg_replace('~:(\d)~',';port=\1',$M)),$V,$E,$C);return
true;}function
set_charset($Ra){$this->query("SET NAMES $Ra");}function
select_db($j){return$this->query("USE ".idf_escape($j));}function
query($F,$Dg=false){$this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,!$Dg);return
parent::query($F,$Dg);}}}class
Min_Driver
extends
Min_SQL{function
insert($Q,$N){return($N?parent::insert($Q,$N):queries("INSERT INTO ".table($Q)." ()\nVALUES ()"));}function
insertUpdate($Q,$J,$Pe){$f=array_keys(reset($J));$Oe="INSERT INTO ".table($Q)." (".implode(", ",$f).") VALUES\n";$Rg=array();foreach($f
as$y)$Rg[$y]="$y = VALUES($y)";$Wf="\nON DUPLICATE KEY UPDATE ".implode(", ",$Rg);$Rg=array();$Cd=0;foreach($J
as$N){$Y="(".implode(", ",$N).")";if($Rg&&(strlen($Oe)+$Cd+strlen($Y)+strlen($Wf)>1e6)){if(!queries($Oe.implode(",\n",$Rg).$Wf))return
false;$Rg=array();$Cd=0;}$Rg[]=$Y;$Cd+=strlen($Y)+2;}return
queries($Oe.implode(",\n",$Rg).$Wf);}function
slowQuery($F,$kg){if(min_version('5.7.8','10.1.2')){if(preg_match('~MariaDB~',$this->_conn->server_info))return"SET STATEMENT max_statement_time=$kg FOR $F";elseif(preg_match('~^(SELECT\b)(.+)~is',$F,$A))return"$A[1] /*+ MAX_EXECUTION_TIME(".($kg*1000).") */ $A[2]";}}function
convertSearch($u,$X,$o){return(preg_match('~char|text|enum|set~',$o["type"])&&!preg_match("~^utf8~",$o["collation"])&&preg_match('~[\x80-\xFF]~',$X['val'])?"CONVERT($u USING ".charset($this->_conn).")":$u);}function
warnings(){$G=$this->_conn->query("SHOW WARNINGS");if($G&&$G->num_rows){ob_start();select($G);return
ob_get_clean();}}function
tableHelp($B){$Kd=preg_match('~MariaDB~',$this->_conn->server_info);if(information_schema(DB))return
strtolower(($Kd?"information-schema-$B-table/":str_replace("_","-",$B)."-table.html"));if(DB=="mysql")return($Kd?"mysql$B-table/":"system-database.html");}}function
idf_escape($u){return"`".str_replace("`","``",$u)."`";}function
table($u){return
idf_escape($u);}function
connect(){global$b,$U,$Tf;$h=new
Min_DB;$wb=$b->credentials();if($h->connect($wb[0],$wb[1],$wb[2])){$h->set_charset(charset($h));$h->query("SET sql_quote_show_create = 1, autocommit = 1");if(min_version('5.7.8',10.2,$h)){$Tf[lang(25)][]="json";$U["json"]=4294967295;}return$h;}$H=$h->error;if(function_exists('iconv')&&!is_utf8($H)&&strlen($rf=iconv("windows-1250","utf-8",$H))>strlen($H))$H=$rf;return$H;}function
get_databases($yc){$H=get_session("dbs");if($H===null){$F=(min_version(5)?"SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME":"SHOW DATABASES");$H=($yc?slow_query($F):get_vals($F));restart_session();set_session("dbs",$H);stop_session();}return$H;}function
limit($F,$Z,$z,$he=0,$L=" "){return" $F$Z".($z!==null?$L."LIMIT $z".($he?" OFFSET $he":""):"");}function
limit1($Q,$F,$Z,$L="\n"){return
limit($F,$Z,1,0,$L);}function
db_collation($l,$bb){global$h;$H=null;$ub=$h->result("SHOW CREATE DATABASE ".idf_escape($l),1);if(preg_match('~ COLLATE ([^ ]+)~',$ub,$A))$H=$A[1];elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$ub,$A))$H=$bb[$A[1]][-1];return$H;}function
engines(){$H=array();foreach(get_rows("SHOW ENGINES")as$I){if(preg_match("~YES|DEFAULT~",$I["Support"]))$H[]=$I["Engine"];}return$H;}function
logged_user(){global$h;return$h->result("SELECT USER()");}function
tables_list(){return
get_key_vals(min_version(5)?"SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME":"SHOW TABLES");}function
count_tables($k){$H=array();foreach($k
as$l)$H[$l]=count(get_vals("SHOW TABLES IN ".idf_escape($l)));return$H;}function
table_status($B="",$nc=false){$H=array();foreach(get_rows($nc&&min_version(5)?"SELECT TABLE_NAME AS Name, ENGINE AS Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ".($B!=""?"AND TABLE_NAME = ".q($B):"ORDER BY Name"):"SHOW TABLE STATUS".($B!=""?" LIKE ".q(addcslashes($B,"%_\\")):""))as$I){if($I["Engine"]=="InnoDB")$I["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\1',$I["Comment"]);if(!isset($I["Engine"]))$I["Comment"]="";if($B!="")return$I;$H[$I["Name"]]=$I;}return$H;}function
is_view($R){return$R["Engine"]===null;}function
fk_support($R){return
preg_match('~InnoDB|IBMDB2I~i',$R["Engine"])||(preg_match('~NDB~i',$R["Engine"])&&min_version(5.6));}function
fields($Q){$H=array();foreach(get_rows("SHOW FULL COLUMNS FROM ".table($Q))as$I){preg_match('~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~',$I["Type"],$A);$H[$I["Field"]]=array("field"=>$I["Field"],"full_type"=>$I["Type"],"type"=>$A[1],"length"=>$A[2],"unsigned"=>ltrim($A[3].$A[4]),"default"=>($I["Default"]!=""||preg_match("~char|set~",$A[1])?(preg_match('~text~',$A[1])?stripslashes(preg_replace("~^'(.*)'\$~",'\1',$I["Default"])):$I["Default"]):null),"null"=>($I["Null"]=="YES"),"auto_increment"=>($I["Extra"]=="auto_increment"),"on_update"=>(preg_match('~^on update (.+)~i',$I["Extra"],$A)?$A[1]:""),"collation"=>$I["Collation"],"privileges"=>array_flip(preg_split('~, *~',$I["Privileges"])),"comment"=>$I["Comment"],"primary"=>($I["Key"]=="PRI"),"generated"=>preg_match('~^(VIRTUAL|PERSISTENT|STORED)~',$I["Extra"]),);}return$H;}function
indexes($Q,$i=null){$H=array();foreach(get_rows("SHOW INDEX FROM ".table($Q),$i)as$I){$B=$I["Key_name"];$H[$B]["type"]=($B=="PRIMARY"?"PRIMARY":($I["Index_type"]=="FULLTEXT"?"FULLTEXT":($I["Non_unique"]?($I["Index_type"]=="SPATIAL"?"SPATIAL":"INDEX"):"UNIQUE")));$H[$B]["columns"][]=$I["Column_name"];$H[$B]["lengths"][]=($I["Index_type"]=="SPATIAL"?null:$I["Sub_part"]);$H[$B]["descs"][]=null;}return$H;}function
foreign_keys($Q){global$h,$ke;static$He='(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';$H=array();$vb=$h->result("SHOW CREATE TABLE ".table($Q),1);if($vb){preg_match_all("~CONSTRAINT ($He) FOREIGN KEY ?\\(((?:$He,? ?)+)\\) REFERENCES ($He)(?:\\.($He))? \\(((?:$He,? ?)+)\\)(?: ON DELETE ($ke))?(?: ON UPDATE ($ke))?~",$vb,$Nd,PREG_SET_ORDER);foreach($Nd
as$A){preg_match_all("~$He~",$A[2],$Kf);preg_match_all("~$He~",$A[5],$dg);$H[idf_unescape($A[1])]=array("db"=>idf_unescape($A[4]!=""?$A[3]:$A[4]),"table"=>idf_unescape($A[4]!=""?$A[4]:$A[3]),"source"=>array_map('idf_unescape',$Kf[0]),"target"=>array_map('idf_unescape',$dg[0]),"on_delete"=>($A[6]?$A[6]:"RESTRICT"),"on_update"=>($A[7]?$A[7]:"RESTRICT"),);}}return$H;}function
view($B){global$h;return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU','',$h->result("SHOW CREATE VIEW ".table($B),1)));}function
collations(){$H=array();foreach(get_rows("SHOW COLLATION")as$I){if($I["Default"])$H[$I["Charset"]][-1]=$I["Collation"];else$H[$I["Charset"]][]=$I["Collation"];}ksort($H);foreach($H
as$y=>$X)asort($H[$y]);return$H;}function
information_schema($l){return(min_version(5)&&$l=="information_schema")||(min_version(5.5)&&$l=="performance_schema");}function
error(){global$h;return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",$h->error));}function
create_database($l,$d){return
queries("CREATE DATABASE ".idf_escape($l).($d?" COLLATE ".q($d):""));}function
drop_databases($k){$H=apply_queries("DROP DATABASE",$k,'idf_escape');restart_session();set_session("dbs",null);return$H;}function
rename_database($B,$d){$H=false;if(create_database($B,$d)){$S=array();$Vg=array();foreach(tables_list()as$Q=>$T){if($T=='VIEW')$Vg[]=$Q;else$S[]=$Q;}$H=(!$S&&!$Vg)||move_tables($S,$Vg,$B);drop_databases($H?array(DB):array());}return$H;}function
auto_increment(){$Fa=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$v){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$v["columns"],true)){$Fa="";break;}if($v["type"]=="PRIMARY")$Fa=" UNIQUE";}}return" AUTO_INCREMENT$Fa";}function
alter_table($Q,$B,$p,$_c,$gb,$Yb,$d,$Ea,$Ee){$c=array();foreach($p
as$o)$c[]=($o[1]?($Q!=""?($o[0]!=""?"CHANGE ".idf_escape($o[0]):"ADD"):" ")." ".implode($o[1]).($Q!=""?$o[2]:""):"DROP ".idf_escape($o[0]));$c=array_merge($c,$_c);$O=($gb!==null?" COMMENT=".q($gb):"").($Yb?" ENGINE=".q($Yb):"").($d?" COLLATE ".q($d):"").($Ea!=""?" AUTO_INCREMENT=$Ea":"");if($Q=="")return
queries("CREATE TABLE ".table($B)." (\n".implode(",\n",$c)."\n)$O$Ee");if($Q!=$B)$c[]="RENAME TO ".table($B);if($O)$c[]=ltrim($O);return($c||$Ee?queries("ALTER TABLE ".table($Q)."\n".implode(",\n",$c).$Ee):true);}function
alter_indexes($Q,$c){foreach($c
as$y=>$X)$c[$y]=($X[2]=="DROP"?"\nDROP INDEX ".idf_escape($X[1]):"\nADD $X[0] ".($X[0]=="PRIMARY"?"KEY ":"").($X[1]!=""?idf_escape($X[1])." ":"")."(".implode(", ",$X[2]).")");return
queries("ALTER TABLE ".table($Q).implode(",",$c));}function
truncate_tables($S){return
apply_queries("TRUNCATE TABLE",$S);}function
drop_views($Vg){return
queries("DROP VIEW ".implode(", ",array_map('table',$Vg)));}function
drop_tables($S){return
queries("DROP TABLE ".implode(", ",array_map('table',$S)));}function
move_tables($S,$Vg,$dg){global$h;$hf=array();foreach($S
as$Q)$hf[]=table($Q)." TO ".idf_escape($dg).".".table($Q);if(!$hf||queries("RENAME TABLE ".implode(", ",$hf))){$Eb=array();foreach($Vg
as$Q)$Eb[table($Q)]=view($Q);$h->select_db($dg);$l=idf_escape(DB);foreach($Eb
as$B=>$Ug){if(!queries("CREATE VIEW $B AS ".str_replace(" $l."," ",$Ug["select"]))||!queries("DROP VIEW $l.$B"))return
false;}return
true;}return
false;}function
copy_tables($S,$Vg,$dg){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($S
as$Q){$B=($dg==DB?table("copy_$Q"):idf_escape($dg).".".table($Q));if(($_POST["overwrite"]&&!queries("\nDROP TABLE IF EXISTS $B"))||!queries("CREATE TABLE $B LIKE ".table($Q))||!queries("INSERT INTO $B SELECT * FROM ".table($Q)))return
false;foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")))as$I){$zg=$I["Trigger"];if(!queries("CREATE TRIGGER ".($dg==DB?idf_escape("copy_$zg"):idf_escape($dg).".".idf_escape($zg))." $I[Timing] $I[Event] ON $B FOR EACH ROW\n$I[Statement];"))return
false;}}foreach($Vg
as$Q){$B=($dg==DB?table("copy_$Q"):idf_escape($dg).".".table($Q));$Ug=view($Q);if(($_POST["overwrite"]&&!queries("DROP VIEW IF EXISTS $B"))||!queries("CREATE VIEW $B AS $Ug[select]"))return
false;}return
true;}function
trigger($B){if($B=="")return
array();$J=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($B));return
reset($J);}function
triggers($Q){$H=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")))as$I)$H[$I["Trigger"]]=array($I["Timing"],$I["Event"]);return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
routine($B,$T){global$h,$Zb,$kd,$U;$wa=array("bool","boolean","integer","double precision","real","dec","numeric","fixed","national char","national varchar");$Lf="(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";$Cg="((".implode("|",array_merge(array_keys($U),$wa)).")\\b(?:\\s*\\(((?:[^'\")]|$Zb)++)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s,]+)['\"]?)?";$He="$Lf*(".($T=="FUNCTION"?"":$kd).")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$Cg";$ub=$h->result("SHOW CREATE $T ".idf_escape($B),2);preg_match("~\\(((?:$He\\s*,?)*)\\)\\s*".($T=="FUNCTION"?"RETURNS\\s+$Cg\\s+":"")."(.*)~is",$ub,$A);$p=array();preg_match_all("~$He\\s*,?~is",$A[1],$Nd,PREG_SET_ORDER);foreach($Nd
as$Be)$p[]=array("field"=>str_replace("``","`",$Be[2]).$Be[3],"type"=>strtolower($Be[5]),"length"=>preg_replace_callback("~$Zb~s",'normalize_enum',$Be[6]),"unsigned"=>strtolower(preg_replace('~\s+~',' ',trim("$Be[8] $Be[7]"))),"null"=>1,"full_type"=>$Be[4],"inout"=>strtoupper($Be[1]),"collation"=>strtolower($Be[9]),);if($T!="FUNCTION")return
array("fields"=>$p,"definition"=>$A[11]);return
array("fields"=>$p,"returns"=>array("type"=>$A[12],"length"=>$A[13],"unsigned"=>$A[15],"collation"=>$A[16]),"definition"=>$A[17],"language"=>"SQL",);}function
routines(){return
get_rows("SELECT ROUTINE_NAME AS SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ".q(DB));}function
routine_languages(){return
array();}function
routine_id($B,$I){return
idf_escape($B);}function
last_id(){global$h;return$h->result("SELECT LAST_INSERT_ID()");}function
explain($h,$F){return$h->query("EXPLAIN ".(min_version(5.1)&&!min_version(5.7)?"PARTITIONS ":"").$F);}function
found_rows($R,$Z){return($Z||$R["Engine"]!="InnoDB"?null:$R["Rows"]);}function
types(){return
array();}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($sf,$i=null){return
true;}function
create_sql($Q,$Ea,$Uf){global$h;$H=$h->result("SHOW CREATE TABLE ".table($Q),1);if(!$Ea)$H=preg_replace('~ AUTO_INCREMENT=\d+~','',$H);return$H;}function
truncate_sql($Q){return"TRUNCATE ".table($Q);}function
use_sql($j){return"USE ".idf_escape($j);}function
trigger_sql($Q){$H="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q,"%_\\")),null,"-- ")as$I)$H.="\nCREATE TRIGGER ".idf_escape($I["Trigger"])." $I[Timing] $I[Event] ON ".table($I["Table"])." FOR EACH ROW\n$I[Statement];;\n";return$H;}function
show_variables(){return
get_key_vals("SHOW VARIABLES");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
show_status(){return
get_key_vals("SHOW STATUS");}function
convert_field($o){if(preg_match("~binary~",$o["type"]))return"HEX(".idf_escape($o["field"]).")";if($o["type"]=="bit")return"BIN(".idf_escape($o["field"])." + 0)";if(preg_match("~geometry|point|linestring|polygon~",$o["type"]))return(min_version(8)?"ST_":"")."AsWKT(".idf_escape($o["field"]).")";}function
unconvert_field($o,$H){if(preg_match("~binary~",$o["type"]))$H="UNHEX($H)";if($o["type"]=="bit")$H="CONV($H, 2, 10) + 0";if(preg_match("~geometry|point|linestring|polygon~",$o["type"]))$H=(min_version(8)?"ST_":"")."GeomFromText($H, SRID($o[field]))";return$H;}function
support($oc){return!preg_match("~scheme|sequence|type|view_trigger|materializedview".(min_version(8)?"":"|descidx".(min_version(5.1)?"":"|event|partitioning".(min_version(5)?"":"|routine|trigger|view")))."~",$oc);}function
kill_process($X){return
queries("KILL ".number($X));}function
connection_id(){return"SELECT CONNECTION_ID()";}function
max_connections(){global$h;return$h->result("SELECT @@max_connections");}function
driver_config(){$U=array();$Tf=array();foreach(array(lang(27)=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),lang(28)=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),lang(25)=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),lang(60)=>array("enum"=>65535,"set"=>64),lang(29)=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),lang(31)=>array("geometry"=>0,"point"=>0,"linestring"=>0,"polygon"=>0,"multipoint"=>0,"multilinestring"=>0,"multipolygon"=>0,"geometrycollection"=>0),)as$y=>$X){$U+=$X;$Tf[$y]=array_keys($X);}return
array('possible_drivers'=>array("MySQLi","MySQL","PDO_MySQL"),'jush'=>"sql",'types'=>$U,'structured_types'=>$Tf,'unsigned'=>array("unsigned","zerofill","unsigned zerofill"),'operators'=>array("=","<",">","<=",">=","!=","LIKE","LIKE %%","REGEXP","IN","FIND_IN_SET","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL"),'functions'=>array("char_length","date","from_unixtime","lower","round","floor","ceil","sec_to_time","time_to_sec","upper"),'grouping'=>array("avg","count","count distinct","group_concat","max","min","sum"),'edit_functions'=>array(array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1","date|time"=>"now",),array(number_type()=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",)),);}}$jb=driver_config();$Ne=$jb['possible_drivers'];$x=$jb['jush'];$U=$jb['types'];$Tf=$jb['structured_types'];$Jg=$jb['unsigned'];$pe=$jb['operators'];$Ic=$jb['functions'];$Mc=$jb['grouping'];$Rb=$jb['edit_functions'];if($b->operators===null)$b->operators=$pe;define("SERVER",$_GET[DRIVER]);define("DB",$_GET["db"]);define("ME",preg_replace('~\?.*~','',relative_uri()).'?'.(sid()?SID.'&':'').(SERVER!==null?DRIVER."=".urlencode(SERVER).'&':'').(isset($_GET["username"])?"username=".urlencode($_GET["username"]).'&':'').(DB!=""?'db='.urlencode(DB).'&'.(isset($_GET["ns"])?"ns=".urlencode($_GET["ns"])."&":""):''));$ca="4.8.1";function
page_header($mg,$n="",$Pa=array(),$ng=""){global$ba,$ca,$b,$Mb,$x;page_headers();if(is_ajax()&&$n){page_messages($n);exit;}$og=$mg.($ng!=""?": $ng":"");$pg=strip_tags($og.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".$b->name());echo'<!DOCTYPE html>
<html lang="',$ba,'" dir="',lang(61),'">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<title>',$pg,'</title>
<link rel="stylesheet" type="text/css" href="',h(preg_replace("~\\?.*~","",ME)."?file=default.css&version=4.8.1"),'">
',script_src(preg_replace("~\\?.*~","",ME)."?file=functions.js&version=4.8.1");if($b->head()){echo'<link rel="shortcut icon" type="image/x-icon" href="',h(preg_replace("~\\?.*~","",ME)."?file=favicon.ico&version=4.8.1"),'">
<link rel="apple-touch-icon" href="',h(preg_replace("~\\?.*~","",ME)."?file=favicon.ico&version=4.8.1"),'">
';foreach($b->css()as$yb){echo'<link rel="stylesheet" type="text/css" href="',h($yb),'">
';}}echo'
<body class="',lang(61),' nojs">
';$q=get_temp_dir()."/adminer.version";if(!$_COOKIE["adminer_version"]&&function_exists('openssl_verify')&&file_exists($q)&&filemtime($q)+86400>time()){$Tg=unserialize(file_get_contents($q));$Ve="-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwqWOVuF5uw7/+Z70djoK
RlHIZFZPO0uYRezq90+7Amk+FDNd7KkL5eDve+vHRJBLAszF/7XKXe11xwliIsFs
DFWQlsABVZB3oisKCBEuI71J4kPH8dKGEWR9jDHFw3cWmoH3PmqImX6FISWbG3B8
h7FIx3jEaw5ckVPVTeo5JRm/1DZzJxjyDenXvBQ/6o9DgZKeNDgxwKzH+sw9/YCO
jHnq1cFpOIISzARlrHMa/43YfeNRAm/tsBXjSxembBPo7aQZLAWHmaj5+K19H10B
nCpz9Y++cipkVEiKRGih4ZEvjoFysEOdRLj6WiD/uUNky4xGeA6LaJqh5XpkFkcQ
fQIDAQAB
-----END PUBLIC KEY-----
";if(openssl_verify($Tg["version"],base64_decode($Tg["signature"]),$Ve)==1)$_COOKIE["adminer_version"]=$Tg["version"];}echo'<script',nonce(),'>
mixin(document.body, {onkeydown: bodyKeydown, onclick: bodyClick',(isset($_COOKIE["adminer_version"])?"":", onload: partial(verifyVersion, '$ca', '".js_escape(ME)."', '".get_token()."')");?>});
document.body.className = document.body.className.replace(/ nojs/, ' js');
var offlineMessage = '<?php echo
js_escape(lang(62)),'\';
var thousandsSeparator = \'',js_escape(lang(5)),'\';
</script>

<div id="help" class="jush-',$x,' jsonly hidden"></div>
',script("mixin(qs('#help'), {onmouseover: function () { helpOpen = 1; }, onmouseout: helpMouseout});"),'
<div id="content">
';if($Pa!==null){$_=substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($_?$_:".").'">'.$Mb[DRIVER].'</a> &raquo; ';$_=substr(preg_replace('~\b(db|ns)=[^&]*&~','',ME),0,-1);$M=$b->serverName(SERVER);$M=($M!=""?$M:lang(63));if($Pa===false)echo"$M\n";else{echo"<a href='".h($_)."' accesskey='1' title='Alt+Shift+1'>$M</a> &raquo; ";if($_GET["ns"]!=""||(DB!=""&&is_array($Pa)))echo'<a href="'.h($_."&db=".urlencode(DB).(support("scheme")?"&ns=":"")).'">'.h(DB).'</a> &raquo; ';if(is_array($Pa)){if($_GET["ns"]!="")echo'<a href="'.h(substr(ME,0,-1)).'">'.h($_GET["ns"]).'</a> &raquo; ';foreach($Pa
as$y=>$X){$Fb=(is_array($X)?$X[1]:h($X));if($Fb!="")echo"<a href='".h(ME."$y=").urlencode(is_array($X)?$X[0]:$X)."'>$Fb</a> &raquo; ";}}echo"$mg\n";}}echo"<h2>$og</h2>\n","<div id='ajaxstatus' class='jsonly hidden'></div>\n";restart_session();page_messages($n);$k=&get_session("dbs");if(DB!=""&&$k&&!in_array(DB,$k,true))$k=null;stop_session();define("PAGE_HEADER",1);}function
page_headers(){global$b;header("Content-Type: text/html; charset=utf-8");header("Cache-Control: no-cache");header("X-Frame-Options: deny");header("X-XSS-Protection: 0");header("X-Content-Type-Options: nosniff");header("Referrer-Policy: origin-when-cross-origin");foreach($b->csp()as$xb){$Rc=array();foreach($xb
as$y=>$X)$Rc[]="$y $X";header("Content-Security-Policy: ".implode("; ",$Rc));}$b->headers();}function
csp(){return
array(array("script-src"=>"'self' 'unsafe-inline' 'nonce-".get_nonce()."' 'strict-dynamic'","connect-src"=>"'self'","frame-src"=>"https://www.adminer.org","object-src"=>"'none'","base-uri"=>"'none'","form-action"=>"'self'",),);}function
get_nonce(){static$de;if(!$de)$de=base64_encode(rand_string());return$de;}function
page_messages($n){$Lg=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$Ud=$_SESSION["messages"][$Lg];if($Ud){echo"<div class='message'>".implode("</div>\n<div class='message'>",$Ud)."</div>".script("messagesPrint();");unset($_SESSION["messages"][$Lg]);}if($n)echo"<div class='error'>$n</div>\n";}function
page_footer($Wd=""){global$b,$sg;echo'</div>

';switch_lang();if($Wd!="auth"){echo'<form action="" method="post">
<p class="logout">
<input type="submit" name="logout" value="',lang(64),'" id="logout">
<input type="hidden" name="token" value="',$sg,'">
</p>
</form>
';}echo'<div id="menu">
';$b->navigation($Wd);echo'</div>
',script("setupSubmitHighlight(document);");}function
int32($Zd){while($Zd>=2147483648)$Zd-=4294967296;while($Zd<=-2147483649)$Zd+=4294967296;return(int)$Zd;}function
long2str($W,$Xg){$rf='';foreach($W
as$X)$rf.=pack('V',$X);if($Xg)return
substr($rf,0,end($W));return$rf;}function
str2long($rf,$Xg){$W=array_values(unpack('V*',str_pad($rf,4*ceil(strlen($rf)/4),"\0")));if($Xg)$W[]=strlen($rf);return$W;}function
xxtea_mx($hh,$gh,$Xf,$sd){return
int32((($hh>>5&0x7FFFFFF)^$gh<<2)+(($gh>>3&0x1FFFFFFF)^$hh<<4))^int32(($Xf^$gh)+($sd^$hh));}function
encrypt_string($Sf,$y){if($Sf=="")return"";$y=array_values(unpack("V*",pack("H*",md5($y))));$W=str2long($Sf,true);$Zd=count($W)-1;$hh=$W[$Zd];$gh=$W[0];$We=floor(6+52/($Zd+1));$Xf=0;while($We-->0){$Xf=int32($Xf+0x9E3779B9);$Qb=$Xf>>2&3;for($_e=0;$_e<$Zd;$_e++){$gh=$W[$_e+1];$Yd=xxtea_mx($hh,$gh,$Xf,$y[$_e&3^$Qb]);$hh=int32($W[$_e]+$Yd);$W[$_e]=$hh;}$gh=$W[0];$Yd=xxtea_mx($hh,$gh,$Xf,$y[$_e&3^$Qb]);$hh=int32($W[$Zd]+$Yd);$W[$Zd]=$hh;}return
long2str($W,false);}function
decrypt_string($Sf,$y){if($Sf=="")return"";if(!$y)return
false;$y=array_values(unpack("V*",pack("H*",md5($y))));$W=str2long($Sf,false);$Zd=count($W)-1;$hh=$W[$Zd];$gh=$W[0];$We=floor(6+52/($Zd+1));$Xf=int32($We*0x9E3779B9);while($Xf){$Qb=$Xf>>2&3;for($_e=$Zd;$_e>0;$_e--){$hh=$W[$_e-1];$Yd=xxtea_mx($hh,$gh,$Xf,$y[$_e&3^$Qb]);$gh=int32($W[$_e]-$Yd);$W[$_e]=$gh;}$hh=$W[$Zd];$Yd=xxtea_mx($hh,$gh,$Xf,$y[$_e&3^$Qb]);$gh=int32($W[0]-$Yd);$W[0]=$gh;$Xf=int32($Xf-0x9E3779B9);}return
long2str($W,true);}$h='';$Qc=$_SESSION["token"];if(!$Qc)$_SESSION["token"]=rand(1,1e6);$sg=get_token();$Je=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$X){list($y)=explode(":",$X);$Je[$y]=$X;}}function
add_invalid_login(){global$b;$Gc=file_open_lock(get_temp_dir()."/adminer.invalid");if(!$Gc)return;$nd=unserialize(stream_get_contents($Gc));$jg=time();if($nd){foreach($nd
as$od=>$X){if($X[0]<$jg)unset($nd[$od]);}}$md=&$nd[$b->bruteForceKey()];if(!$md)$md=array($jg+30*60,0);$md[1]++;file_write_unlock($Gc,serialize($nd));}function
check_invalid_login(){global$b;$nd=unserialize(@file_get_contents(get_temp_dir()."/adminer.invalid"));$md=($nd?$nd[$b->bruteForceKey()]:array());$ce=($md[1]>29?$md[0]-time():0);if($ce>0)auth_error(lang(65,ceil($ce/60)));}$Ca=$_POST["auth"];if($Ca){session_regenerate_id();$Sg=$Ca["driver"];$M=$Ca["server"];$V=$Ca["username"];$E=(string)$Ca["password"];$l=$Ca["db"];set_password($Sg,$M,$V,$E);$_SESSION["db"][$Sg][$M][$V][$l]=true;if($Ca["permanent"]){$y=base64_encode($Sg)."-".base64_encode($M)."-".base64_encode($V)."-".base64_encode($l);$Se=$b->permanentLogin(true);$Je[$y]="$y:".base64_encode($Se?encrypt_string($E,$Se):"");cookie("adminer_permanent",implode(" ",$Je));}if(count($_POST)==1||DRIVER!=$Sg||SERVER!=$M||$_GET["username"]!==$V||DB!=$l)redirect(auth_url($Sg,$M,$V,$l));}elseif($_POST["logout"]&&(!$Qc||verify_token())){foreach(array("pwds","db","dbs","queries")as$y)set_session($y,null);unset_permanent();redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1),lang(66).' '.lang(67));}elseif($Je&&!$_SESSION["pwds"]){session_regenerate_id();$Se=$b->permanentLogin();foreach($Je
as$y=>$X){list(,$Wa)=explode(":",$X);list($Sg,$M,$V,$l)=array_map('base64_decode',explode("-",$y));set_password($Sg,$M,$V,decrypt_string(base64_decode($Wa),$Se));$_SESSION["db"][$Sg][$M][$V][$l]=true;}}function
unset_permanent(){global$Je;foreach($Je
as$y=>$X){list($Sg,$M,$V,$l)=array_map('base64_decode',explode("-",$y));if($Sg==DRIVER&&$M==SERVER&&$V==$_GET["username"]&&$l==DB)unset($Je[$y]);}cookie("adminer_permanent",implode(" ",$Je));}function
auth_error($n){global$b,$Qc;$Df=session_name();if(isset($_GET["username"])){header("HTTP/1.1 403 Forbidden");if(($_COOKIE[$Df]||$_GET[$Df])&&!$Qc)$n=lang(68);else{restart_session();add_invalid_login();$E=get_password();if($E!==null){if($E===false)$n.=($n?'<br>':'').lang(69,target_blank(),'<code>permanentLogin()</code>');set_password(DRIVER,SERVER,$_GET["username"],null);}unset_permanent();}}if(!$_COOKIE[$Df]&&$_GET[$Df]&&ini_bool("session.use_only_cookies"))$n=lang(70);$Ce=session_get_cookie_params();cookie("adminer_key",($_COOKIE["adminer_key"]?$_COOKIE["adminer_key"]:rand_string()),$Ce["lifetime"]);page_header(lang(36),$n,null);echo"<form action='' method='post'>\n","<div>";if(hidden_fields($_POST,array("auth")))echo"<p class='message'>".lang(71)."\n";echo"</div>\n";$b->loginForm();echo"</form>\n";page_footer("auth");exit;}if(isset($_GET["username"])&&!class_exists("Min_DB")){unset($_SESSION["pwds"][DRIVER]);unset_permanent();page_header(lang(72),lang(73,implode(", ",$Ne)),false);page_footer("auth");exit;}stop_session(true);if(isset($_GET["username"])&&is_string(get_password())){list($Wc,$Le)=explode(":",SERVER,2);if(preg_match('~^\s*([-+]?\d+)~',$Le,$A)&&($A[1]<1024||$A[1]>65535))auth_error(lang(74));check_invalid_login();$h=connect();$m=new
Min_Driver($h);}$Hd=null;if(!is_object($h)||($Hd=$b->login($_GET["username"],get_password()))!==true){$n=(is_string($h)?h($h):(is_string($Hd)?$Hd:lang(32)));auth_error($n.(preg_match('~^ | $~',get_password())?'<br>'.lang(75):''));}if($_POST["logout"]&&$Qc&&!verify_token()){page_header(lang(64),lang(76));page_footer("db");exit;}if($Ca&&$_POST["token"])$_POST["token"]=$sg;$n='';if($_POST){if(!verify_token()){$jd="max_input_vars";$Rd=ini_get($jd);if(extension_loaded("suhosin")){foreach(array("suhosin.request.max_vars","suhosin.post.max_vars")as$y){$X=ini_get($y);if($X&&(!$Rd||$X<$Rd)){$jd=$y;$Rd=$X;}}}$n=(!$_POST["token"]&&$Rd?lang(77,"'$jd'"):lang(76).' '.lang(78));}}elseif($_SERVER["REQUEST_METHOD"]=="POST"){$n=lang(79,"'post_max_size'");if(isset($_GET["sql"]))$n.=' '.lang(80);}function
email_header($Rc){return"=?UTF-8?B?".base64_encode($Rc)."?=";}function
send_mail($Ub,$Vf,$Td,$Hc="",$sc=array()){$ac=(DIRECTORY_SEPARATOR=="/"?"\n":"\r\n");$Td=str_replace("\n",$ac,wordwrap(str_replace("\r","","$Td\n")));$Oa=uniqid("boundary");$Aa="";foreach((array)$sc["error"]as$y=>$X){if(!$X)$Aa.="--$Oa$ac"."Content-Type: ".str_replace("\n","",$sc["type"][$y]).$ac."Content-Disposition: attachment; filename=\"".preg_replace('~["\n]~','',$sc["name"][$y])."\"$ac"."Content-Transfer-Encoding: base64$ac$ac".chunk_split(base64_encode(file_get_contents($sc["tmp_name"][$y])),76,$ac).$ac;}$Ka="";$Sc="Content-Type: text/plain; charset=utf-8$ac"."Content-Transfer-Encoding: 8bit";if($Aa){$Aa.="--$Oa--$ac";$Ka="--$Oa$ac$Sc$ac$ac";$Sc="Content-Type: multipart/mixed; boundary=\"$Oa\"";}$Sc.=$ac."MIME-Version: 1.0$ac"."X-Mailer: Adminer Editor".($Hc?$ac."From: ".str_replace("\n","",$Hc):"");return
mail($Ub,email_header($Vf),$Ka.$Td.$Aa,$Sc);}function
like_bool($o){return
preg_match("~bool|(tinyint|bit)\\(1\\)~",$o["full_type"]);}$h->select_db($b->database());$ke="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";$Mb[DRIVER]=lang(36);if(isset($_GET["select"])&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])$_GET["edit"]=$_GET["select"];if(isset($_GET["download"])){$a=$_GET["download"];$p=fields($a);header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$_GET["where"])).".".friendly_url($_GET["field"]));$K=array(idf_escape($_GET["field"]));$G=$m->select($a,$K,array(where($_GET,$p)),$K);$I=($G?$G->fetch_row():array());echo$m->value($I[0],$p[$_GET["field"]]);exit;}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$p=fields($a);$Z=(isset($_GET["select"])?($_POST["check"]&&count($_POST["check"])==1?where_check($_POST["check"][0],$p):""):where($_GET,$p));$Kg=(isset($_GET["select"])?$_POST["edit"]:$Z);foreach($p
as$B=>$o){if(!isset($o["privileges"][$Kg?"update":"insert"])||$b->fieldName($o)==""||$o["generated"])unset($p[$B]);}if($_POST&&!$n&&!isset($_GET["select"])){$Gd=$_POST["referer"];if($_POST["insert"])$Gd=($Kg?null:$_SERVER["REQUEST_URI"]);elseif(!preg_match('~^.+&select=.+$~',$Gd))$Gd=ME."select=".urlencode($a);$w=indexes($a);$Fg=unique_array($_GET["where"],$w);$Ze="\nWHERE $Z";if(isset($_POST["delete"]))queries_redirect($Gd,lang(81),$m->delete($a,$Ze,!$Fg));else{$N=array();foreach($p
as$B=>$o){$X=process_input($o);if($X!==false&&$X!==null)$N[idf_escape($B)]=$X;}if($Kg){if(!$N)redirect($Gd);queries_redirect($Gd,lang(82),$m->update($a,$N,$Ze,!$Fg));if(is_ajax()){page_headers();page_messages($n);exit;}}else{$G=$m->insert($a,$N);$Ad=($G?last_id():0);queries_redirect($Gd,lang(83,($Ad?" $Ad":"")),$G);}}}$I=null;if($_POST["save"])$I=(array)$_POST["fields"];elseif($Z){$K=array();foreach($p
as$B=>$o){if(isset($o["privileges"]["select"])){$za=convert_field($o);if($_POST["clone"]&&$o["auto_increment"])$za="''";if($x=="sql"&&preg_match("~enum|set~",$o["type"]))$za="1*".idf_escape($B);$K[]=($za?"$za AS ":"").idf_escape($B);}}$I=array();if(!support("table"))$K=array("*");if($K){$G=$m->select($a,$K,array($Z),$K,array(),(isset($_GET["select"])?2:1));if(!$G)$n=error();else{$I=$G->fetch_assoc();if(!$I)$I=false;}if(isset($_GET["select"])&&(!$I||$G->fetch_assoc()))$I=null;}}if(!support("table")&&!$p){if(!$Z){$G=$m->select($a,array("*"),$Z,array("*"));$I=($G?$G->fetch_assoc():false);if(!$I)$I=array($m->primary=>"");}if($I){foreach($I
as$y=>$X){if(!$Z)$I[$y]=null;$p[$y]=array("field"=>$y,"null"=>($y!=$m->primary),"auto_increment"=>($y==$m->primary));}}}edit_form($a,$p,$I,$Kg);}elseif(isset($_GET["select"])){$a=$_GET["select"];$R=table_status1($a);$w=indexes($a);$p=fields($a);$Dc=column_foreign_keys($a);$ie=$R["Oid"];parse_str($_COOKIE["adminer_import"],$ta);$pf=array();$f=array();$hg=null;foreach($p
as$y=>$o){$B=$b->fieldName($o);if(isset($o["privileges"]["select"])&&$B!=""){$f[$y]=html_entity_decode(strip_tags($B),ENT_QUOTES);if(is_shortable($o))$hg=$b->selectLengthProcess();}$pf+=$o["privileges"];}list($K,$Jc)=$b->selectColumnsProcess($f,$w);$pd=count($Jc)<count($K);$Z=$b->selectSearchProcess($p,$w);$se=$b->selectOrderProcess($p,$w);$z=$b->selectLimitProcess();if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$Gg=>$I){$za=convert_field($p[key($I)]);$K=array($za?$za:idf_escape(key($I)));$Z[]=where_check($Gg,$p);$H=$m->select($a,$K,$Z,$K);if($H)echo
reset($H->fetch_row());}exit;}$Pe=$Ig=null;foreach($w
as$v){if($v["type"]=="PRIMARY"){$Pe=array_flip($v["columns"]);$Ig=($K?$Pe:array());foreach($Ig
as$y=>$X){if(in_array(idf_escape($y),$K))unset($Ig[$y]);}break;}}if($ie&&!$Pe){$Pe=$Ig=array($ie=>0);$w[]=array("type"=>"PRIMARY","columns"=>array($ie));}if($_POST&&!$n){$ch=$Z;if(!$_POST["all"]&&is_array($_POST["check"])){$Va=array();foreach($_POST["check"]as$Sa)$Va[]=where_check($Sa,$p);$ch[]="((".implode(") OR (",$Va)."))";}$ch=($ch?"\nWHERE ".implode(" AND ",$ch):"");if($_POST["export"]){cookie("adminer_import","output=".urlencode($_POST["output"])."&format=".urlencode($_POST["format"]));dump_headers($a);$b->dumpTable($a,"");$Hc=($K?implode(", ",$K):"*").convert_fields($f,$p,$K)."\nFROM ".table($a);$Lc=($Jc&&$pd?"\nGROUP BY ".implode(", ",$Jc):"").($se?"\nORDER BY ".implode(", ",$se):"");if(!is_array($_POST["check"])||$Pe)$F="SELECT $Hc$ch$Lc";else{$Eg=array();foreach($_POST["check"]as$X)$Eg[]="(SELECT".limit($Hc,"\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$p).$Lc,1).")";$F=implode(" UNION ALL ",$Eg);}$b->dumpData($a,"table",$F);exit;}if(!$b->selectEmailProcess($Z,$Dc)){if($_POST["save"]||$_POST["delete"]){$G=true;$ua=0;$N=array();if(!$_POST["delete"]){foreach($f
as$B=>$X){$X=process_input($p[$B]);if($X!==null&&($_POST["clone"]||$X!==false))$N[idf_escape($B)]=($X!==false?$X:idf_escape($B));}}if($_POST["delete"]||$N){if($_POST["clone"])$F="INTO ".table($a)." (".implode(", ",array_keys($N)).")\nSELECT ".implode(", ",$N)."\nFROM ".table($a);if($_POST["all"]||($Pe&&is_array($_POST["check"]))||$pd){$G=($_POST["delete"]?$m->delete($a,$ch):($_POST["clone"]?queries("INSERT $F$ch"):$m->update($a,$N,$ch)));$ua=$h->affected_rows;}else{foreach((array)$_POST["check"]as$X){$Yg="\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$p);$G=($_POST["delete"]?$m->delete($a,$Yg,1):($_POST["clone"]?queries("INSERT".limit1($a,$F,$Yg)):$m->update($a,$N,$Yg,1)));if(!$G)break;$ua+=$h->affected_rows;}}}$Td=lang(84,$ua);if($_POST["clone"]&&$G&&$ua==1){$Ad=last_id();if($Ad)$Td=lang(83," $Ad");}queries_redirect(remove_from_uri($_POST["all"]&&$_POST["delete"]?"page":""),$Td,$G);if(!$_POST["delete"]){edit_form($a,$p,(array)$_POST["fields"],!$_POST["clone"]);page_footer();exit;}}elseif(!$_POST["import"]){if(!$_POST["val"])$n=lang(85);else{$G=true;$ua=0;foreach($_POST["val"]as$Gg=>$I){$N=array();foreach($I
as$y=>$X){$y=bracket_escape($y,1);$N[idf_escape($y)]=(preg_match('~char|text~',$p[$y]["type"])||$X!=""?$b->processInput($p[$y],$X):"NULL");}$G=$m->update($a,$N," WHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($Gg,$p),!$pd&&!$Pe," ");if(!$G)break;$ua+=$h->affected_rows;}queries_redirect(remove_from_uri(),lang(84,$ua),$G);}}elseif(!is_string($rc=get_file("csv_file",true)))$n=upload_error($rc);elseif(!preg_match('~~u',$rc))$n=lang(86);else{cookie("adminer_import","output=".urlencode($ta["output"])."&format=".urlencode($_POST["separator"]));$G=true;$db=array_keys($p);preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~',$rc,$Nd);$ua=count($Nd[0]);$m->begin();$L=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));$J=array();foreach($Nd[0]as$y=>$X){preg_match_all("~((?>\"[^\"]*\")+|[^$L]*)$L~",$X.$L,$Od);if(!$y&&!array_diff($Od[1],$db)){$db=$Od[1];$ua--;}else{$N=array();foreach($Od[1]as$s=>$ab)$N[idf_escape($db[$s])]=($ab==""&&$p[$db[$s]]["null"]?"NULL":q(str_replace('""','"',preg_replace('~^"|"$~','',$ab))));$J[]=$N;}}$G=(!$J||$m->insertUpdate($a,$J,$Pe));if($G)$G=$m->commit();queries_redirect(remove_from_uri("page"),lang(87,$ua),$G);$m->rollback();}}}$ag=$b->tableName($R);if(is_ajax()){page_headers();ob_start();}else
page_header(lang(48).": $ag",$n);$N=null;if(isset($pf["insert"])||!support("table")){$N="";foreach((array)$_GET["where"]as$X){if($Dc[$X["col"]]&&count($Dc[$X["col"]])==1&&($X["op"]=="="||(!$X["op"]&&!preg_match('~[_%]~',$X["val"]))))$N.="&set".urlencode("[".bracket_escape($X["col"])."]")."=".urlencode($X["val"]);}}$b->selectLinks($R,$N);if(!$f&&support("table"))echo"<p class='error'>".lang(88).($p?".":": ".error())."\n";else{echo"<form action='' id='form'>\n","<div style='display: none;'>";hidden_fields_get();echo(DB!=""?'<input type="hidden" name="db" value="'.h(DB).'">'.(isset($_GET["ns"])?'<input type="hidden" name="ns" value="'.h($_GET["ns"]).'">':""):"");echo'<input type="hidden" name="select" value="'.h($a).'">',"</div>\n";$b->selectColumnsPrint($K,$f);$b->selectSearchPrint($Z,$f,$w);$b->selectOrderPrint($se,$f,$w);$b->selectLimitPrint($z);$b->selectLengthPrint($hg);$b->selectActionPrint($w);echo"</form>\n";$D=$_GET["page"];if($D=="last"){$Fc=$h->result(count_rows($a,$Z,$pd,$Jc));$D=floor(max(0,$Fc-1)/$z);}$vf=$K;$Kc=$Jc;if(!$vf){$vf[]="*";$rb=convert_fields($f,$p,$K);if($rb)$vf[]=substr($rb,2);}foreach($K
as$y=>$X){$o=$p[idf_unescape($X)];if($o&&($za=convert_field($o)))$vf[$y]="$za AS $X";}if(!$pd&&$Ig){foreach($Ig
as$y=>$X){$vf[]=idf_escape($y);if($Kc)$Kc[]=idf_escape($y);}}$G=$m->select($a,$vf,$Z,$Kc,$se,$z,$D,true);if(!$G)echo"<p class='error'>".error()."\n";else{if($x=="mssql"&&$D)$G->seek($z*$D);$Wb=array();echo"<form action='' method='post' enctype='multipart/form-data'>\n";$J=array();while($I=$G->fetch_assoc()){if($D&&$x=="oracle")unset($I["RNUM"]);$J[]=$I;}if($_GET["page"]!="last"&&$z!=""&&$Jc&&$pd&&$x=="sql")$Fc=$h->result(" SELECT FOUND_ROWS()");if(!$J)echo"<p class='message'>".lang(12)."\n";else{$Ja=$b->backwardKeys($a,$ag);echo"<div class='scrollable'>","<table id='table' cellspacing='0' class='nowrap checkable'>",script("mixin(qs('#table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true), onkeydown: editingKeydown});"),"<thead><tr>".(!$Jc&&$K?"":"<td><input type='checkbox' id='all-page' class='jsonly'>".script("qs('#all-page').onclick = partial(formCheck, /check/);","")." <a href='".h($_GET["modify"]?remove_from_uri("modify"):$_SERVER["REQUEST_URI"]."&modify=1")."'>".lang(89)."</a>");$ae=array();$Ic=array();reset($K);$bf=1;foreach($J[0]as$y=>$X){if(!isset($Ig[$y])){$X=$_GET["columns"][key($K)];$o=$p[$K?($X?$X["col"]:current($K)):$y];$B=($o?$b->fieldName($o,$bf):($X["fun"]?"*":$y));if($B!=""){$bf++;$ae[$y]=$B;$e=idf_escape($y);$Xc=remove_from_uri('(order|desc)[^=]*|page').'&order%5B0%5D='.urlencode($y);$Fb="&desc%5B0%5D=1";echo"<th id='th[".h(bracket_escape($y))."]'>".script("mixin(qsl('th'), {onmouseover: partial(columnMouse), onmouseout: partial(columnMouse, ' hidden')});",""),'<a href="'.h($Xc.($se[0]==$e||$se[0]==$y||(!$se&&$pd&&$Jc[0]==$e)?$Fb:'')).'">';echo
apply_sql_function($X["fun"],$B)."</a>";echo"<span class='column hidden'>","<a href='".h($Xc.$Fb)."' title='".lang(90)."' class='text'> â†“</a>";if(!$X["fun"]){echo'<a href="#fieldset-search" title="'.lang(43).'" class="text jsonly"> =</a>',script("qsl('a').onclick = partial(selectSearch, '".js_escape($y)."');");}echo"</span>";}$Ic[$y]=$X["fun"];next($K);}}$Dd=array();if($_GET["modify"]){foreach($J
as$I){foreach($I
as$y=>$X)$Dd[$y]=max($Dd[$y],min(40,strlen(utf8_decode($X))));}}echo($Ja?"<th>".lang(91):"")."</thead>\n";if(is_ajax()){if($z%2==1&&$D%2==1)odd();ob_end_clean();}foreach($b->rowDescriptions($J,$Dc)as$Zd=>$I){$Fg=unique_array($J[$Zd],$w);if(!$Fg){$Fg=array();foreach($J[$Zd]as$y=>$X){if(!preg_match('~^(COUNT\((\*|(DISTINCT )?`(?:[^`]|``)+`)\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\(`(?:[^`]|``)+`\))$~',$y))$Fg[$y]=$X;}}$Gg="";foreach($Fg
as$y=>$X){if(($x=="sql"||$x=="pgsql")&&preg_match('~char|text|enum|set~',$p[$y]["type"])&&strlen($X)>64){$y=(strpos($y,'(')?$y:idf_escape($y));$y="MD5(".($x!='sql'||preg_match("~^utf8~",$p[$y]["collation"])?$y:"CONVERT($y USING ".charset($h).")").")";$X=md5($X);}$Gg.="&".($X!==null?urlencode("where[".bracket_escape($y)."]")."=".urlencode($X):"null%5B%5D=".urlencode($y));}echo"<tr".odd().">".(!$Jc&&$K?"":"<td>".checkbox("check[]",substr($Gg,1),in_array(substr($Gg,1),(array)$_POST["check"])).($pd||information_schema(DB)?"":" <a href='".h(ME."edit=".urlencode($a).$Gg)."' class='edit'>".lang(92)."</a>"));foreach($I
as$y=>$X){if(isset($ae[$y])){$o=$p[$y];$X=$m->value($X,$o);if($X!=""&&(!isset($Wb[$y])||$Wb[$y]!=""))$Wb[$y]=(is_mail($X)?$ae[$y]:"");$_="";if(preg_match('~blob|bytea|raw|file~',$o["type"])&&$X!="")$_=ME.'download='.urlencode($a).'&field='.urlencode($y).$Gg;if(!$_&&$X!==null){foreach((array)$Dc[$y]as$Cc){if(count($Dc[$y])==1||end($Cc["source"])==$y){$_="";foreach($Cc["source"]as$s=>$Kf)$_.=where_link($s,$Cc["target"][$s],$J[$Zd][$Kf]);$_=($Cc["db"]!=""?preg_replace('~([?&]db=)[^&]+~','\1'.urlencode($Cc["db"]),ME):ME).'select='.urlencode($Cc["table"]).$_;if($Cc["ns"])$_=preg_replace('~([?&]ns=)[^&]+~','\1'.urlencode($Cc["ns"]),$_);if(count($Cc["source"])==1)break;}}}if($y=="COUNT(*)"){$_=ME."select=".urlencode($a);$s=0;foreach((array)$_GET["where"]as$W){if(!array_key_exists($W["col"],$Fg))$_.=where_link($s++,$W["col"],$W["val"],$W["op"]);}foreach($Fg
as$sd=>$W)$_.=where_link($s++,$sd,$W);}$X=select_value($X,$_,$o,$hg);$t=h("val[$Gg][".bracket_escape($y)."]");$Y=$_POST["val"][$Gg][bracket_escape($y)];$Sb=!is_array($I[$y])&&is_utf8($X)&&$J[$Zd][$y]==$I[$y]&&!$Ic[$y];$gg=preg_match('~text|lob~',$o["type"]);echo"<td id='$t'";if(($_GET["modify"]&&$Sb)||$Y!==null){$Nc=h($Y!==null?$Y:$I[$y]);echo">".($gg?"<textarea name='$t' cols='30' rows='".(substr_count($I[$y],"\n")+1)."'>$Nc</textarea>":"<input name='$t' value='$Nc' size='$Dd[$y]'>");}else{$Id=strpos($X,"<i>â€¦</i>");echo" data-text='".($Id?2:($gg?1:0))."'".($Sb?"":" data-warning='".h(lang(93))."'").">$X</td>";}}}if($Ja)echo"<td>";$b->backwardKeysPrint($Ja,$J[$Zd]);echo"</tr>\n";}if(is_ajax())exit;echo"</table>\n","</div>\n";}if(!is_ajax()){if($J||$D){$fc=true;if($_GET["page"]!="last"){if($z==""||(count($J)<$z&&($J||!$D)))$Fc=($D?$D*$z:0)+count($J);elseif($x!="sql"||!$pd){$Fc=($pd?false:found_rows($R,$Z));if($Fc<max(1e4,2*($D+1)*$z))$Fc=reset(slow_query(count_rows($a,$Z,$pd,$Jc)));else$fc=false;}}$Ae=($z!=""&&($Fc===false||$Fc>$z||$D));if($Ae){echo(($Fc===false?count($J)+1:$Fc-$D*$z)>$z?'<p><a href="'.h(remove_from_uri("page")."&page=".($D+1)).'" class="loadmore">'.lang(94).'</a>'.script("qsl('a').onclick = partial(selectLoadMore, ".(+$z).", '".lang(95)."â€¦');",""):''),"\n";}}echo"<div class='footer'><div>\n";if($J||$D){if($Ae){$Pd=($Fc===false?$D+(count($J)>=$z?2:1):floor(($Fc-1)/$z));echo"<fieldset>";if($x!="simpledb"){echo"<legend><a href='".h(remove_from_uri("page"))."'>".lang(96)."</a></legend>",script("qsl('a').onclick = function () { pageClick(this.href, +prompt('".lang(96)."', '".($D+1)."')); return false; };"),pagination(0,$D).($D>5?" â€¦":"");for($s=max(1,$D-4);$s<min($Pd,$D+5);$s++)echo
pagination($s,$D);if($Pd>0){echo($D+5<$Pd?" â€¦":""),($fc&&$Fc!==false?pagination($Pd,$D):" <a href='".h(remove_from_uri("page")."&page=last")."' title='~$Pd'>".lang(97)."</a>");}}else{echo"<legend>".lang(96)."</legend>",pagination(0,$D).($D>1?" â€¦":""),($D?pagination($D,$D):""),($Pd>$D?pagination($D+1,$D).($Pd>$D+1?" â€¦":""):"");}echo"</fieldset>\n";}echo"<fieldset>","<legend>".lang(98)."</legend>";$Kb=($fc?"":"~ ").$Fc;echo
checkbox("all",1,0,($Fc!==false?($fc?"":"~ ").lang(99,$Fc):""),"var checked = formChecked(this, /check/); selectCount('selected', this.checked ? '$Kb' : checked); selectCount('selected2', this.checked || !checked ? '$Kb' : checked);")."\n","</fieldset>\n";if($b->selectCommandPrint()){echo'<fieldset',($_GET["modify"]?'':' class="jsonly"'),'><legend>',lang(89),'</legend><div>
<input type="submit" value="',lang(14),'"',($_GET["modify"]?'':' title="'.lang(85).'"'),'>
</div></fieldset>
<fieldset><legend>',lang(100),' <span id="selected"></span></legend><div>
<input type="submit" name="edit" value="',lang(10),'">
<input type="submit" name="clone" value="',lang(101),'">
<input type="submit" name="delete" value="',lang(18),'">',confirm(),'</div></fieldset>
';}$Ec=$b->dumpFormat();foreach((array)$_GET["columns"]as$e){if($e["fun"]){unset($Ec['sql']);break;}}if($Ec){print_fieldset("export",lang(102)." <span id='selected2'></span>");$ye=$b->dumpOutput();echo($ye?html_select("output",$ye,$ta["output"])." ":""),html_select("format",$Ec,$ta["format"])," <input type='submit' name='export' value='".lang(102)."'>\n","</div></fieldset>\n";}$b->selectEmailPrint(array_filter($Wb,'strlen'),$f);}echo"</div></div>\n";if($b->selectImportPrint()){echo"<div>","<a href='#import'>".lang(103)."</a>",script("qsl('a').onclick = partial(toggle, 'import');",""),"<span id='import' class='hidden'>: ","<input type='file' name='csv_file'> ",html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$ta["format"],1);echo" <input type='submit' name='import' value='".lang(103)."'>","</span>","</div>";}echo"<input type='hidden' name='token' value='$sg'>\n","</form>\n",(!$Jc&&$K?"":script("tableCheck();"));}}}if(is_ajax()){ob_end_clean();exit;}}elseif(isset($_GET["script"])){if($_GET["script"]=="kill")$h->query("KILL ".number($_POST["kill"]));elseif(list($Q,$t,$B)=$b->_foreignColumn(column_foreign_keys($_GET["source"]),$_GET["field"])){$z=11;$G=$h->query("SELECT $t, $B FROM ".table($Q)." WHERE ".(preg_match('~^[0-9]+$~',$_GET["value"])?"$t = $_GET[value] OR ":"")."$B LIKE ".q("$_GET[value]%")." ORDER BY 2 LIMIT $z");for($s=1;($I=$G->fetch_row())&&$s<$z;$s++)echo"<a href='".h(ME."edit=".urlencode($Q)."&where".urlencode("[".bracket_escape(idf_unescape($t))."]")."=".urlencode($I[0]))."'>".h($I[1])."</a><br>\n";if($I)echo"...\n";}exit;}else{page_header(lang(63),"",false);if($b->homepage()){echo"<form action='' method='post'>\n","<p>".lang(104).": <input type='search' name='query' value='".h($_POST["query"])."'> <input type='submit' value='".lang(43)."'>\n";if($_POST["query"]!="")search_tables();echo"<div class='scrollable'>\n","<table cellspacing='0' class='nowrap checkable'>\n",script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"),'<thead><tr class="wrap">','<td><input id="check-all" type="checkbox" class="jsonly">'.script("qs('#check-all').onclick = partial(formCheck, /^tables\[/);",""),'<th>'.lang(105),'<td>'.lang(106),"</thead>\n";foreach(table_status()as$Q=>$I){$B=$b->tableName($I);if(isset($I["Engine"])&&$B!=""){echo'<tr'.odd().'><td>'.checkbox("tables[]",$Q,in_array($Q,(array)$_POST["tables"],true)),"<th><a href='".h(ME).'select='.urlencode($Q)."'>$B</a>";$X=format_number($I["Rows"]);echo"<td align='right'><a href='".h(ME."edit=").urlencode($Q)."'>".($I["Engine"]=="InnoDB"&&$X?"~ $X":$X)."</a>";}}echo"</table>\n","</div>\n","</form>\n",script("tableCheck();");}}page_footer();