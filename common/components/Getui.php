<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 2016/4/11
 * Desc:个推工具类
 * Time: 10:51
 */

namespace common\components;
use Yii;
use common\models\Member;
require_once('../../common/components/getui/' . 'IGt.Push.php');
require_once('../../common/components/getui/igetui/IGt.AppMessage.php');
require_once('../../common/components/getui/igetui/IGt.APNPayload.php');
require_once('../../common/components/getui/igetui/template/IGt.BaseTemplate.php');
require_once('../../common/components/getui/IGt.Batch.php');
require_once('../../common/components/getui/igetui/utils/AppConditions.php');
//http的域名
define('HOST',Yii::$app->params['getui']['host']);            
define('APPKEY',Yii::$app->params['getui']['appKey']);
define('APPID',Yii::$app->params['getui']['appId']);
define('MASTERSECRET',Yii::$app->params['getui']['masterSecret']);
class Getui {

    /**
     * 消息推送单推通用接口
     * @author by lxhui
     * @param $title 标题
     * @param $content 内容
     * @param $uids 推送对象uid集合 array(2,1,4);
     * @version [2010-06-03]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
     public function pushSingle($title,$content,$uids){   
         $model =Member::find()->where(['id'=>$uids])->asArray()->all();
         if($model)
         {
             foreach($model as $val)
             {
                if($val['devicetoken']) //ios推送
                    $this->pushAPN($val['devicetoken']);
                else // 安卓推送
                    $this->pushMessageToSingle($title,$content,$val['clientid']);// 单推 androd                 
             }
             
         }   
     }
    public function pushAPN($devicetoken){       
        //APN简单推送
        $igt = new \IGeTui(HOST,APPKEY,MASTERSECRET);
        $template = new \IGtAPNTemplate();
        $apn = new \IGtAPNPayload();
        $alertmsg=new \SimpleAlertMsg();
        $alertmsg->alertMsg="";
        $apn->alertMsg=$alertmsg;
    //        $apn->badge=2;
        $apn->sound="";
        $apn->add_customMsg("payload","payload");
        $apn->contentAvailable=1;
        $apn->category="ACTIONABLE";
        $template->set_apnInfo($apn);
        $message = new \IGtSingleMessage();
        $message->set_data($template);
        $ret = $igt->pushAPNMessageToSingle(APPID, $devicetoken, $message);
        return $ret;
    }

    public function pushAPNL(){
        //APN高级推送
        $igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
        $template = new IGtAPNTemplate();
        $apn = new IGtAPNPayload();
        $apn->add_customMsg("payload","payload");

        $template->set_apnInfo($apn);
        $message = new IGtSingleMessage();

        //多个用户推送接口
        putenv("needDetails=true");
        $listmessage = new IGtListMessage();
        $listmessage->set_data($template);
        $contentId = $igt->getAPNContentId(APPID, $listmessage);
        //$deviceTokenList = array("3337de7aa297065657c087a041d28b3c90c9ed51bdc37c58e8d13ced523f5f5f");
        $deviceTokenList = array(DEVICETOKEN);
        $ret = $igt->pushAPNMessageToList(APPID, $contentId, $deviceTokenList);
        var_dump($ret);
    }

    //用户状态查询
    public function getUserStatus() {
        $igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
        $rep = $igt->getClientIdStatus(APPID,CID);
        var_dump($rep);
        echo ("<br><br>");
    }

    //推送任务停止
    public function stoptask(){

        $igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
        $igt->stop("OSA-1127_QYZyBzTPWz5ioFAixENzs3");
    }

    //通过服务端设置ClientId的标签
    public function setTag(){
        $igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
        $tagList = array('','中文','English');
        $rep = $igt->setClientTag(APPID,CID,$tagList);
        var_dump($rep);
        echo ("<br><br>");
    }

    public function getUserTags() {
        $igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
        $rep = $igt->getUserTags(APPID,CID);
        //$rep.connect();
        var_dump($rep);
        echo ("<br><br>");
    }

    //
    //服务端推送接口，支持三个接口推送
    //1.PushMessageToSingle接口：支持对单个用户进行推送
    //2.PushMessageToList接口：支持对多个用户进行推送，建议为50个用户
    //3.pushMessageToApp接口：对单个应用下的所有用户进行推送，可根据省份，标签，机型过滤推送
    //

    //单推接口案例
    public function pushMessageToSingle($title,$content,$clientid){
        //$igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
        $igt = new \IGeTui(NULL,APPKEY,MASTERSECRET,false);

        //消息模版：
        // 1.TransmissionTemplate:透传功能模板
        // 2.LinkTemplate:通知打开链接功能模板
        // 3.NotificationTemplate：通知透传功能模板
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板

        // $template = IGtNotyPopLoadTemplateDemo();
        // $template = IGtLinkTemplateDemo();
        // $template = IGtNotificationTemplateDemo();
        $template = self::IGtTransmissionTemplateDemo($title,$content);

        //个推信息体
        $message = new \IGtSingleMessage();

        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
    //	$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
        //接收方
        $target = new \IGtTarget();
        $target->set_appId(APPID);
        $target->set_clientId($clientid);
    //    $target->set_alias(Alias);

        try {
            $rep = $igt->pushMessageToSingle($message, $target);
            //var_dump($rep);
            return $rep;
        }catch(RequestException $e){
            $requstId =e.getRequestId();
            $rep = $igt->pushMessageToSingle($message, $target,$requstId);
            //var_dump($rep);
            return $rep;
        }

    }

    public function pushMessageToSingleBatch()
    {
        putenv("gexin_pushSingleBatch_needAsync=false");

        $igt = new IGeTui(HOST, APPKEY, MASTERSECRET);
        $batch = new IGtBatch(APPKEY, $igt);
        $batch->setApiUrl(HOST);
        //$igt->connect();
        //消息模版：
        // 1.TransmissionTemplate:透传功能模板
        // 2.LinkTemplate:通知打开链接功能模板
        // 3.NotificationTemplate：通知透传功能模板
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板

    //    $template = IGtNotyPopLoadTemplateDemo();
        //$template = IGtLinkTemplateDemo();
        //$template = IGtNotificationTemplateDemo();
        $template = IGtTransmissionTemplateDemo();

        //个推信息体
        $message = new IGtSingleMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(12 * 1000 * 3600);//离线时间
        $message->set_data($template);//设置推送消息类型
    //    $message->set_PushNetWorkType(1);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送

        $target = new IGtTarget();
        $target->set_appId(APPID);
        $target->set_clientId(CID);
        $batch->add($message, $target);
        try {

            $rep = $batch->submit();
            var_dump($rep);
            echo("<br><br>");
        }catch(Exception $e){
            $rep=$batch->retry();
            var_dump($rep);
            echo ("<br><br>");
        }
    }
    /**
     * 多推接口案例
     * @param $title 消息标题 $content 内容
     * @author by lxhui
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     * @return array
     */
    public function pushMessageToList($title,$content,$clientid)
    {
        putenv("gexin_pushList_needDetails=true");
        putenv("gexin_pushList_needAsync=true");

        $igt = new \IGeTui(HOST, APPKEY, MASTERSECRET);
        //消息模版：
        // 1.TransmissionTemplate:透传功能模板
        // 2.LinkTemplate:通知打开链接功能模板
        // 3.NotificationTemplate：通知透传功能模板
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板


        //$template = IGtNotyPopLoadTemplateDemo();
        //$template = IGtLinkTemplateDemo();
        //$template = IGtNotificationTemplateDemo();
        $template = self::IGtTransmissionTemplateDemo($title,$content);
        //个推信息体
        $message = new \IGtListMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600 * 12 * 1000);//离线时间
        $message->set_data($template);//设置推送消息类型
    //    $message->set_PushNetWorkType(1);	//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
    //    $contentId = $igt->getContentId($message);
        $contentId = $igt->getContentId($message,"toList任务别名功能");	//根据TaskId设置组名，支持下划线，中文，英文，数字

