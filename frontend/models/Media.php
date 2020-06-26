<?php

namespace frontend\models;
use yii\helpers\Html;
use yii\helpers\Url;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $source
 * @property int $type
 * @property string $nameOrUrl
 * @property string $creator
 * @property int $right2Link
 * @property int $ownerId
 * @property int $isUrl
 * @property int $publicPermission
 * @property int $status
 * @property int $permission2upload
 * @property HistoricalMediaLink[] $historicalMediaLinks
 * @property HistoricalFact[] $hists
 */
class Media extends \yii\db\ActiveRecord
{

    /** upload files */
    public $files;
    public $isMainMedia;
    public $permission2upload;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * {@inheritdoc}
     *  
     */
    public function behaviors()
    {
        return [
            //add audit log
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'type', 'right2Link', 'ownerId'], 'required'],
            [['type', 'right2Link', 'ownerId', 'isUrl', 'publicPermission','status'], 'integer'],
            [['description'], 'string'],
            [['title', 'nameOrUrl','creator','source'], 'string', 'max' => 255],
            [['files'], 'file', 'skipOnEmpty' => true],
            [['isMainMedia','permission2upload', 'isUrl','status'], 'safe'],
            ['permission2upload', 'validatePermission', 'skipOnEmpty' => false, 'skipOnError' => false]
        ];
    }

    public function validatePermission($attribute, $params)
    {
        if ($this->$attribute!=1) {

            $this->addError($attribute, 'You must have the permission to publish in order to save this media.');
            return false;
        }
    
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'Source'=>'Source',
            'type' => 'Media Type',
            'nameOrUrl' => 'Name Or Url',
            'right2Link' => 'Others Can Link This Media',
            'ownerId' => 'Owner ID',
            'isMainMedia'=>'Set as Main media',
            'creator'=>'Creator',
            'publicPermission'=>'Everyone Can Edit',
            'permission2upload'=>'Permission to publish this media online'
        ];
    }

    /**
     * Gets query for [[HistoricalMediaLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoricalMediaLinks()
    {
        return $this->hasMany(HistoricalMediaLink::className(), ['mediaId' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'ownerId']);
    }
    /**
     * Gets query for [[Hists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHists()
    {
        return $this->hasMany(HistoricalFact::className(), ['id' => 'histId'])->viaTable('historicalMediaLink', ['mediaId' => 'id']);
    }
    /**
     * Get media display html from nameOrUrl and type fields
     * @property int $width px
     * @return String
     */
    public function getMediaUrl($width="80", $height="60"){
        if($this->type==1){
            if($this->isUrl){     
                return Html::img($this->nameOrUrl,
                ['width' => $width, 'style'=>'display:block; margin:0 auto;']);
            
            }else{
        
                return Html::img(Url::base().'/uploads/'.$this->id.'/'.$this->nameOrUrl,
                ['width' => $width, 'style'=>'display:block; margin:0 auto;']); 
            }
                   
        }else if($this->type==2){ 
            if($this->isUrl){     
                return '<video width='.$width.' height='.$height.' controls style="display:block; margin:0 auto;">
                <source src="'.$this->nameOrUrl .'" type="video/mp4">
                </video> ';
            
            }else{
                return '<video width='.$width.' height='.$height.' controls style="display:block; margin:0 auto;">
                <source src="'.Url::base().'/uploads/'.$this->id.'/'.$this->nameOrUrl .'" type="video/mp4">
                </video> ';
            
            }           
        } 
        return "";  
    }
}
