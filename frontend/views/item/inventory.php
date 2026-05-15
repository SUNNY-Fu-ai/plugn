<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\touchspin\TouchSpin;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant->restaurant_uuid;

$this->title = 'Inventory';
$this->params['breadcrumbs'][] = $this->title;

$js = "
$(function () {
  $('.summary').insertAfter('.top');
});


    $(document).on('wheel', 'input[type=number]', function (e) {
        $(this).blur();
    });

";
$this->registerJs($js);
?>



<section id="data-list-view" class="data-list-view-header">

  <?php if ($count > 0) { ?>

    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'label' => 'Image',
                    'format' => 'raw',
                    'value' => function ($item) {

                            $itemItmage = $item->itemImage;

                            if ($itemItmage)
                              return  Html::img("https://res.cloudinary.com/plugn/image/upload/c_scale,h_60,w_60/restaurants/" . $item->restaurant->restaurant_uuid . "/items/" . $itemItmage->product_file_name, ['style' => 'border-radius: 3px;margin-right: 20px;']);
                            else
                                return Html::img("https://res.cloudinary.com/plugn/image/upload/c_scale,h_60,w_60/no-image.jpg", ['style' => 'border-radius: 3px;margin-right: 20px;']);
                    },
                ],
                [
                    'label' => 'Item name',
                    'format' => 'text',
                    'attribute' => 'item_name',
                ],
                [
                    'format' => 'text',
                    'attribute' => 'sku'
                ],
                [
                    'label' => 'Available',
                    'format' => 'raw',
                    'attribute' => 'stock_qty'
                ],
                [
                    'header' => 'Edit quantity available',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {update}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                          return $this->render('_update-inventory', ['model' => $model]);
                        },
                    ],
                ],
            ],
            //'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table dataTable data-list-view'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

  <?php } else {?>


    <div class="card">
      <div style="padding: 70px 0; text-align: center;">

        <div>
          <img src="https://res.cloudinary.com/plugn/image/upload/v1607881378/emptystate-inventory.svg" width="226" alt="" />
        </div>

        <h3>
          Add and manage your products
        </h3>

        <p>
          When you enable inventory tracking on your products, you can view and adjust their inventory counts here.
        </p>
        <?= Html::a('Go to items', ['item/index', 'storeUuid' => $restaurant->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
      </div>
    </div>


  <?php } ?>

</section>
<!-- Data list view end -->
