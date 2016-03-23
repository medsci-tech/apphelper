这里是单位列表页
<?
echo $this->render('/region/index', [
    'model' => new \common\models\Region,
    'foo' => 1,
    'bar' => 2,
]);
?>