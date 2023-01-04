<?php
/**
 * The common simplified chinese file of QuCheng.
 *
 * @copyright   Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     common
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
include(dirname(__FILE__) . '/common.php');

global $config;

$lang->arrow     = '&nbsp;<i class="icon-angle-right"></i>&nbsp;';
$lang->colon     = '-';
$lang->comma     = '，';
$lang->dot       = '。';
$lang->at        = ' 于 ';
$lang->downArrow = '↓';
$lang->null      = '空';
$lang->ellipsis  = '…';
$lang->percent   = '%';
$lang->dash      = '-';
$lang->and       = '和';

$lang->qucheng         = '渠成';
$lang->quchengPlatform = '渠成平台';
$lang->aboutQuCheng    = '关于渠成';
$lang->quchengSummary  = '基于云原生的应用交付平台。';

$lang->pmsName        = '开源版';
$lang->proName        = '专业版';
$lang->bizName        = '企业版';
$lang->maxName        = '旗舰版';
$lang->liteName       = '迅捷版';
$lang->logoImg        = 'qucheng-logo.svg';
$lang->welcome        = "渠成云原生应用交付平台";
$lang->logout         = '退出';
$lang->login          = '登录';
$lang->help           = '帮助';
$lang->profile        = '个人档案';
$lang->changePassword = '修改密码';
$lang->unfoldMenu     = '展开导航';
$lang->collapseMenu   = '收起导航';
$lang->preference     = '个性化设置';
$lang->tutorialAB     = '新手引导';
$lang->runInfo        = "<div class='row'><div class='u-1 a-center' id='debugbar'>时间: %s 毫秒, 内存: %s KB, 查询: %s.  </div></div>";
$lang->agreement      = "已阅读并同意<a href='http://zpl.pub/page/zplv12.html' target='_blank'>《Z PUBLIC LICENSE授权协议1.2》</a>。<span class='text-danger'>未经许可，不得去除、隐藏或遮掩软件的任何标志及链接。</span>";
$lang->designedByAIUX = "<a href='https://aiuxdesign.com' class='link-aiux' target='_blank'><i class='icon icon-aiux'></i> 艾体验设计</a>";
$lang->hello          = '您好！';

$lang->version    = '版本';
$lang->desc       = '说明';
$lang->operation  = '操作';

$lang->reset          = '重填';
$lang->cancel         = '取消';
$lang->refresh        = '刷新';
$lang->create         = '新建';
$lang->edit           = '编辑';
$lang->delete         = '删除';
$lang->close          = '关闭';
$lang->unlink         = '移除';
$lang->import         = '导入';
$lang->export         = '导出';
$lang->setFileName    = '文件名：';
$lang->submitting     = '稍候...';
$lang->save           = '保存';
$lang->confirm        = '确认';
$lang->preview        = '查看';
$lang->goback         = '返回';
$lang->goPC           = 'PC版';
$lang->more           = '更多';
$lang->moreLink       = 'More';
$lang->day            = '天';
$lang->customConfig   = '自定义';
$lang->public         = '公共';
$lang->trunk          = '主干';
$lang->sort           = '排序';
$lang->required       = '必填';
$lang->noData         = '暂无';
$lang->fullscreen     = '全屏';
$lang->retrack        = '收起';
$lang->whitelist      = '访问白名单';
$lang->globalSetting  = '全局设置';
$lang->waterfallModel = '瀑布模型';
$lang->all            = '所有';
$lang->viewDetails    = '查看详情';

$lang->actions         = '操作';
$lang->restore         = '恢复默认';
$lang->comment         = '备注';
$lang->history         = '历史记录';
$lang->attatch         = '附件';
$lang->reverse         = '切换顺序';
$lang->switchDisplay   = '切换显示';
$lang->switchTo        = '切换到';
$lang->expand          = '展开全部';
$lang->collapse        = '收起';
$lang->saveSuccess     = '保存成功';
$lang->importSuccess   = '导入成功';
$lang->fail            = '失败';
$lang->success         = '成功';
$lang->addFiles        = '上传了附件 ';
$lang->files           = '附件 ';
$lang->pasteText       = '多项录入';
$lang->uploadImages    = '多图上传 ';
$lang->timeout         = '连接超时，请检查网络环境，或重试！';
$lang->repairTable     = '数据库表可能损坏，请用phpmyadmin或myisamchk检查修复。';
$lang->duplicate       = '已有相同标题的%s';
$lang->ipLimited       = "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>抱歉，管理员限制当前IP登录，请联系管理员解除限制。</body></html>";
$lang->unfold          = '+';
$lang->fold            = '-';
$lang->homepage        = '设为模块首页';
$lang->noviceTutorial  = '新手教程';
$lang->changeLog       = '修改日志';
$lang->manual          = '手册';
$lang->customMenu      = '自定义导航';
$lang->customField     = '自定义表单项';
$lang->lineNumber      = '行号';
$lang->tutorialConfirm = '检测到你尚未退出新手教程模式，是否现在退出？';
$lang->levelExceeded   = '层级已超过显示范围，更多信息请前往网页端查看或者是通过搜索方式查看。';
$lang->noticeOkFile    = '为了安全起见，系统需要确认您的管理员身份。\n 请登录服务器，创建%s文件。\n 注意：\n 1. 文件内容为空。\n 2. 如果之前文件存在，删除之后重新创建。';

$lang->serviceAgreement = "服务协议";
$lang->privacyPolicy    = "隐私政策";
$lang->needAgreePrivacy = "请先阅读《服务协议》和《隐私政策》";
$lang->iAgreedPrivacy   = "我已阅读并同意";

$lang->preShortcutKey    = '[快捷键:←]';
$lang->nextShortcutKey   = '[快捷键:→]';
$lang->backShortcutKey   = '[快捷键:Alt+↑]';
$lang->shortcutOperation = '快捷操作';

$lang->select        = '选择';
$lang->selectAll     = '全选';
$lang->selectReverse = '反选';
$lang->loading       = '稍候...';
$lang->notFound      = '抱歉，您访问的对象不存在！';
$lang->notPage       = '抱歉，您访问的功能正在开发中！';
$lang->showAll       = '[[全部显示]]';
$lang->selectedItems = '已选择 <strong>{0}</strong> 项';

$lang->future      = '未来';
$lang->year        = '年';
$lang->workingHour = '工时';

$lang->idAB         = 'ID';
$lang->priAB        = 'P';
$lang->statusAB     = '状态';
$lang->openedByAB   = '创建';
$lang->assignedToAB = '指派';
$lang->typeAB       = '类型';
$lang->nameAB       = '名称';

$lang->pri     = '优先级';
$lang->delayed = '已延期';

$lang->common->common     = '公有模块';
$lang->my->common         = '仪表盘';
$lang->space->common      = '服务管理';
$lang->store->common      = '应用市场';
$lang->solution->common   = '解决方案';
$lang->navigation->common = '导航';
$lang->admin->common      = '后台管理';
$lang->system->common     = '后台管理';
$lang->user->common       = '用户';
$lang->custom->common     = '自定义';
$lang->company->common    = '公司';
$lang->upgrade->common    = '升级';

$lang->my->shortCommon       = '仪表盘';
$lang->space->shortCommon    = '服务管理';
$lang->store->shortCommon    = '应用市场';
$lang->solution->shortCommon  = '解决方案';
$lang->store->cloudStore     = '公共市场';
$lang->store->localStore     = '本地市场';

$lang->dashboard       = '仪表盘';
$lang->contribute      = '贡献';
$lang->dynamic         = '动态';
$lang->contact         = '联系人';
$lang->whitelist       = '白名单';
$lang->roadmap         = '路线图';
$lang->track           = '矩阵';
$lang->settings        = '设置';
$lang->overview        = '概况';
$lang->module          = '模块';
$lang->priv            = '权限';
$lang->other           = '其他';
$lang->estimation      = '估算';
$lang->measure         = '度量';
$lang->treeView        = '树状图';
$lang->groupView       = '分组视图';
$lang->executionKanban = '看板';
$lang->burn            = '燃尽图';
$lang->view            = '视图';
$lang->intro           = '介绍';
$lang->indexPage       = '首页';
$lang->model           = '模型';
$lang->redev           = '二次开发';
$lang->browser         = '浏览器';
$lang->db              = '数据库';
$lang->editor          = '编辑器';
$lang->timezone        = '时区';
$lang->security        = '安全';
$lang->calendar        = '日程';

$lang->admin->system     = '系统';

$lang->searchTips = '';
$lang->searchAB   = '搜索';

/* 查询中可以选择的对象列表。*/
$lang->searchObjects['all']     = '全部';
$lang->searchObjects['release'] = '发布';
$lang->searchObjects['user']    = '用户';
$lang->searchTips               = '编号(ctrl+g)';

