<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;
    public $filename;

    public function rules()
    {
        return [
            [['file'], 'file', 
                'skipOnEmpty' => false, 
                'extensions' => ['txt', 'xml'], 
                'maxSize' => 10*1024*1024, 
                'checkExtensionByMimeType' => true
            ],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->filename = \Yii::$app->security->generateRandomString() . '.' . $this->file->extension;
            $this->file->saveAs("uploads/{$this->filename}");
           
            return true;
        }
        
        return false;
    }
}