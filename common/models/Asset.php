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
 * @property integer $parent_id
 * @property string $filename
 * @property string $thumbnail
 * @property integer $assetable_id
 * @property string $assetable_type
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
     * @var string Base path on server where images collected
     */
    public $basePath;
    /**
     * @var string Base url path to collected images
     */
    public $baseUrl;

    /**
     * @var string assetable types
     */
    const ASSETABLE_ALBUM       = 'album';
    const ASSETABLE_ALBUM_COVER = 'album_cover';
    const ASSETABLE_BANNER      = 'banner';
    const ASSETABLE_COACH       = 'coach';
    const ASSETABLE_TEAM        = 'team';
    const ASSETABLE_COUNTRY     = 'country';
    const ASSETABLE_PLAYER      = 'player';
    const ASSETABLE_POST        = 'post';
    const ASSETABLE_USER        = 'user';
    const ASSETABLE_MATCH_EVENT = 'match_event';
    const ASSETABLE_VIDEO       = 'video';
    const ASSETABLE_VIDEOFILE   = 'videofile';

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
     * @inheritdoc
     */
    public function __construct( $config = [] ) {
        // Init pathes
        $this->basePath = Yii::getAlias('@frontend').'/web/images/store/';
        $this->baseUrl = 'http://'.$_SERVER['HTTP_HOST'].'/images/store/';
        parent::__construct($config);
    }

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
            [['assetable_id', 'parent_id'], 'integer'],
            [['filename', 'thumbnail'], 'string', 'max' => 255],
            [['assetable_type'], 'string', 'max' => 20],

            //required
            [['filename'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'parent_id'      => 'Parent ID',
            'filename'       => 'Filename',
            'thumbnail'      => 'Thumbnail',
            'assetable_id'   => 'Assetable ID',
            'assetable_type' => 'Assetable Type',
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
            if(!$this->save()) {
                return false;
            }
            $this->createFilePath();
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
            $imageBox = $this->getImageBox($size);
            $ratio = $imageBox->getWidth() / $imageBox->getHeight();
            $cropRatio = $cropData[2] / $cropData[3];
            $point = new Point($cropData[0] * $width, $cropData[1] * $height);
            if($ratio == $cropRatio) {
                $box = new Box($cropData[2] * $width, $cropData[3] * $height);
            } else {
                if ($cropData[2] < $cropData[3]) {
                    $side = $cropData[2];
                    $box = new Box($side * $width, $side / $ratio * $width);

                } else {
                    $side = $cropData[3];
                    $box = new Box($side * $ratio * $height, $side * $height);
                }
            }
            $imagine->crop($point, $box);

            if($imageBox) {
                $imagine->resize($imageBox);
            }
        }
        
        $imagine->save($this->getFilePath());

        return true;
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
            if(!$this->save()) {
                return false;
            }
            $this->createFilePath();
            // svg 
            if($this->uploadedFile->type == 'image/svg+xml') {
                $this->uploadedFile->saveAs($this->getFilePath());
                return true;
            }
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
            return true;
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

        return true;
    }

    /**
     * Save usual asset record and attached file
     *
     * @return boolean
     */
    public function saveVideoAsset() 
    {
        if(!empty($this->uploadedFile))
        {
            // If file is exist -> remove him
            if(!empty($this->filename) && file_exists($this->getFilePath()))
            {
                unlink($this->getFilePath());
            }
            $this->genFilename();
            if(!$this->save()) {
                return false;
            }
            $this->createFilePath();
            $this->uploadedFile->saveAs($this->getFilePath());
        }
        else
        {
            return false;
        }
        return true;
    }

    /**
     * Get folder name
     * @return string
     */
    public function getFolderName()
    {
        switch ($this->getAssetableType()) {
            case self::ASSETABLE_POST:
            case self::ASSETABLE_VIDEO:
            case self::ASSETABLE_ALBUM:
            case self::ASSETABLE_ALBUM_COVER:
                $path = 'galleries/';
                break;
            case self::ASSETABLE_PLAYER:
            case self::ASSETABLE_COACH:
                $path = 'faces/';
                break;
            case self::ASSETABLE_BANNER:
                $path = 'banners/';
                break;
            case self::ASSETABLE_COUNTRY:
                $path = 'flags/';
                break;
            case self::ASSETABLE_USER:
                $path = 'avatars/';
                break;
            case self::ASSETABLE_TEAM:
                $path = 'logos/';
                break;
            case self::ASSETABLE_MATCH_EVENT:
                $path = 'icons/';
                break;
            case self::ASSETABLE_VIDEOFILE:
                $path = 'videos/';
                break;
            default: 
                $path = 'other/';
                break;
        }
        $id = !isset($this->parent_id) ? $this->id : $this->parent_id; 
        $folderFirst = sprintf('%04d', floor($id / 10000));
        $folderLast = sprintf('%04d', $id % 10000);
        $path .= $folderFirst.'/'.$folderLast.'/';
        return $path;
    }

    /**
     * Get folder path
     * @return string
     */
    public function getFolderPath()
    {
        if(!isset($this->id)) return false;
        return $this->basePath.$this->getFolderName();
    }

    /**
     * Get folder url
     * @return string
     */
    public function getFolderUrl()
    {
        if(!isset($this->id)) return false;
        return $this->baseUrl.$this->getFolderName();
    }

    /**
     * Get file path
     *
     * @return string
     */
    public function getFilePath()
    {
        $folderPath = $this->getFolderPath();
        if(!$folderPath || empty($this->filename)) return false;
        return $folderPath.$this->filename;
    }

    /**
     * Get file url
     *
     * @return mixed
     */
    public function getFileUrl()
    {
        $excludeAssetableTypes = [
            self::ASSETABLE_POST,
            self::ASSETABLE_TEAM,
        ];
        $filePath = $this->getFilePath();
        // If image not found in DB
        if(empty($this->filename) && in_array($this->getAssetableType(), $excludeAssetableTypes)) {
            return false;
        }
        // Only for teams. If image not found on server
        if(!file_exists($filePath) && in_array($this->getAssetableType(), [self::ASSETABLE_TEAM, self::ASSETABLE_VIDEOFILE])) {
            return false;
        }
        // Default image
        if(!file_exists($filePath)) {
            return $this->getDefaultFileUrl();
        }
        return $this->getFolderUrl().$this->filename;
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
     * Generate unique file name
     *
     * @return void
     */
    public function createFilePath()
    {
        $folderPath = $this->getFolderPath();
        if($folderPath && !file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
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
    public static function getAssets($assetableId, $assetableType, $thumbnail = null, $single = false)
    {
        $query = Asset::find()
            ->where([
                'assetable_id' => $assetableId,
                'assetable_type' => $assetableType,
            ]);
        if(isset($thumbnail)) {
            if($thumbnail === false) {
                $query->andWhere(['thumbnail' => null]);
            } else {
                $query->andWhere(['thumbnail' => $thumbnail]);
            }
        } 

        if(!$single) {
            return $query->all();
        }
        $asset = $query->one();
        if(isset($asset)) {
            return $asset;
        } else {
            $asset = new Asset;
            $asset->assetable_type = $assetableType;
            return $asset;
        }
    }

    /**
     * Get possible thumbnail names by assetable type
     *
     * @return array Array of possible thumbnail names
     */
    public static function getThumbnails($assetableType)
    {
        switch ($assetableType) {
            case self::ASSETABLE_POST:
            case self::ASSETABLE_VIDEO:
                return [
                    self::THUMBNAIL_BIG,
                    self::THUMBNAIL_NEWS,
                    self::THUMBNAIL_CONTENT,
                ];
            case self::ASSETABLE_ALBUM:
            case self::ASSETABLE_ALBUM_COVER:
                return [
                    self::THUMBNAIL_BIG,
                    self::THUMBNAIL_SMALL,
                    self::THUMBNAIL_CONTENT,
                ];
            case self::ASSETABLE_USER:
            case self::ASSETABLE_PLAYER:
            case self::ASSETABLE_COACH:
                return [
                    self::THUMBNAIL_SMALL,
                    self::THUMBNAIL_CONTENT,
                ];
            case self::ASSETABLE_USER:
                return [
                    self::THUMBNAIL_CONTENT,
                ];
            case self::ASSETABLE_COUNTRY:
                return [
                    self::THUMBNAIL_SMALL,
                ];
            case self::ASSETABLE_TEAM:
                return [
                    self::THUMBNAIL_CONTENT,
                ];
            default: return [];
        }
    }

    /**
     * @param boolean $size Imagine\Image\Size
     * 
     * @return Imagine\Image\Box Return size of image
     */
    public function getImageBox($size)
    {
        switch ($this->getAssetableType())
        {
            case self::ASSETABLE_USER:
                switch (strtolower($this->thumbnail))
                {
                    case self::THUMBNAIL_CONTENT:
                        return new Box(100, 100);
                    default: break;
                }
                break;
            case self::ASSETABLE_COUNTRY:
                switch (strtolower($this->thumbnail))
                {
                    case self::THUMBNAIL_SMALL:
                        return new Box(90, 60);
                    default: break;
                }
                break;
            case self::ASSETABLE_MATCH_EVENT:
                return new Box(25,25);
            case self::ASSETABLE_POST:
            case self::ASSETABLE_VIDEO:
                switch (strtolower($this->thumbnail))
                {
                    // To slider
                    case self::THUMBNAIL_BIG:
                        // return new Box($size->getWidth()*290/$size->getHeight(),290);
                        return new Box(300, 200);
                    // To top 6 news and other preview news
                    case self::THUMBNAIL_NEWS:
                        return new Box(166*2, 110*2);
                    // To content
                    case self::THUMBNAIL_CONTENT:
                        $width = 595;
                        $height = $size->getHeight()*$width/$size->getWidth();
                        $height = $height > 400 ? 400 : $height;
                        return new Box($width, $height);
                    // original
                    default: break;
                }
            case self::ASSETABLE_PLAYER:
            case self::ASSETABLE_COACH:
                switch (strtolower($this->thumbnail))
                {
                    case self::THUMBNAIL_SMALL:
                        return new Box(150, 150);
                    case self::THUMBNAIL_CONTENT:
                        return new Box(300, 450);
                    default: break;
                }
            case self::ASSETABLE_TEAM:
                switch (strtolower($this->thumbnail))
                {
                    case self::THUMBNAIL_CONTENT:
                        return new Box(200, 200);
                    default: break;
                }
                break;
            case self::ASSETABLE_ALBUM:
            case self::ASSETABLE_ALBUM_COVER:
                switch (strtolower($this->thumbnail))
                {
                    case self::THUMBNAIL_BIG:
                        return new Box(300, 200);
                    case self::THUMBNAIL_SMALL:
                        return new Box(90, 60);
                    case self::THUMBNAIL_CONTENT:
                        return new Box(615, 410);
                    default: break;
                }
            default: break;
        }
        return new Box($size->getWidth(),$size->getHeight());
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
                // chdir($this->getFolderPath());
                unlink($this->getFilePath());
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        Comment::deleteAll(['commentable_type' => Comment::COMMENTABLE_PHOTO ,'commentable_id' => $this->id]);
        CommentCount::deleteAll(['commentable_type' => CommentCount::COMMENTABLE_PHOTO ,'commentable_id' => $this->id]);
    }
}
