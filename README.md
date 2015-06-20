# modx.org

This repository contains the MODX.org website. Currently only contains a Slack invite form, but who knows, maybe there'll be more in the future!

## Setting up

This site is built with Slim (v2.6) and composer. Run `composer install` in the root of the repository to load the dependencies and autoloader.

Copy `environment.sample.php` to `environment.php` and adjust the environment variables to suit your environment. 

Make sure your vhost goes into the /www/ directory and _not_ the root of the project. 

That should do the trick to get it up and running!