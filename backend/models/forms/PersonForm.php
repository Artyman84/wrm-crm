<?php


namespace backend\models\forms;


use borales\extensions\phoneInput\PhoneInputValidator;
use common\models\Country;
use common\models\Person;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class PersonForm
 * @package backend\models\forms
 *
 * @property string $first_name
 * @property string $last_name
 * @property string $patronymic
 * @property string $email
 * @property string $phone
 * @property string $birth_date
 * @property int $country_id
 * @property PersonImageForm $uploadImage
 * @property-read $photoUrl
 * @property-read $photo
 */
class PersonForm extends Model
{
    public const UPLOAD_PATH = 'persons';

    /**
     * @var string
     */
    public string $first_name;

    /**
     * @var string
     */
    public string $last_name;

    /**
     * @var string
     */
    public string $patronymic;

    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $phone;

    /**
     * @var string
     */
    public string $birth_date;

    /**
     * @var int
     */
    public int $country_id;

    /**
     * @var PersonImageForm|null
     */
    public ?PersonImageForm $uploadImage = null;

    /**
     * @var string
     */
    private $photoUrl;

    /**
     * @var string
     */
    private $photo;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        $this->uploadImage = new PersonImageForm();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['country_id', 'first_name', 'last_name', 'phone'], 'required'],
            [['country_id'], 'integer'],
            [['birth_date'], 'safe'],
            [['birth_date'], 'date', 'format' => 'dd-mm-yyyy'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['email'], 'unique', 'targetClass' => Person::class, 'targetAttribute' => 'email'],
            [['first_name', 'last_name', 'patronymic'], 'string', 'max' => 50],
            [['phone'], PhoneInputValidator::class, 'region' => Yii::$app->user->identity->getAvailableCountriesISO2(false)],
            [['phone'], 'string', 'max' => 30],
            [['phone'], 'unique', 'targetClass' => Person::class, 'targetAttribute' => 'phone'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['country_id' => 'id']],

            [['uploadImage'], 'validateImage'],

            [['patronymic', 'email', 'birth_date'], 'default', 'value' => null]
        ];
    }

    /**
     * @param string $attribute
     * @param array|null $params
     */
    public function validateImage(string $attribute,  $params) {
        if (!$this->$attribute->validate()) {
            $this->addErrors([$attribute, $this->$attribute->getErrors()]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'country_id' => Yii::t('app', 'Country'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'patronymic' => Yii::t('app', 'Patronymic'),
            'email' => Yii::t('app', 'E-mail'),
            'phone' => Yii::t('app', 'Phone'),
            'uploadImage' => Yii::t('app', 'Photo'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null): bool
    {
        if (parent::load($data, $formName)) {
            return $this->uploadImage->load($data);
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @throws Exception
     * @return bool
     */
    public function uploadImage(): bool
    {
        $uploadImage = $this->uploadImage;
        $uploadImage->imageFile = UploadedFile::getInstance($uploadImage, 'imageFile');
        $this->photo = $uploadImage->upload(self::UPLOAD_PATH, 650, 800);

        return isset($this->photo);
    }
}