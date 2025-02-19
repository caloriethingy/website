<?php

use yii\db\Migration;

/**
 * Class m230120_214209_init_rbac
 */
class m230120_214209_init_rbac extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        /**
         * Permissions for future use?
         * Maybe...
         */
        // add "view meal" permission
        $viewMeal = $auth->createPermission('viewMeal');
        $viewMeal->description = 'View own meal records';
        $auth->add($viewMeal);

        // add "create meal" permission
        $createMeal = $auth->createPermission('createMeal');
        $createMeal->description = 'Create a new meal record';
        $auth->add($createMeal);

        // add "update meal" permission
        $updateMeal = $auth->createPermission('updateMeal');
        $updateMeal->description = 'Update own meal records';
        $auth->add($updateMeal);

        // add "delete meal" permission
        $deleteMeal = $auth->createPermission('deleteMeal');
        $deleteMeal->description = 'Delete own meal records';
        $auth->add($deleteMeal);


        // add "view all meals" permission (for admin)
        $viewAllMeals = $auth->createPermission('viewAllMeals');
        $viewAllMeals->description = 'View all meal records';
        $auth->add($viewAllMeals);

        // add "update all meals" permission (for admin)
        $updateAllMeals = $auth->createPermission('updateAllMeals');
        $updateAllMeals->description = 'Update any meal record';
        $auth->add($updateAllMeals);

        // add "delete all meals" permission (for admin)
        $deleteAllMeals = $auth->createPermission('deleteAllMeals');
        $deleteAllMeals->description = 'Delete any meal record';
        $auth->add($deleteAllMeals);

        // Add roles
        $user = $auth->createRole('user');
        $auth->add($user);

        $admin = $auth->createRole('admin');
        $auth->add($admin);


        // Add permissions to roles (crucially important):
        $auth->addChild($user, $viewMeal);
        $auth->addChild($user, $createMeal);
        $auth->addChild($user, $updateMeal);
        $auth->addChild($user, $deleteMeal);

        $auth->addChild($admin, $viewMeal);
        $auth->addChild($admin, $createMeal);
        $auth->addChild($admin, $updateMeal);
        $auth->addChild($admin, $deleteMeal);
        $auth->addChild($admin, $viewAllMeals);
        $auth->addChild($admin, $updateAllMeals);
        $auth->addChild($admin, $deleteAllMeals);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($admin, 1);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }
}
