<?php

use Service\Container;

require __DIR__.'/bootstrap.php';

$container = new Container($configuration);
$pdo = $container->getPDO();
$comment = $container->getComment();
$unapprovedComments = $comment->getAllUnapprovedComments();

include 'header.php';

?>
<div class="container">
    <?php
    if ($unapprovedComments) {
    ?>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Text</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (count($unapprovedComments) > 1) {
            ?>
                <div class="col-sm-12 text-right">
                    <a href="approve_deny_comment.php?deny_all=true">
                        <button class="btn btn-danger">
                            Deny All
                        </button>
                    </a>
                </div>
                &nbsp;
                <div class="col-sm-12  text-right">
                    <a href="approve_deny_comment.php?approve_all=true">
                        <button class="btn btn-success">
                            Approve All
                        </button>
                    </a>
                </div>
            <?php
            }
            foreach ($unapprovedComments as $unapprovedComment) {
            ?>
                <tr>
                    <td><?php echo $unapprovedComment->name;?></td>
                    <td><?php echo $unapprovedComment->email;?></td>
                    <td><?php echo $unapprovedComment->text;?></td>
                    <td>

                        <div class="col-sm-6">
                            <a href="approve_deny_comment.php?approve_single_id=<?php echo $unapprovedComment->id; ?>">Approve</a>
                        </div>
                        <div class="col-sm-6">
                            <a href="approve_deny_comment.php?deny_single_id=<?php echo $unapprovedComment->id; ?>">Deny</a>
                        </div>

                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    <?php
    } else {
    ?>
        <div class="well">There are no comments for approval at this moment.</div>
    <?php
    }
    ?>
</div>

