<?php

use Service\Container;

error_reporting(E_ALL);
ini_set("display_startup_errors","1");
ini_set("display_errors","1");

require __DIR__.'/bootstrap.php';

$container = new Container($configuration);
$pdo = $container->getPDO();
$comment = $container->getComment();

$requestString = array_keys($_GET)[0];


switch($requestString) {
    case 'approve_single_id':
        try{

            $comment->approveSingleComment(intval($_REQUEST['approve_single_id']));
        } catch (Exception $e) {
            echo $e;
        }
        break;
    case 'deny_single_id':
        try{
            $comment->denySingleComment(intval($_REQUEST['deny_single_id']));
        } catch (Exception $e) {
            echo $e;
        }
        break;
    case 'approve_all':
        try{

            $comment->approveAllComments();
        } catch (Exception $e) {
            echo $e;
        }
        break;
    case 'deny_all':
        try{

            $comment->denyAllComments();
        } catch (Exception $e) {
            echo $e;
        }
        break;
}

header('Location: /index.php');
die;
