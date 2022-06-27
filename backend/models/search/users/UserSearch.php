<?php

namespace backend\models\search\users;

use common\models\query\PersonQuery;
use common\models\query\UserQuery;
use common\models\User;
use common\models\users\BaseUser;
use yii\base\Model;
use yii\rbac\Role;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
abstract class UserSearch extends User
{
    /**
     * @var \yii\web\User
     */
    protected $webUser;

    /**
     * @return string
     */
    abstract protected function getModelName(): string;

    /**
     * @return array
     */
    abstract public function getColumns(): array;

    /**
     * UserSearch constructor.
     * @param \yii\web\User $webUser
     * @param array $config
     */
    public function __construct(\yii\web\User $webUser, array $config = [])
    {
        $this->webUser = $webUser;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        /** @var BaseUser $modelName */
        $modelName = $this->getModelName();
        return $modelName::getRole();
    }

    /**
     * @param BaseUser $user
     * @return bool
     */
    public function canUpdate(BaseUser $user): bool
    {
        return $user->id == $this->webUser->id || $this->webUser->can($user::getCreatePermName());
    }

    /**
     * @return UserQuery
     */
    protected function getSearchQuery(): UserQuery
    {
        /** @var BaseUser $modelName */
        $modelName = $this->getModelName();

        return $modelName::find()
            ->alias('user')
            ->innerJoin('{{auth_assignment}} AS auth', 'auth.user_id = user.id AND auth.item_name = :role', [':role' => $this->getRole()])
            ->innerJoin('{{auth_item}} AS item', 'item.name = auth.item_name AND type = :type', [':type' => Role::TYPE_ROLE])
            ->joinWith(['person AS person' => function (PersonQuery $query) {
                $query
                    ->innerJoinWith('country AS c')
                    ->byCountries(array_keys($this->webUser->identity->availableCountries));
            }], true, 'INNER JOIN');
    }
}