/* 导入支持的编码格式。*/
$lang->importEncodeList['gbk']   = 'GBK';
$lang->importEncodeList['big5']  = 'BIG5';
$lang->importEncodeList['utf-8'] = 'UTF-8';

/* 导出文件的类型列表。*/
$lang->exportFileTypeList['csv']  = 'csv';
$lang->exportFileTypeList['xml']  = 'xml';
$lang->exportFileTypeList['html'] = 'html';

$lang->exportTypeList['all']      = '全部记录';
$lang->exportTypeList['selected'] = '选中记录';

$lang->visionList = array();
$lang->visionList['rnd']  = '研发综合界面';
$lang->visionList['lite'] = '迅捷界面';

$lang->createObjects['todo']   = '待办';
$lang->createObjects['effort'] = '日志';

/* 语言 */
$lang->lang    = 'Language';
$lang->setLang = '语言设置';

/* 风格列表。*/
$lang->theme                = '主题';
$lang->themes['qucheng']    = '渠成绿';
//$lang->themes['cyan']       = '渠成青';
//$lang->themes['default']    = '禅道蓝';
//$lang->themes['blue']       = '青春蓝';
//$lang->themes['green']      = '叶兰绿';
//$lang->themes['red']        = '赤诚红';
//$lang->themes['purple']     = '玉烟紫';
//$lang->themes['pink']       = '芙蕖粉';
//$lang->themes['blackberry'] = '露莓黑';
//$lang->themes['classic']    = '经典蓝';

