<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
use frontend\models\Profile;

/**
 * @var \yii\web\View          $this
 * @var \Da\User\Model\Profile $profile
 */

$this->title = empty($profile->name) ? Html::encode($profile->user->username) : Html::encode($profile->name);
$this->params['breadcrumbs'][] = $this->title;

?>
 <?php
$content= '<br/>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="row">
            <div class="col-sm-6 col-md-4">';
            $content= $content. Html::img(
                    $profile->getAvatarUrl(230),
                    [
                        'class' => 'img-rounded img-responsive',
                        'alt' => $profile->user->username,
                    ]
                ).'
            </div>
            <div class="col-sm-6 col-md-8">
                <h4>'. $this->title .'</h4>
                <ul style="padding: 0; list-style: none outside none;">';
                    if (!empty($profile->location)): 
                        $content= $content.'<li>
                            <i class="glyphicon glyphicon-map-marker text-muted"></i>
                            '. Html::encode($profile->location) .'
                        </li>';
                    endif; 
                    if (!empty($profile->website)): 
                        $content= $content.'<li>
                            <i class="glyphicon glyphicon-globe text-muted"></i>
                            '. Html::a(Html::encode($profile->website), Html::encode($profile->website)) .'
                        </li>';
                    endif; 
                    /*if (!empty($profile->public_email)): 
                        $content= $content.'<li>
                            <i class="glyphicon glyphicon-envelope text-muted"></i>
                            '. Html::a(
                                Html::encode($profile->public_email),
                                'mailto:' .
                                Html::encode($profile->public_email)
                            ).'
                            
                        </li>';
                    endif; */
                    $content= $content.'<li>
                        <i class="glyphicon glyphicon-time text-muted"></i>
                        '.Yii::t('usuario', 'Joined on {0, date}', $profile->user->created_at) .'
                    </li>
                </ul>';
                if (!empty($profile->bio)): 
                    '$content= $content.<p>'.Html::encode($profile->bio) .'</p>';
                endif;
            $content= $content.'</div>
        </div>
    </div>
</div><br/>';
if(\Yii::$app->user->can("updateProfile",$params=['profile' => Profile::findOne(['user_id' => Yii::$app->request->get('id')])])){
    //$content= $content. Html::a('Update', ['updateprofile', 'id' => $profile->user_id], ['class' => 'btn btn-primary']);
    $content= $content. Html::a('Update', ['/user/settings'], ['target'=>'_blank','class' => 'btn btn-primary']);
}

?>
<?php

echo Tabs::widget([

    'items' => [
        [

            'label' => 'Profile',
            'content'=>$content,
            'active' => true         

        ],
        [

            'label' => 'Historical Facts',
            'url' => Url::to(['profile/myhists','id'=>$profile->user_id]),        

        ],

        [
            'label' => 'Maps',
            'url' => Url::to(['profile/mymaps','id'=>$profile->user_id]),

        ],
        

    ],

]);

?>