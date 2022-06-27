<?php


namespace backend\models\forms;


use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Class UploadImageForm
 * @package app\models\forms
 * @property string|null $imgName
 * @property bool $createThumbnail
 */
class PersonImageForm extends Model
{
    public const UPLOAD_PATH = 'persons';

    /**
     * @var bool
     */
    public bool $createThumbnail = true;

    /**
     * @var UploadedFile|null
     */
    public ?UploadedFile $imageFile = null;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['createThumbnail'], 'safe'],
        ];
    }

    /**
     * @param string $path
     * @param int|null $width
     * @param int|null $height
     * @param int $quality
     * @return string|null
     * @throws Exception
     */
    public function upload(string $path, int $width = null, int $height = null, int $quality = 70): ?string
    {
        if ($this->imageFile && $this->validate()) {
            $imgName = Yii::$app->security->generateRandomString(8) . '_' . time() . '.' . $this->imageFile->extension;
            $imgPath = Yii::$app->basePath . '/web/uploads/' . $path . '/' . $imgName;

            if ($this->imageFile->saveAs($imgPath)) {
                $this->saveImage($imgPath, $imgPath, $width, $height, $quality);

                if ($this->createThumbnail) {
                    $imgThumbPath = Yii::$app->basePath . '/web/uploads/' . $path . '/thumb_' . $imgName;
                    $this->saveImage($imgPath, $imgThumbPath, 100, 250, $quality);
                }

                return $imgName;
            }
        }

        return null;
    }

    /**
     * @param string $sourcePath
     * @param string $destPath
     * @param int|null $width
     * @param int|null $height
     * @param int $quality
     */
    private function saveImage(string $sourcePath, string $destPath, int $width = null, int $height = null, int $quality = 70): void
    {
        $image = Image::getImagine()->open($sourcePath);

        if ($width && $height) {
            $image = Image::resize($image, $width, $height);
        }

        $image->save($destPath, ['quality' => $quality]);
    }

    /**
     * @param string $path
     * @param string $imgName
     */
    public static function deleteImage(string $path, string $imgName)
    {
        $path = Yii::$app->basePath . '/web/uploads/' . $path . '/';

        $fileName = $path . $imgName;
        if (file_exists($fileName)) {
            @unlink($fileName);
        }

        $thumbFileName = $path . 'thumb_' . $imgName;
        if (file_exists($thumbFileName)) {
            @unlink($path . 'thumb_' . $imgName);
        }
    }
}