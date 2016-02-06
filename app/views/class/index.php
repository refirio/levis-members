<?php import('app/views/header.php') ?>

        <h2><?php h($view['class']['name']) ?></h2>
        <?php if ($view['class']['memo']) : ?>
            <p><?php h($view['class']['memo']) ?></p>
        <?php endif ?>
        <?php if ($view['class']['image_01'] || $view['class']['image_02']) : ?>
            <p>
                <?php if ($view['class']['image_01']) : ?>
                <a href="<?php t($GLOBALS['http_path'] . $GLOBALS['file_targets']['class'] . $view['class']['id'] . '/' . $view['class']['image_01']) ?>" class="image"><img src="<?php t($GLOBALS['http_path'] . $GLOBALS['file_targets']['class'] . $view['class']['id'] . '/thumbnail_' . $view['class']['image_01']) ?>" alt="画像1" /></a>
                <?php endif ?>
                <?php if ($view['class']['image_02']) : ?>
                <a href="<?php t($GLOBALS['http_path'] . $GLOBALS['file_targets']['class'] . $view['class']['id'] . '/' . $view['class']['image_02']) ?>" class="image"><img src="<?php t($GLOBALS['http_path'] . $GLOBALS['file_targets']['class'] . $view['class']['id'] . '/thumbnail_' . $view['class']['image_02']) ?>" alt="画像2" /></a>
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
                <?php foreach ($view['members'] as $member) : ?>
                <tr>
                    <td><?php h($member['name']) ?></td>
                    <td><?php h($member['name_kana']) ?></td>
                    <td><?php h($GLOBALS['options']['member']['grades'][$member['grade']]) ?></td>
                    <td><?php h(localdate('Y年m月d日', $member['birthday'])) ?></td>
                    <td><?php h($member['email']) ?></td>
                    <td><?php h($member['tel']) ?></td>
                    <td>
                        <?php if ($member['image_01']) : ?>
                        <a href="<?php t($GLOBALS['http_path'] . $GLOBALS['file_targets']['member'] . $member['id'] . '/' . $member['image_01']) ?>" class="image">画像1</a>
                        <?php endif ?>
                        <?php if ($member['image_02']) : ?>
                        <a href="<?php t($GLOBALS['http_path'] . $GLOBALS['file_targets']['member'] . $member['id'] . '/' . $member['image_02']) ?>" class="image">画像2</a>
                        <?php endif ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <h3>ページ移動</h3>
        <ul>
            <li><?php if ($_GET['page'] > 1) : ?><a href="<?php t(MAIN_FILE) ?>/class/<?php t($params[1]) ?>?page=<?php t($_GET['page'] - 1) ?>">前のページ</a><?php else : ?>前のページ<?php endif ?></li>
            <li><?php if ($view['member_page'] > $_GET['page']) : ?><a href="<?php t(MAIN_FILE) ?>/class/<?php t($params[1]) ?>?page=<?php t($_GET['page'] + 1) ?>">次のページ</a><?php else : ?>次のページ<?php endif ?></li>
        </ul>
        <ul>
            <?php for ($i = 1; $i <= $view['member_page']; $i++) : ?>
            <li><?php if ($i != $_GET['page']) : ?><a href="<?php t(MAIN_FILE) ?>/class/<?php t($params[1]) ?>?page=<?php t($i) ?>"><?php t($i) ?></a><?php else : ?><?php t($i) ?><?php endif ?></li>
            <?php endfor ?>
        </ul>

<?php import('app/views/footer.php') ?>
