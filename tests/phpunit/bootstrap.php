<?php
/**
 * Pavlakis Slim CLI Request
 *
 * @link        https://github.com/pavlakis/slim-cli
 * @copyright   Copyright © 2017 Antonis Pavlakis
 * @author      Antonios Pavlakis
 * @author      Bobby DeVeaux (@bobbyjason) Based on Bobby's code from: https://github.com/dvomedia/gulp-skeleton/blob/master/web/index.php
 * @license     https://github.com/pavlakis/slim-cli/blob/master/LICENSE (BSD 3-Clause License)
 */
$autoloader = require __DIR__ . '/../../vendor/autoload.php';
$autoloader->add('pavlakis\\cli\\tests\\', __DIR__);