<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>

    </p>
    <div class="card">
        <div class="card-body">
            <div class="box-body table-responsive no-padding">

                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'name',
                        'name_ar',
                        'tagline',
                        'tagline_ar',
                        'country.country_name',
                        [
                            'label' => 'Store currency',
                            'value' => function ($data) {
                              return $data->currency->title;
                            }
                        ],
                        // [
                        //     'label' => 'Support Delivery',
                        //     'value' => function ($data) {
                        //         return $data->support_delivery ? 'Yes' : 'No';
                        //     },
                        //     'format' => 'raw'
                        // ],
                        // [
                        //     'label' => 'Support Pick up',
                        //     'value' => function ($data) {
                        //         return $data->support_pick_up ? 'Yes' : 'No';
                        //     },
                        //     'format' => 'raw'
                        // ],
                        [
                            'attribute' => 'phone_number',
                            'format' => 'html',
                            'value' => function ($data) {
                                return $data->phone_number;
                            },
                            'visible' => $model->phone_number != null,
                        ],
                        'restaurant_email:email',
                        [
                            'attribute' => 'restaurant_email_notification',
                            'format' => 'html',
                            'value' => function ($data) {
                               return $data->restaurant_email_notification ? 'Yes' : 'No';
                            },
                        ],
                        [
                            'attribute' => 'schedule_order',
                            'format' => 'html',
                            'value' => function ($data) {
                               return $data->schedule_order ? 'Yes' : 'No';
                            },
                        ],
                        'schedule_interval',
                        [
                            'attribute' => 'instagram_url',
                            'format' => 'html',
                            'value' => function ($data) {
                                $url = trim((string) $data->instagram_url);
                                $label = Html::encode($url);
                                $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

                                if (!in_array($scheme, ['http', 'https'], true)) {
                                    return $label;
                                }

                                return Html::a($label, $url, [
                                    'target' => '_blank',
                                    'rel' => 'noopener noreferrer',
                                ]);
                            },
                            'visible' => $model->instagram_url != null,
                        ],
                    ],
                    'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
                ])
                ?>

            </div>
        </div>
    </div>

</div>
