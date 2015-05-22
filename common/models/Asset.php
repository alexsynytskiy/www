<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\Color;
use Imagine\Image\ManipulatorInterface;


/**
 * This is the model class for table "assets".
 *
 * @property integer $id
 * @property string $filename
 * @property string $thumbnail
 * @property integer $width
 * @property integer $height
 * @property string $type
 * @property integer $assetable_id
 * @property string $assetable_type
 * @property integer $comments_count
 *
 * @property Users $user
 * @property Asset $parent
 * @property Asset[] $assets
 */
class Asset extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile uploaded file
     */
    public $uploadedFile;

    /**
     * @var string Coordinates data for crop image
     */
    public $cropData;

    /**
     * @var string assetable types
     */
    const ASSETABLE_ALBUM   = 'album';
    const ASSETABLE_BANNER  = 'banner';
    const ASSETABLE_COACH   = 'coach';
    const ASSETABLE_COMMAND = 'command';
    const ASSETABLE_COUNTRY = 'country';
    const ASSETABLE_PLAYER  = 'player';
    const ASSETABLE_POST    = 'post';
    const ASSETABLE_USER    = 'user';

    /**
     * @var string assets thumbnail types
     */
    const THUMBNAIL_ALBUM   = 'album';
    const THUMBNAIL_BIG     = 'big';
    const THUMBNAIL_CONTENT = 'content';
    const THUMBNAIL_COVER   = 'cover';
    const THUMBNAIL_NEWS    = 'news';
    const THUMBNAIL_POSTER  = 'poster';
    const THUMBNAIL_SMALL   = 'small';
    const THUMBNAIL_THUMB   = 'thumb';

    /**
     * @var string asset types
     */
    const TYPE_PHOTO      = 'photo';
    const TYPE_ATTACHMENT = 'attachment';
    const TYPE_AVATAR     = 'avatar';
    const TYPE_FACE       = 'face';
    const TYPE_FLAG       = 'flag';
    const TYPE_IMAGE      = 'image';
    const TYPE_LOGO       = 'logo';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'assets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['width', 'height', 'assetable_id', 'comments_count'], 'integer'],
            [['filename', 'thumbnail'], 'string', 'max' => 255],
            [['type', 'assetable_type'], 'string', 'max' => 20],

            //required
            [['type', 'filename'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'thumbnail' => 'Thumbnail',
            'width' => 'Width',
            'height' => 'Height',
            'type' => 'Type',
            'assetable_id' => 'Assetable ID',
            'assetable_type' => 'Assetable Type',
            'comments_count' => 'Comments Count',
        ];
    }

    /**
     * Save cropped asset record and attached file
     *
     * @return boolean
     */
    public function saveCroppedAsset()
    {
        if(!empty($this->uploadedFile))
        {
            // If file is exist -> remove him
            if(!empty($this->filename) && file_exists($this->getFilePath()))
            {
                unlink($this->getFilePath());
            }

            $this->genFilename();
            $imagine = Image::getImagine()->open($this->uploadedFile->tempName);
        }
        else
        {
            if(file_exists($this->getFilePath())) {
                $imagine = Image::getImagine()->open($this->getFilePath());
            } else return false;
        }

        $size = $imagine->getSize();
        $width = $size->getWidth();
        $height = $size->getHeight();

        $cropData = explode(';', $this->cropData);

        if(count($cropData) == 4)
        {
            $point = new Point($cropData[0]*$width, $cropData[1]*$height);
            $box = new Box($cropData[2]*$width, $cropData[3]*$height);
            $imagine->crop($point, $box);
            $imageBox = $this->getImageBox($size);

            if($imageBox) {
                $imagine->resize($imageBox);
            }
        }
        $imagine->save($this->getFilePath());

        return $this->save(false);
    }

     /**
     * Save usual asset record and attached file
     *
     * @return boolean
     */
    public function saveAsset()
    {
        if(!empty($this->uploadedFile))
        {
            // If file is exist -> remove him
            if(!empty($this->filename) && file_exists($this->getFilePath()))
            {
                unlink($this->getFilePath());
            }
            $this->genFilename();
            $img = Image::getImagine()->open($this->uploadedFile->tempName);
        }
        else
        {
            if(file_exists($this->getFilePath())) {
                $img = Image::getImagine()->open($this->getFilePath());
            } else return false;
        }

        $size = $img->getSize();
        $box = $this->getImageBox($size);

        if (($img->getSize()->getWidth() <= $box->getWidth() && $img->getSize()->getHeight() <= $box->getHeight()) || (!$box->getWidth() && !$box->getHeight())) {
            $img->copy()->save($this->getFilePath());
            return $this->save(false);
        }

        $img = $img->thumbnail($box, ManipulatorInterface::THUMBNAIL_OUTBOUND);

        // create empty image to preserve aspect ratio of thumbnail
        $thumb = Image::getImagine()->create($box, new Color('FFF', 100));

        // calculate points
        $startX = 0;
        $startY = 0;
        if ($size->getWidth() < $box->getWidth()) {
            $startX = ceil($box->getWidth() - $size->getWidth()) / 2;
        }
        if ($size->getHeight() < $box->getHeight()) {
            $startY = ceil($box->getHeight() - $size->getHeight()) / 2;
        }

        $thumb->paste($img, new Point($startX, $startY));
        $thumb->save($this->getFilePath());

        return $this->save(false);
    }

    /**
     * Get file path
     *
     * @return string
     */
    public function getFilePath()
    {
        if(empty($this->filename)) return false;
        return Yii::getAlias('@frontend').'/web/images/store/'.$this->getAssetableType().'/'.$this->filename;
    }

    /**
     * Get file url
     *
     * @return mixed
     */
    public function getFileUrl()
    {
        if(empty($this->filename) && $this->getAssetableType() != self::ASSETABLE_USER){
            return false;
        }
        if(!file_exists($this->getFilePath())) {
            return $this->getDefaultFileUrl();
        }
        return 'http://'.$_SERVER['HTTP_HOST'].'/images/store/'.$this->getAssetableType().'/'.$this->filename;
    }

    /**
     * Get default file url
     *
     * @return string
     */
    public function getDefaultFileUrl()
    {   
        switch ($this->getAssetableType()) {
            case self::ASSETABLE_COACH:
            case self::ASSETABLE_PLAYER:
            case self::ASSETABLE_USER:
                return 'http://'.$_SERVER['HTTP_HOST'].'/images/default_user_image.png';
            default:
                return 'http://'.$_SERVER['HTTP_HOST'].'/images/default_image.png';
        }
    }

    /**
     * Get assetable type
     *
     * @return string
     */
    public function getAssetableType()
    {
        return strtolower($this->assetable_type);
    }

    /**
     * Generate unique file name
     *
     * @return void
     */
    public function genFilename()
    {
        $filename = $this->getAssetableType().$this->assetable_id.'_';
        $filename .= substr(md5(rand(10000,99999).microtime()),0,5);
        if(!empty($this->thumbnail)) $filename .= '_'.$this->thumbnail;
        $filename .= '.'.$this->uploadedFile->extension;
        $this->filename = $filename;
    }

    /**
     * Get possible thumbnail names by assetable type
     *
     * @return array Array of possible thumbnail names
     */
    public function getThumbnails($assetableType)
    {
        switch ($assetableType) {
            case self::ASSETABLE_POST:
                return [
                    self::THUMBNAIL_BIG,
                    self::THUMBNAIL_NEWS,
                    self::THUMBNAIL_COVER,
                    // self::THUMBNAIL_ALBUM,
                    // self::THUMBNAIL_SMALL,
                    // self::THUMBNAIL_POSTER,
                    // self::THUMBNAIL_CONTENT,
                ];
            default: return [];
        }
    }

    /**
     * Get all assets file for assetable entity
     *
     * @param int $assetableId
     * @param string $assetableType
     * @param string $thumbnail
     * @param boolean $single
     *
     * @return array[Asset] or Asset if $single == true
     */
    public static function getAssets($assetableId, $assetableType, $thumbnail, $single = false)
    {
        $query = Asset::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'assetable_id' => $assetableId,
            'assetable_type' => $assetableType,
            'thumbnail' => $thumbnail,
        ]);

        $models = $dataProvider->getModels();

        if(!$single) {
            return $models;
        }
        return isset($models[0]) ? $models[0] : new Asset;
    }

    /**
     * @return Imagine\Image\Box Return size of image
     */
    public function getImageBox($size)
    {
        switch ($this->getAssetableType())
        {
            case self::ASSETABLE_USER:
                return new Box(80,80);
            case self::ASSETABLE_POST:
                switch (strtolower($this->thumbnail))
                {
                    // To slider
                    case self::THUMBNAIL_BIG:
                        // return new Box($size->getWidth()*290/$size->getHeight(),290);
                        return new Box(300,200);
                    // To news preview
                    case self::THUMBNAIL_NEWS:
                        return new Box(172,116);
                    // To top 6 news
                    case self::THUMBNAIL_COVER:
                        return new Box(166,109);
                    // original
                    default: return new Box($size->getWidth(),$size->getHeight());
                    // case self::THUMBNAIL_ALBUM:
                    //     return new Box(150,150);
                    // case self::THUMBNAIL_SMALL:
                    //     return new Box(50,50);
                    // case self::THUMBNAIL_POSTER:
                    //     return new Box(100,75);
                    // case self::THUMBNAIL_CONTENT:
                    //     return new Box($size->getWidth(),$size->getHeight());
                }
            default: return new Box($size->getWidth(),$size->getHeight());
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // If file is exist -> remove it
            if(!empty($this->filename) && file_exists($this->getFilePath()))
            {
                unlink($this->getFilePath());
            }
            return true;
        } else {
            return false;
        }
    }
}
