<?php
/**
 * The export2csv view file of file module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.cn)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     file
 * @version     $Id$
 * @link        https://www.qucheng.cn
 */
?>
<?php
echo '"'. implode('","', $fields) . '"' . "\n";
foreach($rows as $row)
{
    echo '"';
    foreach($fields as $fieldName => $fieldLabel)
    {
        isset($row->$fieldName) ? print(str_replace('"', '“', htmlspecialchars_decode(strip_tags($row->$fieldName, '<img>')))) : print('');
        echo '","';
    }
    echo '"' . "\n";
}
if($this->post->kind == 'task' && $config->vision != 'lite') echo $this->lang->file->childTaskTips;
