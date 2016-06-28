<?php
namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class TestController extends Controller
{
   
    /**
     * Lists all Article models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
       $result =self::pdf2png('uploads/upload1.pdf','uploads');
       echo(serialize($result));exit;
    }
    /**
     * @$PDF pdf文件
     * @$Path
     * @return mixed
     */
    
    public function pdf2png($PDF,$Path){
    if(!extension_loaded('imagick')){
        return false;
    }
    if(!file_exists($PDF)){
        echo'缺少pdf文件';
        return false;
    }
    $IM =new \imagick();
    $IM->setResolution(120,120);
    $IM->setCompressionQuality(100);
    $IM->readImage($PDF);
    foreach($IM as $Key => $Var){
        $Var->setImageFormat('png');
        $Filename = $Path.'/'.time().'.png';
        if($Var->writeImage($Filename)==true){
            $Return[]= $Filename;
        }
    }
    $Return = array_unique($Return);
    sort($Return);
    return $Return;
 }

}
