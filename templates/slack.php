<?php require "_header.php" ?>

<div class="large-8 columns push-2 main-wrapper">
    <section role="main">
        <h1>Chat about MODX on <a href="https://slack.com/">Slack!</a></h1>

        <div class="row">
            <div class="large-9 no-left-padding columns">
                <p>Want to chat about MODX?
                    Need help with something, or simply want to bounce off ideas?
                    Fill in your email below for an instant invite to the MODX Community Slack team!</p>

                <?php
                $msg = $flash['invite'];
                if (!empty($msg)): ?>
                <div class="alert-box"><?php echo $msg; ?></div>
                <?php endif ?>
                <form method="post" action="/slack/invite">
                    <input type="hidden" name="send_invite" value="1">
                    <input type="email" name="email" placeholder="my@email.com">

                    <button type="submit">Send me an Invite!</button>
                </form>
            </div>
            <div class="large-3 no-right-padding columns">
                <img src="assets/img/modx-stacked-grey.svg" alt="MODX Community" width="100%">
            </div>
        </div>

        <hr>

        <h2 class="subheader"><?php echo $total; ?> MODXers are already signed up!</h2>


        <ul class="avatars">
            <?php foreach ($users as $user) : ?>
            <li class="<?php echo 'avatar' . ($user['active'] ? ' active' : ''); ?>">
                <img width="72" height="72" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="
        data-src="<?php echo $user['image']; ?>" alt="<?php echo $user['name']; ?>">
            </li>
            <?php endforeach ?>
        </ul>
    </section>
</div>

<?php require "_footer.php" ?>