/* 错误提示信息。*/
$lang->error                  = new stdclass();
$lang->error->error           = '错误';
$lang->error->companyNotFound = "您访问的域名 %s 没有对应的公司。";
$lang->error->length          = array("『%s』长度错误，应当为『%s』", "『%s』长度应当不超过『%s』，且大于『%s』。");
$lang->error->reg             = "『%s』不符合格式，应当为:『%s』。";
$lang->error->unique          = "『%s』已经有『%s』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。";
$lang->error->gt              = "『%s』应当大于『%s』。";
$lang->error->ge              = "『%s』应当不小于『%s』。";
$lang->error->lt              = "『%s』应当小于『%s』。";
$lang->error->le              = "『%s』应当不大于『%s』。";
$lang->error->notempty        = "『%s』不能为空。";
$lang->error->empty           = "『%s』必须为空。";
$lang->error->equal           = "『%s』必须为『%s』。";
$lang->error->in              = "『%s』值无效。";
$lang->error->int             = array("『%s』应当是数字。", "『%s』应当介于『%s-%s』之间。");
$lang->error->float           = "『%s』应当是数字，可以是小数。";
$lang->error->email           = "『%s』应当为合法的EMAIL。";
$lang->error->phone           = "『%s』应当为合法的电话号码。";
$lang->error->mobile          = "『%s』应当为合法的手机号码。";
$lang->error->URL             = "『%s』应当为合法的URL。";
$lang->error->date            = "『%s』应当为合法的日期。";
$lang->error->datetime        = "『%s』应当为合法的日期。";
$lang->error->code            = "『%s』应当为字母或数字的组合。";
$lang->error->account         = "『%s』只能是字母、数字或下划线的组合三位以上。";
$lang->error->passwordsame    = "两次密码应该相同。";
$lang->error->passwordrule    = "密码应该符合规则，长度至少为六位。";
$lang->error->accessDenied    = '您没有访问权限';
$lang->error->pasteImg        = '您的浏览器不支持粘贴图片！';
$lang->error->noData          = '没有数据';
$lang->error->editedByOther   = '该记录可能已经被改动。请刷新页面重新编辑！';
$lang->error->tutorialData    = '新手模式下不会插入数据，请退出新手模式操作';
$lang->error->noCurlExt       = '服务器未安装Curl模块。';
$lang->error->fail            = '失败';
$lang->error->success         = '成功';
$lang->error->httpServerError = '服务器错误';

/* 分页信息。*/
$lang->pager               = new stdclass();
$lang->pager->noRecord     = "暂时没有记录";
$lang->pager->digest       = "共 <strong>%s</strong> 条记录，%s <strong>%s/%s</strong> &nbsp; ";
$lang->pager->recPerPage   = "每页 <strong>%s</strong> 条";
$lang->pager->first        = "<i class='icon-step-backward' title='首页'></i>";
$lang->pager->pre          = "<i class='icon-play icon-flip-horizontal' title='上一页'></i>";
$lang->pager->next         = "<i class='icon-play' title='下一页'></i>";
$lang->pager->last         = "<i class='icon-step-forward' title='末页'></i>";
$lang->pager->locate       = "GO!";
$lang->pager->previousPage = "上一页";
$lang->pager->nextPage     = "下一页";
$lang->pager->summery      = "第 <strong>%s-%s</strong> 项，共 <strong>%s</strong> 项";
$lang->pager->pageOfText   = '第 {0} 页';
$lang->pager->firstPage    = '第一页';
$lang->pager->lastPage     = '最后一页';
$lang->pager->goto         = '跳转';
$lang->pager->pageOf       = '第 <strong>{page}</strong> 页';
$lang->pager->totalPage    = '共 <strong>{totalPage}</strong> 页';
$lang->pager->totalCount   = '共 <strong>{recTotal}</strong> 项';
$lang->pager->pageSize     = '每页 <strong>{recPerPage}</strong> 项';
$lang->pager->itemsRange   = '第 <strong>{start}</strong> ~ <strong>{end}</strong> 项';
$lang->pager->pageOfTotal  = '第 <strong>{page}</strong>/<strong>{totalPage}</strong> 页';

