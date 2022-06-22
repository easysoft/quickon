<?php
/**
 * The export2xml view file of file module of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     file
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php echo "<?xml version='1.0' encoding='utf-8'?><xml>\n";?>
<fields>
<?php
foreach($fields as $fieldName => $fieldLabel)
{
  echo "  <$fieldName>$fieldLabel</$fieldName>\n";
}
?>
</fields>
<rows>
<?php
foreach($rows as $row)
{
    echo "  <row>\n";
    foreach($fields as $fieldName => $fieldLabel)
    {
        $fieldValue = isset($row->$fieldName) ? htmlSpecialString($row->$fieldName) : '';
        echo "    <$fieldName>$fieldValue</$fieldName>\n";
    }
    echo "  </row>\n";
}
?>
</rows>
</xml>
