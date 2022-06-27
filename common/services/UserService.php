<?php


namespace common\services;


use backend\models\forms\PersonImageForm;
use common\exceptions\NotSetPropertyException;
use common\models\User;
use common\repositories\BaseRepository;
use common\repositories\BaseRepositoryInterface;
use common\repositories\UserRepository;
use yii\base\Exception;
use yii\web\UploadedFile;

/**
 * Class UserService
 * @package common\services
 */
class UserService extends BaseService
{
    /**
     * @var UserRepository|string
     */
    protected string|BaseRepository $repositoryClassName = UserRepository::class;

    /**
     * @return UserRepository
     */
    public function getRepository(): BaseRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * @throws Exception
     */
    public function saveUserInfo(User $userInfo, PersonImageForm $uploadImage, array $data): bool
    {
        if ($userInfo->load($data) && $userInfo->validate()) {
            $uploadImage->load($data);
            $uploadImage->imageFile = UploadedFile::getInstance($uploadImage, 'imageFile');
            $uploadImage->upload('users', 650, 800);
            $userInfo->photo = $uploadImage->imgName;
            return $userInfo->save();
        }

        return false;
    }

}