$lang->colorPicker           = new stdclass();
$lang->colorPicker->errorTip = '不是有效的颜色值';

$lang->downNotify     = "下载桌面提醒";
$lang->clientName     = "客户端";
$lang->downloadClient = "下载客户端";
$lang->clientHelp     = "客户端使用说明";
$lang->clientHelpLink = "http://www.qucheng.com/book/quchenghelp/302.html#2";
$lang->website        = "https://www.qucheng.com";

$lang->suhosinInfo     = "警告：数据太多，请在php.ini中修改<font color=red>sohusin.post.max_vars</font>和<font color=red>sohusin.request.max_vars</font>（大于%s的数）。 保存并重新启动apache或php-fpm，否则会造成部分数据无法保存。";
$lang->maxVarsInfo     = "警告：数据太多，请在php.ini中修改<font color=red>max_input_vars</font>（大于%s的数）。 保存并重新启动apache或php-fpm，否则会造成部分数据无法保存。";
$lang->pasteTextInfo   = "粘贴文本到文本域中，每行文字作为一条数据的标题。";
$lang->noticeImport    = "导入数据中，含有已经存在系统的数据，请确认这些数据要覆盖或者全新插入。";
$lang->importConfirm   = "导入确认";
$lang->importAndCover  = "覆盖";
$lang->importAndInsert = "全新插入";

$lang->noResultsMatch    = "没有匹配结果";
$lang->searchMore        = "搜索此关键字的更多结果：";
$lang->chooseUsersToMail = "选择要发信通知的用户...";
$lang->noticePasteImg    = "可以在编辑器直接贴图。";
$lang->pasteImgFail      = "贴图失败，请稍后重试。";
$lang->pasteImgUploading = "正在上传图片，请稍后...";

/* 时间格式设置。*/
if(!defined('DT_DATETIME1'))  define('DT_DATETIME1', 'Y-m-d H:i:s');
if(!defined('DT_DATETIME2'))  define('DT_DATETIME2', 'y-m-d H:i');
if(!defined('DT_MONTHTIME1')) define('DT_MONTHTIME1', 'n/d H:i');
if(!defined('DT_MONTHTIME2')) define('DT_MONTHTIME2', 'n月d日 H:i');
if(!defined('DT_DATE1'))      define('DT_DATE1', 'Y-m-d');
if(!defined('DT_DATE2'))      define('DT_DATE2', 'Ymd');
if(!defined('DT_DATE3'))      define('DT_DATE3', 'Y年m月d日');
if(!defined('DT_DATE4'))      define('DT_DATE4', 'n月j日');
if(!defined('DT_DATE5'))      define('DT_DATE5', 'j/n');
if(!defined('DT_TIME1'))      define('DT_TIME1', 'H:i:s');
if(!defined('DT_TIME2'))      define('DT_TIME2', 'H:i');
if(!defined('LONG_TIME'))     define('LONG_TIME', '2059-12-31');
if(!defined('BRANCH_MAIN'))   define('BRANCH_MAIN', '0');

$lang->year    = '年';
$lang->month   = '月';
$lang->day     = '天';
$lang->hour    = '小时';
$lang->minute  = '分';
$lang->second  = '秒';

/* datepicker 时间*/
$lang->datepicker = new stdclass();

$lang->datepicker->dpText                   = new stdclass();
$lang->datepicker->dpText->TEXT_OR          = '或 ';
$lang->datepicker->dpText->TEXT_PREV_YEAR   = '去年';
$lang->datepicker->dpText->TEXT_PREV_MONTH  = '上月';
$lang->datepicker->dpText->TEXT_PREV_WEEK   = '上周';
$lang->datepicker->dpText->TEXT_YESTERDAY   = '昨天';
$lang->datepicker->dpText->TEXT_THIS_MONTH  = '本月';
$lang->datepicker->dpText->TEXT_THIS_WEEK   = '本周';
$lang->datepicker->dpText->TEXT_TODAY       = '今天';
$lang->datepicker->dpText->TEXT_NEXT_YEAR   = '明年';
$lang->datepicker->dpText->TEXT_NEXT_MONTH  = '下月';
$lang->datepicker->dpText->TEXT_CLOSE       = '关闭';
$lang->datepicker->dpText->TEXT_DATE        = '选择时间段';
$lang->datepicker->dpText->TEXT_CHOOSE_DATE = '选择日期';

$lang->datepicker->dayNames     = array('星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
$lang->datepicker->abbrDayNames = array('日', '一', '二', '三', '四', '五', '六');
$lang->datepicker->monthNames   = array('一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月');

include(dirname(__FILE__) . '/menu.php');
