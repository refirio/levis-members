<?php import('app/views/header.php') ?>

        <h2><?php h($_view['class']['name']) ?></h2>
        <?php if ($_view['class']['memo']) : ?>
            <p><?php h($_view['class']['memo']) ?></p>
        <?php endif ?>
        <?php if ($_view['class']['image_01'] || $_view['class']['image_02']) : ?>
            <p>
                <?php if ($_view['class']['image_01']) : ?>
                <a href="<?php t($GLOBALS['config']['http_path'] . $GLOBALS['config']['file_targets']['class'] . $_view['class']['id'] . '/' . $_view['class']['image_01']) ?>" class="image"><img src="<?php t($GLOBALS['config']['http_path'] . $GLOBALS['config']['file_targets']['class'] . $_view['class']['id'] . '/thumbnail_' . $_view['class']['image_01']) ?>" alt="画像1" /></a>
                <?php endif ?>
                <?php if ($_view['class']['image_02']) : ?>
                <a href="<?php t($GLOBALS['config']['http_path'] . $GLOBALS['config']['file_targets']['class'] . $_view['class']['id'] . '/' . $_view['class']['image_02']) ?>" class="image"><img src="<?php t($GLOBALS['config']['http_path'] . $GLOBALS['config']['file_targets']['class'] . $_view['class']['id'] . '/thumbnail_' . $_view['class']['image_02']) ?>" alt="画像2" /></a>
                <?php endif ?>
            </p>
        <?php endif ?>
        <table summary="名簿一覧">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>名前（フリガナ）</th>
                    <th>成績</th>
                    <th>生年月日</th>
                    <th>メールアドレス</th>
                    <th>電話番号</th>
                    <th>画像</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>名前</th>
                    <th>名前（フリガナ）</th>
                    <th>成績</th>
                    <th>生年月日</th>
                    <th>メールアドレス</th>
                    <th>電話番号</th>
                    <th>画像</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ($_view['members'] as $member) : ?>
                <tr>
                    <td><?php h($member['name']) ?></td>
                    <td><?php h($member['name_kana']) ?></td>
                    <td><?php h($GLOBALS['config']['options']['member']['grades'][$member['grade']]) ?></td>
                    <td><?php h(localdate('Y年m月d日', $member['birthday'])) ?></td>
                    <td><?php h($member['email']) ?></td>
                    <td><?php h($member['tel']) ?></td>
                    <td>
                        <?php if ($member['image_01']) : ?>
                        <a href="<?php t($GLOBALS['config']['http_path'] . $GLOBALS['config']['file_targets']['member'] . $member['id'] . '/' . $member['image_01']) ?>" class="image">画像1</a>
                        <?php endif ?>
                        <?php if ($member['image_02']) : ?>
                        <a href="<?php t($GLOBALS['config']['http_path'] . $GLOBALS['config']['file_targets']['member'] . $member['id'] . '/' . $member['image_02']) ?>" class="image">画像2</a>
                        <?php endif ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <h3>ページ移動</h3>
        <ul>
            <li><?php if ($_GET['page'] > 1) : ?><a href="<?php t(MAIN_FILE) ?>/class/<?php t($_params[1]) ?>?page=<?php t($_GET['page'] - 1) ?>">前のページ</a><?php else : ?>前のページ<?php endif ?></li>
            <li><?php if ($_view['member_page'] > $_GET['page']) : ?><a href="<?php t(MAIN_FILE) ?>/class/<?php t($_params[1]) ?>?page=<?php t($_GET['page'] + 1) ?>">次のページ</a><?php else : ?>次のページ<?php endif ?></li>
        </ul>
        <ul>
            <?php for ($i = 1; $i <= $_view['member_page']; $i++) : ?>
            <li><?php if ($i !== $_GET['page']) : ?><a href="<?php t(MAIN_FILE) ?>/class/<?php t($_params[1]) ?>?page=<?php t($i) ?>"><?php t($i) ?></a><?php else : ?><?php t($i) ?><?php endif ?></li>
            <?php endfor ?>
        </ul>

<?php import('app/views/footer.php') ?>
