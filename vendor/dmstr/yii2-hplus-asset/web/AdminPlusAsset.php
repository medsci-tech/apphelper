<?php
namespace dmstr\web;

use yii\base\Exception;
use yii\web\AssetBundle;

/**
 * H+ AssetBundle
 * @since v4.0.1
 * @author lxhui
 */
class AdminPlusAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/hplus';
    public $css = [
        'css/bootstrap.min14ed.css?v=3.3.6',
        'css/plugins/chosen/chosen.css',
        'css/plugins/sweetalert/sweetalert.css',
        'css/plugins/toastr/toastr.min.css',
        'css/font-awesome.min93e3.css?v=4.4.0',
        'css/animate.min.css',
        'css/style.min862f.css?v=4.1.0',
        '/css/bootstrap-table.min.css',
        'css/plugins/plyr/plyr.css',
    ];
    public $js = [
        'js/bootstrap.min.js?v=3.3.6',
        '/js/plugins/treeview/bootstrap-treeview.js',
        'js/plugins/metisMenu/jquery.metisMenu.js',
        'js/plugins/slimscroll/jquery.slimscroll.min.js',
        'js/plugins/layer/layer.min.js',
        'js/hplus.min.js?v=4.1.0',
        'js/plugins/layer/laydate/laydate.js',
        'js/contabs.min.js',
        'js/plugins/pace/pace.min.js',
        'js/plugins/sweetalert/sweetalert.min.js',
        '/js/bootstrap-table.min.js',
        '/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js',
        '/js/jquery-common-mime-fun.js',
        'js/plugins/toastr/toastr.min.js',
        'js/plugins/webuploader/jquery.ajaxupload.js',
        'js/plugins/chosen/chosen.jquery.js',
        'js/plugins/plyr/plyr.js',
    ];
    public $depends = [
/*        'rmrevin\yii\fontawesome\AssetBundle',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',*/
    ];

    /**
     * @var string|bool Choose skin color, eg. `'skin-blue'` or set `false` to disable skin loading
     * @see https://almsaeedstudio.com/themes/AdminLTE/documentation/index.html#layout
     */
    public $skin = '_all-skins';

    /**
     * @inheritdoc
     */
    public function init()
    {
        // Append skin color file if specified
        if ($this->skin) {
            if (('_all-skins' !== $this->skin) && (strpos($this->skin, 'skin-') !== 0)) {
                throw new Exception('Invalid skin specified');
            }

            $this->css[] = sprintf('css/skins/%s.min.css', $this->skin);
        }

        parent::init();
    }
}
