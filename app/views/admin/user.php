<?php import('app/views/admin/header.php') ?>

        <h3><?php h($_view['title']) ?></h3>

        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/user_form">ユーザ登録</a></li>
        </ul>
        <?php if (isset($_GET['ok'])) : ?>
        <ul class="ok">
            <?php if ($_GET['ok'] === 'post') : ?>
            <li>データを登録しました。</li>
            <?php elseif ($_GET['ok'] === 'delete') : ?>
            <li>データを削除しました。</li>
            <?php endif ?>
        </ul>
        <?php elseif (isset($_GET['warning'])) : ?>
        <ul class="warning">
            <?php if ($_GET['warning'] === 'delete') : ?>
            <li>データが選択されていません。</li>
            <?php endif ?>
        </ul>
        <?php endif ?>

        <table summary="ユーザ一覧">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ユーザ名</th>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>最終ログイン日時</th>
                    <th>作業</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>ユーザ名</th>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>最終ログイン日時</th>
                    <th>作業</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ($_view['users'] as $user) : ?>
                <tr>
                    <td><?php h($user['id']) ?></td\>
                    <td><?php h($user['username']) ?></td>
                    <td><?php h($user['profile_name']) ?></td>
                    <td><?php h($user['email']) ?><?php if ($user['email_verified'] == 0) : ?>（存在未確認）<?php endif ?></td>
                    <td><?php h($user['loggedin'] ? localdate('Y/m/d H:i', $user['loggedin']) : '-') ?></td>
                    <td><a href="<?php t(MAIN_FILE) ?>/admin/user_form?id=<?php t($user['id']) ?>">編集</a></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <?php if ($_view['user_page'] > 1) : ?>
            <h3>ページ移動</h3>
            <ul>
                <li><?php if ($_GET['page'] > 1) : ?><a href="<?php t(MAIN_FILE) ?>/admin/user?page=<?php t($_GET['page'] - 1) ?>">前のページ</a><?php else : ?>前のページ<?php endif ?></li>
                <li><?php if ($_view['user_page'] > $_GET['page']) : ?><a href="<?php t(MAIN_FILE) ?>/admin/user?page=<?php t($_GET['page'] + 1) ?>">次のページ</a><?php else : ?>次のページ<?php endif ?></li>
            </ul>
            <ul>
                <?php for ($i = 1; $i <= $_view['user_page']; $i++) : ?>
                <li><?php if ($i !== $_GET['page']) : ?><a href="<?php t(MAIN_FILE) ?>/admin/user?page=<?php t($i) ?>"><?php t($i) ?></a><?php else : ?><?php t($i) ?><?php endif ?></li>
                <?php endfor ?>
            </ul>
            <p><?php e($_view['user_pager']) ?></p>
        <?php endif ?>

<?php import('app/views/admin/footer.php') ?>
