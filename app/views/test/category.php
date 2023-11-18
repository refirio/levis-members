<!DOCTYPE html>
<html>
    <head>
        <title>記事</title>
    </head>
    <body>
        <ul>
            <?php foreach ($_view['categories'] as $category) : ?>
            <li><?php h($category['name']) ?></li>
            <?php endforeach ?>
        </ul>
    </body>
</html>
