<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use \app\rbac\UserGroupRule;
use \app\rbac\RedactorPostOwnerRule;

class RbacController extends Controller
{
    public function actionInit()
    {
        $authManager = \Yii::$app->authManager;
        $authManager->removeAll();

        // Create roles
        $guest = $authManager->createRole('guest');
        $user = $authManager->createRole('user');
        $redactor = $authManager->createRole('redactor');
        $moderator = $authManager->createRole('moderator');
        $admin = $authManager->createRole('admin');
        $root = $authManager->createRole('root');

        // Create simple, based on action{$NAME} permissions
        $login = $authManager->createPermission('login');
        $logout = $authManager->createPermission('logout');
        $emailconfirm = $authManager->createPermission('email-confirm');
        $passwordrequest = $authManager->createPermission('password-request');
        $passwordreset = $authManager->createPermission('password-reset');
        $resendverificationemail = $authManager->createPermission('resend-verification-email');
        $agreement = $authManager->createPermission('agreement');
        $error = $authManager->createPermission('error');
        $auth = $authManager->createPermission('auth');
        $signup = $authManager->createPermission('signup');
        $index = $authManager->createPermission('index');
        $view = $authManager->createPermission('view');
        $update = $authManager->createPermission('update');
        $delete = $authManager->createPermission('delete');
        $create = $authManager->createPermission('create');
        $about = $authManager->createPermission('about');
        $contact = $authManager->createPermission('contact');
        $captcha = $authManager->createPermission('captcha');
        $updateOwnPost = $authManager->createPermission('updateOwnPost');
        $deleteOwnPost = $authManager->createPermission('deleteOwnPost');


        // Add permissions in Yii::$app->authManager
        $authManager->add($login);
        $authManager->add($logout);
        $authManager->add($emailconfirm);
        $authManager->add($passwordrequest);
        $authManager->add($passwordreset);
        $authManager->add($resendverificationemail);
        $authManager->add($agreement);
        $authManager->add($auth);
        $authManager->add($error);
        $authManager->add($signup);
        $authManager->add($index);
        $authManager->add($view);
        $authManager->add($update);
        $authManager->add($delete);
        $authManager->add($create);
        $authManager->add($about);
        $authManager->add($contact);
        $authManager->add($captcha);

        // Add rule, based on UserExt->group === $user->group
        $userGroupRule = new UserGroupRule();
        $redactorPostOwnerRule = new RedactorPostOwnerRule();
        $authManager->add($userGroupRule);
        $authManager->add($redactorPostOwnerRule);

        // Add rule "UserGroupRule" in roles
        $guest->ruleName = $userGroupRule->name;
        $user->ruleName = $userGroupRule->name;
        $redactor->ruleName = $userGroupRule->name;
        $moderator->ruleName = $userGroupRule->name;
        $admin->ruleName = $userGroupRule->name;
        $root->ruleName = $userGroupRule->name;
        $updateOwnPost->ruleName = $redactorPostOwnerRule->name;
        $deleteOwnPost->ruleName = $redactorPostOwnerRule->name;

        // Add roles in Yii::$app->authManager
        $authManager->add($guest);
        $authManager->add($user);
        $authManager->add($redactor);
        $authManager->add($moderator);
        $authManager->add($admin);
        $authManager->add($root);
        $authManager->add($updateOwnPost);
        $authManager->add($deleteOwnPost);

        // Add permission-per-role in Yii::$app->authManager
        // Guest
        $authManager->addChild($guest, $login);
        $authManager->addChild($guest, $signup);
        $authManager->addChild($guest, $emailconfirm);
        $authManager->addChild($guest, $passwordrequest);
        $authManager->addChild($guest, $resendverificationemail);
        $authManager->addChild($guest, $agreement);
        $authManager->addChild($guest, $passwordreset);
        $authManager->addChild($guest, $auth);
        $authManager->addChild($guest, $error);
        $authManager->addChild($guest, $index);
        $authManager->addChild($guest, $view);
        $authManager->addChild($guest, $about);
        $authManager->addChild($guest, $contact);
        $authManager->addChild($guest, $captcha);

        // user
        $authManager->addChild($user, $logout);
        $authManager->addChild($user, $guest);

        // redactor
        $authManager->addChild($redactor, $create);
        $authManager->addChild($redactor, $updateOwnPost);
        $authManager->addChild($redactor, $deleteOwnPost);
        $authManager->addChild($redactor, $user);

        //moderator
        $authManager->addChild($moderator, $redactor);

        // admin
        $authManager->addChild($moderator, $update);
        $authManager->addChild($admin, $delete);
        $authManager->addChild($admin, $moderator);

        // root
        $authManager->addChild($root, $admin);
    }
}