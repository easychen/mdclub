<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controller\AdminController;
use App\Controller\AnswerController;
use App\Controller\ApiController;
use App\Controller\ArticleController;
use App\Controller\CaptchaController;
use App\Controller\EmailController;
use App\Controller\ImageController;
use App\Controller\InboxController;
use App\Controller\IndexController;
use App\Controller\NotificationController;
use App\Controller\OptionController;
use App\Controller\QuestionController;
use App\Controller\TopicController;
use App\Controller\UserController;
use App\Controller\TokenController;

// 页面
$app->get(   '/',                             IndexController::class .        ':pageIndex');
$app->get(   '/migration',                    IndexController::class .        ':migration');
$app->get(   '/topics',                       TopicController::class .        ':pageIndex');
$app->get(   '/topics/{topic_id:\d+}',        TopicController::class .        ':pageDetail');
$app->get(   '/articles',                     ArticleController::class .      ':pageIndex');
$app->get(   '/articles/{article_id:\d+}',    ArticleController::class .      ':pageDetail');
$app->get(   '/questions',                    QuestionController::class .     ':pageIndex');
$app->get(   '/questions/{question_id:\d+}',  QuestionController::class .     ':pageDetail');
$app->get(   '/users',                        UserController::class .         ':pageIndex');
$app->get(   '/users/{user_id:\d+}',          UserController::class .         ':pageDetail');
$app->get(   '/notifications',                NotificationController::class . ':pageIndex');
$app->get(   '/inbox',                        InboxController::class .        ':pageIndex');

