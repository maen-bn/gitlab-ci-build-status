# gitlab-ci-build-status
[![build status](https://travis-ci.org/maen-bn/gitlab-ci-build-status.svg?branch=master)](https://travis-ci.org/maen-bn/gitlab-ci-build-status)
[![Build Status](https://scrutinizer-ci.com/g/maen-bn/gitlab-ci-build-status/badges/build.png?b=master)](https://scrutinizer-ci.com/g/maen-bn/gitlab-ci-build-status/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/maen-bn/gitlab-ci-build-status/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/maen-bn/gitlab-ci-build-status/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/maen-bn/gitlab-ci-build-status/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/maen-bn/gitlab-ci-build-status/?branch=master)

Simple tool to check latest commit build from GitLab CI

## Installation

The tool requires you have [PHP](https://php.net) 5.4.* + and [Composer](https://getcomposer.org).

The get the latest version of gitlab-ci-build-status, add the following line to your `composer.json` file:
```
"maenbn/gitlab-ci-build-status": "dev-master"
```
Then run `composer install` or `composer update` to install.

## Usage

Setting up the client requires your Gitlab CI URL, Gitlab CI project ID, and Gitlab CI project token:
```php
$client = new \Maenbn\GitlabCiBuildStatus\Client('https://gitlab.example.com/api/v3' 'project_id', 'my_private_token');
```
Then you can grab the status. You can also specify a branch (defaults to master).
```php
$status = $client->getStatus('my_current_patch');
```