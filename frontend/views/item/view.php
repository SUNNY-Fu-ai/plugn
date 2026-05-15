<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Item */

$this->params['restaurant_uuid'] = $storeUuid;

$this->title = $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index', 'storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="item-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->item_uuid, 'storeUuid' => $storeUuid], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->item_uuid, 'storeUuid' => $storeUuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <div class="card">
        <div class="card-body">
            <div class="box-body table-responsive no-padding">
                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'item_name',
                        'item_name_ar',
                        'item_description:html',
                        'item_description_ar:html',
                        'sort_number',
                        // 'stock_qty',
                        [
                            'attribute' => 'Image',
                            'format' => 'html',
                            'value' => function ($data) {
                                return Html::img("https://res.cloudinary.com/plugn/image/upload/c_scale,h_105,w_105/restaurants/" . $data->restaurant->restaurant_uuid . "/items/" . $data->item_image);
                            },
                        ],
                        [
                            'attribute' => 'category_item',
                            'value' => function ($data) {
                                $itemCategoryValues = '';

                                foreach ($data->categoryItems as $key => $itemCategoryValue) {

                                    if ($key == 0)
                                        $itemCategoryValues .= $itemCategoryValue->category->title;
                                    else
                                        $itemCategoryValues .= ', ' . $itemCategoryValue->category->title;
                                }

                                return $itemCategoryValues;
                            },
                            'format' => 'text'
                        ],
                        [
                            'attribute' => 'item_price',
                            "value" => function($data) {
                                    return Yii::$app->formatter->asCurrency($data->item_price, $data->currency->code, [
                                        \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                    ]);
                            },
                        ],
                        'item_created_at',
                        'item_updated_at',
                    ],
                    'options' => ['class' => 'table table-hover text-nowrap  table-bordered'],
                ])
                ?>

            </div>
        </div>
    </div>

    <h2>Options</h2>

    <p>
        <?= Html::a('Create Option', ['option/create', 'item_uuid' => $model->item_uuid, 'storeUuid' => $storeUuid], ['class' => 'btn btn-success']) ?>
    </p>


    <div class="card">
        <?=
        GridView::widget([
            'dataProvider' => $itemOptionsDataProvider,
            'columns' => [
                'option_name',
                'option_name_ar',
                'min_qty',
                'max_qty',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', ['option/view', 'id' => $model->option_id, 'storeUuid' => $model->item->restaurant_uuid], [
                                        'title' => 'View',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', ['option/update', 'id' => $model->option_id, 'storeUuid' => $model->item->restaurant_uuid], [
                                        'title' => 'Update',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', ['option/delete', 'id' => $model->option_id, 'storeUuid' => $model->item->restaurant_uuid], [
                                        'title' => 'Delete',
                                        'data' => [
                                            'confirm' => 'Are you absolutely sure ? You will lose all the information about this option with this action.',
                                            'method' => 'post',
                                        ],
                            ]);
                        },
                    ],
                ],
            ],
            'layout' => '{summary}<div class="card-body"><div class="box-body table-responsive no-padding">{items}{pager}</div></div>',
            'tableOptions' => ['class' => 'table  table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>

    </div>

</div>
