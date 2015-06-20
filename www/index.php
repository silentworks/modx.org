<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/environment.php';
session_cache_limiter(false);
session_name($_ENV['SESSION_NAME']);
session_start();

$app = new \Slim\Slim([
    'templates.path' => '../templates'
]);

// Environment specific settings
$app->configureMode('production', function () use ($app) {
    $app->config([
        'log.enable' => true,
        'debug' => false
    ]);
});
$app->configureMode('development', function () use ($app) {
    $app->config([
        'debug' => true
    ]);
});

// Hook up the Slack instance
$app->container->singleton('slack', function () {
    return new Slack($_ENV['SLACK_TEAM'], $_ENV['SLACK_TOKEN']);
});


// The home page here
$app->get('/', function () use ($app) {
    // Output the header
    $app->render('_header.php');

    $info = $app->slack->getInfo();
    $_users = $info['users'];
    $users = array();
    foreach ($_users as $user) {
        if ($user['is_bot'] === true) {
            continue;
        }
        if ($user['id'] === 'USLACKBOT') {
            continue;
        }

        $users[] = [
            'id' => $user['id'],
            'name' => !empty($user['real_name']) ? $user['real_name'] : $user['name'],
            'active' => $user['presence'] === 'active',
            'image' => $user['profile']['image_72'],
        ];
    }

    // Sort users by active first
    uasort($users, function ($a, $b) {
        if ($a['active'] && $b['active']) {
            return 0;
        }
        if ($a['active']) {
            return -1;
        }
        if ($b['active']) {
            return 1;
        }

        return 0;
    });
    // Render the page
    $app->render('slack.php', [
        'users' => $users,
        'total' => count($users),
    ]);

    // Output the footer
    $app->render('_footer.php');
})->name('slack');

// POSTing to /slack/invite will send an invite for the requested email address
$app->post('/slack/invite', function () use ($app) {
    // Grab the email from the POST
    $email = $app->request->post('email');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $app->flash('invite', 'Please enter a valid email address.');
        $app->redirect('/');
    }

    // Send the slack invite
    $invited = $app->slack->invite($email);
    if ($invited['ok'] === true) {
        $app->flash('invite', 'Your invitation has been sent! Be sure to check your email for further instructions. ');
    } else {
        if ($invited['error'] === 'sent_recently') {
            $app->flash('invite', 'Oops, you already received an invite recently! If you didn\'t get the email, make sure to check your spam folder too.');
        } elseif ($invited['error'] === 'already_invited') {
            $app->flash('invite', 'Oops, you were already invited before!');
        } elseif ($invited['error'] === 'already_in_team') {
            $app->flash('invite', 'Hey, you are already in the Slack team! Head over to modxcommunity.slack.com if you forgot your details.');
        } else {
            $app->flash('invite', 'Oops, something went wrong: ' . json_encode($invited));
        }
    }

    // Redirect back to the home page; the flash will show a success/error message there.
    $app->redirect('/');
})->name('slack/invite');

// Run the app
$app->run();
