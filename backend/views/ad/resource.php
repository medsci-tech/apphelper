<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/13
 * Time: 10:45
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(['action' => ['ad/create'], 'method' => 'post', 'id' => 'tableForm']); ?>
    <div style="padding: 20px;background-color: white">
        <label class="control-label">资源类型：
            <label class="checkbox-inline">
                <input type="radio" name="optionsRadiosinline" id="optionsRadios3"
                       value="option1" checked> 培训
            </label>
            <label class="checkbox-inline">
                <input type="radio" name="optionsRadiosinline" id="optionsRadios4"
                       value="option2"> 试卷
            </label>
        </label>
        <label class="control-label">资源名称：
            <div class="input-group">
                <input type="text" class="form-control">
                                            <span class="input-group-btn"> <button type="button" class="btn btn-primary">搜索
                                                </button>
                                            </span>
            </div>
        </label>
        <table
            data-toggle="table"
            data-height="350">
            <thead>
            <tr>
                <th class="col-md-9">资源名</th>
                <th class="col-md-3">资源类型</th>
            </tr>
            </thead>
            </tbody>
            <tr>
                <td>ID</td>
                <td>培训wy课程</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>培训课程</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>培训课程</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>培训课程</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>培训课程</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>培训课程</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>培训课程</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>培训课程</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>培训课程</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>培训课程</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>培训课程</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>培训课程</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="attr_from" id="">
        <button type="button" class="btn btn-white">关闭</button>
        <button type="submit" class="btn btn-primary">保存</button>
    </div>