$app->group('/api', function () {
    $this->get(   '',                             ApiController::class . ':pageIndex');

    // 系统设置
    $this->get(   '/options',                     OptionController::class . ':getAll');
    $this->patch( '/options',                     OptionController::class . ':setMultiple');

    // 登录
    $this->post(  '/tokens',                      TokenController::class . ':create');

    // 注册
    $this->post(  '/user/register/email',         UserController::class . ':sendRegisterEmail');
    $this->post(  '/users',                       UserController::class . ':create');

    // 重置密码
    $this->post(  '/user/password/email',         UserController::class . ':sendResetEmail');
    $this->put(   '/user/password',               UserController::class . ':updatePasswordByEmail');

    // 用户信息
    $this->get(   '/users',                       UserController::class . ':getList');
    $this->get(   '/users/{user_id:\d+}',         UserController::class . ':getOne');
    $this->delete('/users/{user_id:\d+}',         UserController::class . ':disableOne');
    $this->get(   '/user',                        UserController::class . ':getMe');
    $this->patch( '/user',                        UserController::class . ':updateMe');

    // 用户头像
    $this->delete('/users/{user_id:\d+}/avatar',  UserController::class . ':deleteAvatar');
    $this->post(  '/user/avatar',                 UserController::class . ':uploadMyAvatar');
    $this->delete('/user/avatar',                 UserController::class . ':deleteMyAvatar');

    // 用户封面
    $this->delete('/users/{user_id:\d+}/cover',   UserController::class . ':deleteCover');
    $this->post(  '/user/cover',                  UserController::class . ':uploadMyCover');
    $this->delete('/user/cover',                  UserController::class . ':deleteMyCover');

    // 用户关注
    $this->get(   '/users/{user_id:\d+}/followers',                        UserController::class . ':getFollowers');
    $this->get(   '/users/{user_id:\d+}/following',                        UserController::class . ':getFollowing');
    $this->get(   '/users/{user_id:\d+}/following/{target_user_id:\d+}',   UserController::class . ':isFollowing');
    $this->get(   '/user/followers',                                       UserController::class . ':getMyFollowers');
    $this->get(   '/user/following',                                       UserController::class . ':getMyFollowing');
    $this->put(   '/user/following/{target_user_id:\d+}',                  UserController::class . ':addFollow');
    $this->delete('/user/following/{target_user_id:\d+}',                  UserController::class . ':deleteFollow');
    $this->get(   '/user/following/{target_user_id:\d+}',                  UserController::class . ':isMyFollowing');

    // 话题信息
    $this->get(   '/topics',                                               TopicController::class . ':getList');
    $this->post(  '/topics',                                               TopicController::class . ':create');
    $this->post(  '/topics/{topic_id:\d+}',                                TopicController::class . ':update'); // formData 数据只能通过 post 请求提交，所以这里不用 patch 请求
    $this->delete('/topics/{topic_id:\d+}',                                TopicController::class . ':delete');

    // 话题关注
    $this->get(   '/users/{user_id:\d+}/topics/following',                 TopicController::class . ':getFollowing');
    $this->get(   '/users/{user_id:\d+}/topics/{topic_id:\d+}/following',  TopicController::class . ':isFollowing');
    $this->get(   '/user/topics/following',                                TopicController::class . ':getMyFollowing');
    $this->get(   '/user/topics/{topic_id:\d+}/following',                 TopicController::class . ':isMyFollowing');
    $this->put(   '/user/topics/{topic_id:\d+}/following',                 TopicController::class . ':addFollow');
    $this->delete('/user/topics/{topic_id:\d+}/following',                 TopicController::class . ':deleteFollow');
    $this->get(   '/topics/{topic_id:\d+}/followers',                      TopicController::class . ':getFollowers');

    // 问题信息
    $this->get(   '/questions/recent',                                     QuestionController::class . ':getRecentList');
    $this->get(   '/questions/popular',                                    QuestionController::class . ':getPopularList');
    $this->get(   '/questions',                                            QuestionController::class . ':getList');
    $this->post(  '/questions',                                            QuestionController::class . ':create');
    $this->get(   '/questions/{question_id:\d+}',                          QuestionController::class . ':getOne');
    $this->patch( '/questions/{question_id:\d+}',                          QuestionController::class . ':update');
    $this->delete('/questions/{question_id:\d+}',                          QuestionController::class . ':delete');

    // 问题关注
    $this->get(   '/users/{user_id:\d+}/questions/following',                   QuestionController::class . ':getFollowing');
    $this->get(   '/users/{user_id:\d+}/questions/{question_id:\d+}/following', QuestionController::class . ':isFollowing');
    $this->get(   '/user/questions/following',                                  QuestionController::class . ':getMyFollowing');
    $this->get(   '/user/questions/{question_id:\d+}/following',                QuestionController::class . ':isMyFollowing');
    $this->put(   '/user/questions/{question_id:\d+}/following',                QuestionController::class . ':addFollow');
    $this->delete('/user/questions/{question_id:\d+}/following',                QuestionController::class . ':deleteFollow');
    $this->get(   '/questions/{question_id:\d+}/followers',                     QuestionController::class . ':getFollowers');

    // 问题的评论
    $this->get(   '/questions/{question_id:\d+}/comments',                 QuestionController::class . ':getComments');
    $this->post(  '/questions/{question_id:\d+}/comments',                 QuestionController::class . ':createComment');

    // 回答
    $this->get(   '/questions/{question_id:\d+}/answers',                  AnswerController::class . ':getListByQuestionId');
    $this->post(  '/questions/{question_id:\d+}/answers',                  AnswerController::class . ':create');
    $this->get(   '/answers',                                              AnswerController::class . ':getList');
    $this->get(   '/answers/{answer_id:\d+}',                              AnswerController::class . ':getDetail');

    // 回答的评论
    $this->get(   '/answers/{answer_id:\d+}/comments',                     AnswerController::class . ':getComments');
    $this->post(  '/answers/{answer_id:\d+}/comments',                     AnswerController::class . ':createComment');

    // 文章

    // 文章关注
    $this->get(   '/users/{user_id:\d+}/articles/following',                     ArticleController::class . ':getFollowing');
    $this->get(   '/users/{user_id:\d+}/articles/{article_id:\d+}/following',    ArticleController::class . ':isFollowing');
    $this->get(   '/user/articles/following',                                    ArticleController::class . ':getMyFollowing');
    $this->get(   '/user/articles/{article_id:\d+}/following',                   ArticleController::class . ':isMyFollowing');
    $this->put(   '/user/articles/{article_id:\d+}/following',                   ArticleController::class . ':addFollow');
    $this->delete('/user/articles/{article_id:\d+}/following',                   ArticleController::class . ':deleteFollow');
    $this->get(   '/articles/{article_id:\d+}/followers',                        ArticleController::class . ':getFollowers');

    // 文章的评论
    $this->get(   '/articles/{article_id:\d+}/comments',                   ArticleController::class . ':getComments');
    $this->post(  '/articles/{article_id:\d+}/comments',                   ArticleController::class . ':createComment');

    // 私信

    // 通知

    // 验证码
    $this->post(  '/captcha',                     CaptchaController::class . ':create');

    // 邮件
    $this->post(  '/email',                       EmailController::class . ':send');

    // 图片
    $this->post(  '/images',                      ImageController::class . ':upload');
    $this->patch( '/images/{image_id:\d+}',       ImageController::class . ':update');
})
    ->add(function (ServerRequestInterface $request, ResponseInterface $response, callable $next) {
        /** @var ResponseInterface $response */
        $response = $next($request, $response);

        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'Token, Origin, X-Requested-With, Content-Type, Accept, Connection, User-Agent, Cookie')
            ->withHeader('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PATCH, DELETE');
    });

// admin
$app->group('/admin', function () {
    $this->get('', AdminController::class . ':pageIndex');
});
