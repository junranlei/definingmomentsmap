<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Da\User\Model\User;
use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View   $this
 * @var User   $user
 * @var string $content
 */

$this->title = Yii::t('usuario', 'Update user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('usuario', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="clearfix"></div>
<?= $this->render(
    '/shared/_alert',
    [
        'module' => Yii::$app->getModule('user'),
    ]
) ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php // $this->render('/shared/_menu') ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?= Nav::widget(
                                    [
                                        'options' => [
                                            'class' => 'nav-pills nav-stacked',
                                        ],
                                        'items' => [
                                            [
                                                'label' => Yii::t('usuario', 'Account details'),
                                                'url' => ['/user/profile/updateaccount', 'id' => $user->id],
                                            ],
                                            [
                                                'label' => Yii::t('usuario', 'Profile details'),
                                                'url' => ['/user/profile/updateprofile', 'id' => $user->id],
                                            ],
                                            
                                            
                                            '<hr>',
                                            [
                                                'label' => Yii::t('usuario', 'Confirm'),
                                                'url' => ['/user/admin/confirm', 'id' => $user->id],
                                                'visible' => !$user->isConfirmed,
                                                'linkOptions' => [
                                                    'class' => 'text-success',
                                                    'data-method' => 'post',
                                                    'data-confirm' => Yii::t(
                                                        'usuario',
                                                        'Are you sure you want to confirm this user?'
                                                    ),
                                                ],
                                            ],
                                            
                                        ],
                                    ]
                                ) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?= $content ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
