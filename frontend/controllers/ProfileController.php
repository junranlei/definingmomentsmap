<?php

namespace frontend\controllers;
use Yii;
use yii\base\Module;
use yii\db\ActiveRecord;
use Da\User\Model\User;
use Da\User\Event\UserEvent;
use Da\User\Query\UserQuery;
use Da\User\Search\UserSearch;
use Da\User\Query\ProfileQuery;
use Da\User\Model\Profile;
use Da\User\Service\UserCreateService;
use Da\User\Traits\ContainerAwareTrait;
use Da\User\Traits\ModuleAwareTrait;
use Da\User\Validator\AjaxRequestModelValidator;
use Da\User\Controller\ProfileController as BaseController;
use Da\User\Event\FormEvent;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProfileController extends BaseController
{
    use ContainerAwareTrait;
    use ModuleAwareTrait;
    /**
     * @var ProfileQuery
     */
    protected $profileQuery;
    /**
     * @var UserQuery
     */
    protected $userQuery;

     /**
     * ProfileController constructor.
     *
     * @param string       $id
     * @param Module       $module
     * @param ProfileQuery $profileQuery
     * @param array        $config
     */
    public function __construct($id, Module $module, ProfileQuery $profileQuery, array $config = [])
    {
        $this->profileQuery = $profileQuery;
        $this->userQuery = new UserQuery(User::class);
        parent::__construct($id, $module, $profileQuery,$config);
    }

     /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','show','updateprofile','updateaccount'],
                        'roles' => ['@'],
                    ],
                    /*[
                        'allow' => true,
                        'actions' => ['show'],
                        'roles' => ['?', '@'],
                    ],*/
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        return $this->redirect(['show', 'id' => Yii::$app->user->getId()]);
    }

    public function actionShow($id)
    {
        $profile = $this->profileQuery->whereUserId($id)->one();

        if ($profile === null) {
            throw new NotFoundHttpException();
        }

        return $this->render(
            'show',
            [
                'profile' => $profile,
            ]
        );
    }

    public function actionUpdateaccount($id)
    {
        $user = $this->userQuery->where(['id' => $id])->one();
        $user->setScenario('update');
        /** @var UserEvent $event */
        $event = $this->make(UserEvent::class, [$user]);

        $this->make(AjaxRequestModelValidator::class, [$user])->validate();

        if ($user->load(Yii::$app->request->post())) {
            $this->trigger(UserEvent::EVENT_BEFORE_ACCOUNT_UPDATE, $event);

            if ($user->save()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('usuario', 'Account details have been updated'));
                $this->trigger(UserEvent::EVENT_AFTER_ACCOUNT_UPDATE, $event);

                return $this->refresh();
            }
        }

        return $this->render('_account', ['user' => $user]);
    }


    public function actionUpdateprofile($id)
    {
        /** @var User $user */
        $user = $this->userQuery->where(['id' => $id])->one();
        /** @var Profile $profile */
        $profile = $user->profile;
        if ($profile === null) {
            $profile = $this->make(Profile::class);
            $profile->link('user', $user);
        }
        /** @var UserEvent $event */
        $event = $this->make(UserEvent::class, [$user]);

        $this->make(AjaxRequestModelValidator::class, [$profile])->validate();

        if ($profile->load(Yii::$app->request->post())) {
            if ($profile->save()) {
                $this->trigger(UserEvent::EVENT_BEFORE_PROFILE_UPDATE, $event);
                Yii::$app->getSession()->setFlash('success', Yii::t('usuario', 'Profile details have been updated'));
                $this->trigger(UserEvent::EVENT_AFTER_PROFILE_UPDATE, $event);

                return $this->refresh();
            }
        }

        return $this->render(
            '_profile',
            [
                'user' => $user,
                'profile' => $profile,
            ]
        );
    }
}