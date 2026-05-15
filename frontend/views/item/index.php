<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Item;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant->restaurant_uuid;

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;

$js = "
$(function () {
  $('.summary').insertAfter('.top');


  $('table.data-list-view.dataTable tbody td').css('padding', '10px');

  $('#restaurant-export_sold_items_data_in_specific_date_range').attr('autocomplete','off');
  $('#restaurant-export_sold_items_data_in_specific_date_range').attr('style', '  padding-right: 2rem !important; padding-left: 3rem !important; ');

  $('#restaurant-export_sold_items_data_in_specific_date_range').change(function(e){
    if(e.target.value){
      $('#export-to-excel-btn').attr('disabled',false);
    }else {
      $('#export-to-excel-btn').attr('disabled',true);
    }
});

});



";
$this->registerJs($js);
?>



<section id="data-list-view" class="data-list-view-header">

  <?php if ($count > 0) { ?>

    <!-- Data list view starts -->
    <div class="action-btns">
        <div class="btn-dropdown mr-1 mb-1">
            <div class="btn-group dropdown actions-dropodown">
                <?= Html::a('Add item', ['create', 'storeUuid' => $restaurant->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <div style="padding-left:14px">
        <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'storeUuid' => $restaurant->restaurant_uuid], ['class' => 'btn btn-outline-primary pull-right', 'style' => '    padding: 0.85rem 1.7rem;']) ?>
    </div>


    <!-- DataTable starts -->
    <div class="table-responsive dataTables_wrapper">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'rowOptions' => function($model) {
                $url = Url::to(['item/update', 'id' => $model->item_uuid, 'storeUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
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
                        'attribute' => 'item_name',
                           'label' => 'Item name',
                           'format' => 'text'
                ],
                [
                    'attribute' => 'sku',
                    'label' => 'SKU',
                    'format' => 'text'
                ],
                [
                    'label' => 'Category name',
                    'value' => function ($data) {
                        $categoryName = '';

                        foreach ($data->category as $key => $category) {
                            if ($key == 0) {
                                $categoryName .= $category['title'];
                            } else {
                                $categoryName .= ', ' . $category['title'];
                            }
                        }

                        if ($categoryName == '')
                            return '(not set)';

                        return $categoryName;
                    },
                    'format' => 'text'
                ],

                'unit_sold',
                'sort_number',

                [
                    'attribute' => 'item_price',
                    "format" => "raw",
                    "value" => function($model) use ($restaurant) {
                        if ($model->extraOptions)
                          return 'Price on selection';
                        else
                        return Yii::$app->formatter->asCurrency($model->item_price, $restaurant->currency->code, [
                            \NumberFormatter::MAX_FRACTION_DIGITS => $restaurant->currency->decimal_place
                        ]);
                    }
                ],
                [
                    'attribute' => 'item_status',
                    "format" => "raw",
                    "filter" => [
                        Item::ITEM_STATUS_UNPUBLISH => 'Unpublished',
                        Item::ITEM_STATUS_PUBLISH => 'Published'
                    ],
                    "value" => function($model) {
                        if ($model->item_status == Item::ITEM_STATUS_PUBLISH) {
                            return '
                                              <span class="text-success"> Published </span>';
                        } else if ($model->item_status == Item::ITEM_STATUS_UNPUBLISH) {
                            return '<span class="text-danger"> Unpublished </span>';
                        }
                    }
                ],
                [
                    'header' => 'Action',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {update}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a(
                                           $model->item_status == Item::ITEM_STATUS_PUBLISH ? '<div class="chip chip-danger mr-1">
                                                         <div class="chip-body">
                                                             <span class="chip-text" style="white-space: pre;"> Unpublish</span>
                                                         </div>
                                                     </div>' : '<div class="chip chip-success mr-1">
                                                                   <div class="chip-body">
                                                                       <span class="chip-text" style="white-space: pre;"> Publish </span>
                                                                   </div>
                                                               </div>' , ['change-item-status', 'id' => $model->item_uuid, 'storeUuid' => $model->restaurant_uuid], [
                                        'title' => $model->item_status == Item::ITEM_STATUS_PUBLISH ? 'Unpublish' : 'Publish',
                                        'data-pjax' => '0',
                                              'class' =>  $model->item_status == Item::ITEM_STATUS_UNPUBLISH ?  'text-success' : 'text-danger',
                                              'style' => 'padding: 10px;'
                                            ]
                            );
                        },
                    ],
                ],
            ],
            'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table data-list-view dataTable'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

  <?php } else {?>


    <div class="card">
      <div style="padding: 70px 0; text-align: center;">

        <div>
          <img src="https://res.cloudinary.com/plugn/image/upload/v1607881378/emptystate-item.svg" width="226" alt="" />
        </div>

        <h3>
          Add and manage your products
        </h3>

        <p>
          This is where you’ll add products and manage your pricing. You can also import and export your product inventory.
        </p>
        <?= Html::a('Add item', ['create', 'storeUuid' => $restaurant->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
      </div>
    </div>


  <?php } ?>

</section>
<!-- Data list view end -->
