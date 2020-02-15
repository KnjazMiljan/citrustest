<?php

use Service\Container;

require __DIR__.'/bootstrap.php';

$container = new Container($configuration);
$pdo = $container->getPDO();
$product = $container->getProduct();
$comment = $container->getComment();
$products = $product->getAll();
$comments = $comment->getAllApprovedComments();

$errorMessage = '';
$successMessage = '';

if (isset($_GET['error'])) {
    $errorMessage = 'Missing form data for the comment.';
}

if (isset($_GET['success'])) {
    $successMessage = 'Your comment is submitted and is awaiting for approval.';
}

?>
<?php
include 'header.php';
    if ($errorMessage) {
?>
        <div class="text-danger bg-danger well">
            <?php echo $errorMessage; ?>
        </div>
<?php
    }
    if($successMessage) {
?>
        <div class="well text-success bg-success">
            <?php echo $successMessage; ?>
        </div>
<?php
    }
?>
<main role="main">
    <div class="album py-5 bg-light">
        <div class="container">

            <div class="row">
                <?php
                    foreach ($products as $product) {
                ?>
                    <div class="col-md-4 col-sm-4 py-1">
                        <div class="card mb-4 box-shadow">
                            <img class="card-img-top" style="max-height: 225px;height: 225px; width: 250px; display: block;" src="<?php echo $product->image_path; ?>">
                            <div class="card-body">
                                <p class="card-title">
                                    <?php echo $product->title; ?>
                                </p>
                                <p class="card-text" style="overflow: hidden; max-height: 18px;">
                                    <?php echo $product->description; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</main>
<section class="jumbotron text-left">
    <div class="container">
        <h2 class="jumbotron-heading">Comments</h2>
        <div class="well">
            <?php
                if($comments) {
                    foreach ($comments as $comment) {
            ?>
                        <div class="card">
                            <div class="card-body">
                                <blockquote class="blockquote mb-0">
                                    <p><?php echo $comment->text; ?></p>
                                    <footer class="blockquote-footer"><?php echo '[' . date('H:i:s d.m.Y.', strtotime($comment->created_at)) . '] ' . $comment->name; ?></footer>
                                </blockquote>
                            </div>
                        </div>
                        <hr>
                <?php
                    }
                } else {
            ?>
                <div class="card">
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p>No comments yet!</p>
                        </blockquote>
                    </div>
                </div>
            <?php
                }
            ?>
        </div>

        <div class="well">
            <h2>
                Submit a comment:
            </h2>
            <form method="POST" action="create_new_comment.php">
                <div class="form-group">
                    <label for="inputEmail">
                        Email address:
                    </label>
                    <input
                        type="email"
                        class="form-control"
                        id="inputEmail" name="email"
                        placeholder="Enter Your email here..."
                        required
                    >
                    <small id="emailHelp" class="form-text text-muted">
                        We'll never share your email with anyone else.
                    </small>
                </div>
                <div class="form-group">
                    <label for="inputName">
                        Name:
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="inputName"
                        name="name"
                        placeholder="Enter Your name here..."
                        required
                    >
                </div>
                <div class="form-group">
                    <label for="commentText">
                        Comment text:
                    </label>
                    <textarea
                        class="form-control"
                        id="commentText"
                        placeholder="Enter Your comment here..."
                        name="text"
                        rows="3"
                        required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

    </div>
</section>
</body>
</html>