        //接收方1
        $target1 = new \IGtTarget();
        $target1->set_appId(APPID);
        $target1->set_clientId($clientid);
    //    $target1->set_alias(Alias);

        $targetList[] = $target1;

        $rep = $igt->pushMessageToList($contentId, $targetList);

        var_dump($rep);

        echo ("<br><br>");

    }

    //群推接口案例

    public function pushMessageToApp($title, $content){
        $igt = new \IGeTui(HOST,APPKEY,MASTERSECRET);
        $template = $this->IGtTransmissionTemplateDemo($title, $content);
        //$template = $this->IGtNotificationTemplateDemo();
        //$template = IGtLinkTemplateDemo();
        //个推信息体
        //基于应用消息体
        $message = new \IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);
        $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
        $appIdList=array(APPID);
        
        //用户属性
        //$age = array("0000", "0010");
        //$cdt = new AppConditions();
       // $cdt->addCondition(AppConditions::PHONE_TYPE, $phoneTypeList);
       // $cdt->addCondition(AppConditions::REGION, $provinceList);
        //$cdt->addCondition(AppConditions::TAG, $tagList);
        //$cdt->addCondition("age", $age);

        $message->set_appIdList($appIdList);
        //$message->set_conditions($cdt->getCondition());
        $rep = $igt->pushMessageToApp($message,"任务组名");
        print_r($rep);
        echo ("<br><br>");
    }

    //所有推送接口均支持四个消息模板，依次为通知弹框下载模板，通知链接模板，通知透传模板，透传模板
    //注：IOS离线推送需通过APN进行转发，需填写pushInfo字段，目前仅不支持通知弹框下载功能

    public function IGtNotyPopLoadTemplateDemo(){
        $template =  new IGtNotyPopLoadTemplate();

        $template ->set_appId(APPID);//应用appid
        $template ->set_appkey(APPKEY);//应用appkey
        //通知栏
        $template ->set_notyTitle("个推");//通知栏标题
        $template ->set_notyContent("个推最新版点击下载");//通知栏内容
        $template ->set_notyIcon("");//通知栏logo
        $template ->set_isBelled(true);//是否响铃
        $template ->set_isVibrationed(true);//是否震动
        $template ->set_isCleared(true);//通知栏是否可清除
        //弹框
        $template ->set_popTitle("弹框标题");//弹框标题
        $template ->set_popContent("弹框内容");//弹框内容
        $template ->set_popImage("");//弹框图片
        $template ->set_popButton1("下载");//左键
        $template ->set_popButton2("取消");//右键
        //下载
        $template ->set_loadIcon("");//弹框图片
        $template ->set_loadTitle("地震速报下载");
        $template ->set_loadUrl("http://dizhensubao.igexin.com/dl/com.ceic.apk");
        $template ->set_isAutoInstall(false);
        $template ->set_isActived(true);
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息

        return $template;
    }

    public function IGtLinkTemplateDemo(){
        $template =  new \IGtLinkTemplate();
        $template ->set_appId(APPID);//应用appid
        $template ->set_appkey(APPKEY);//应用appkey
        $template ->set_title("您有新的评论");//通知栏标题
        $template ->set_text("啊三回复了你");//通知栏内容
        $template ->set_logo("");//通知栏logo
        $template ->set_isRing(true);//是否响铃
        $template ->set_isVibrate(true);//是否震动
        $template ->set_isClearable(true);//通知栏是否可清除
        $template ->set_url("http://www.baidu..com/");//打开连接地址
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //iOS推送需要设置的pushInfo字段
    //        $apn = new IGtAPNPayload();
    //        $apn->alertMsg = "alertMsg";
    //        $apn->badge = 11;
    //        $apn->actionLocKey = "启动";
    //    //        $apn->category = "ACTIONABLE";
    //    //        $apn->contentAvailable = 1;
    //        $apn->locKey = "通知栏内容";
    //        $apn->title = "通知栏标题";
    //        $apn->titleLocArgs = array("titleLocArgs");
    //        $apn->titleLocKey = "通知栏标题";
    //        $apn->body = "body";
    //        $apn->customMsg = array("payload"=>"payload");
    //        $apn->launchImage = "launchImage";
    //        $apn->locArgs = array("locArgs");
    //
    //        $apn->sound=("test1.wav");;
    //        $template->set_apnInfo($apn);
        return $template;
    }

    public function IGtNotificationTemplateDemo(){
        $template =  new \IGtNotificationTemplate();
        $template->set_appId(APPID);//应用appid
        $template->set_appkey(APPKEY);//应用appkey
        $template->set_transmissionType(1);//透传消息类型
        $template->set_transmissionContent("端午节快乐哈哈哈");//透传内容
        $template->set_title("节日快乐");//通知栏标题
        $template->set_text("这里是光谷广场");//通知栏内容
        $template->set_logo("http://wwww.igetui.com/logo.png");//通知栏logo
        $template->set_isRing(true);//是否响铃
        $template->set_isVibrate(true);//是否震动
        $template->set_isClearable(true);//通知栏是否可清除
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //iOS推送需要设置的pushInfo字段
    //        $apn = new IGtAPNPayload();
    //        $apn->alertMsg = "alertMsg";
    //        $apn->badge = 11;
    //        $apn->actionLocKey = "启动";
    //    //        $apn->category = "ACTIONABLE";
    //    //        $apn->contentAvailable = 1;
    //        $apn->locKey = "通知栏内容";
    //        $apn->title = "通知栏标题";
    //        $apn->titleLocArgs = array("titleLocArgs");
    //        $apn->titleLocKey = "通知栏标题";
    //        $apn->body = "body";
    //        $apn->customMsg = array("payload"=>"payload");
    //        $apn->launchImage = "launchImage";
    //        $apn->locArgs = array("locArgs");
    //
    //        $apn->sound=("test1.wav");;
    //        $template->set_apnInfo($apn);
        return $template;
    }
    /**
     * 透传消息模版
     * @param $title 消息标题 $content 内容
     * @author by lxhui
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     * @return array
     */
    public function IGtTransmissionTemplateDemo($title,$content){
        $template =  new \IGtTransmissionTemplate();
        $template->set_appId(APPID);//应用appid
        $template->set_appkey(APPKEY);//应用appkey
        $template->set_transmissionType(1);//透传消息类型
        $template->set_transmissionContent($content);//透传内容
        //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
        //APN高级推送
        $apn = new \IGtAPNPayload();
        $alertmsg=new \DictionaryAlertMsg();
        $alertmsg->body="body";
        $alertmsg->actionLocKey="ActionLockey";
        $alertmsg->locKey="LocKey";
        $alertmsg->locArgs=array("locargs");
        $alertmsg->launchImage="launchimage";
    //        IOS8.2 支持
        $alertmsg->title=$title;
        $alertmsg->titleLocKey="TitleLocKey";
        $alertmsg->titleLocArgs=array("TitleLocArg");

        $apn->alertMsg=$alertmsg;
        $apn->badge=7;
        $apn->sound="";
        $apn->add_customMsg("payload","阿波罗度上市");
        $apn->contentAvailable=1;
        $apn->category="ACTIONABLE";
        $template->set_apnInfo($apn);

        return $template;
    }
   

}