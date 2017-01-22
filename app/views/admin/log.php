<?php import('app/views/admin/header.php') ?>

        <h3><?php h($_view['title']) ?></h3>

        <table summary="ユーザ一覧">
            <thead>
                <tr>
                    <th>日時</th>
                    <th>ユーザ名</th>
                    <th>管理者名</th>
                    <th>IPアドレス</th>
                    <th>環境</th>
                    <th>ログ</th>
                    <th>ページ</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>日時</th>
                    <th>ユーザ名</th>
                    <th>管理者名</th>
                    <th>IPアドレス</th>
                    <th>環境</th>
                    <th>ログ</th>
                    <th>ページ</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ($_view['logs'] as $log) : list($environment, $browser, $os) = environment_useragent($log['agent']) ?>
                <tr>
                    <td><?php h(localdate('Y/m/d H:i', $log['created'])) ?></td>
                    <td><?php h($log['user_username']) ?></td>
                    <td><?php h($log['administrator']) ?></td>
                    <td><?php h($log['ip']) ?></td>
                    <td><span title="<?php t($log['agent']) ?>"><?php h($environment ? $environment : '-') ?></span></td>
                    <td>
                        <?php if ($log['model'] && $log['exec']) : ?>
                            <?php h($log['model']) ?>テーブルに対して<?php h($log['exec']) ?>しました。
                        <?php endif ?>
                        <?php h($log['message']) ?>
                    </td>
                    <td><?php h($log['page']) ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <?php if ($_view['log_page'] > 1) : ?>
            <h3>ページ移動</h3>
            <ul>
                <li><?php if ($_GET['page'] > 1) : ?><a href="<?php t(MAIN_FILE) ?>/admin/log?page=<?php t($_GET['page'] - 1) ?>">前のページ</a><?php else : ?>前のページ<?php endif ?></li>
                <li><?php if ($_view['log_page'] > $_GET['page']) : ?><a href="<?php t(MAIN_FILE) ?>/admin/log?page=<?php t($_GET['page'] + 1) ?>">次のページ</a><?php else : ?>次のページ<?php endif ?></li>
            </ul>
            <ul>
                <?php for ($i = 1; $i <= $_view['log_page']; $i++) : ?>
                <li><?php if ($i !== $_GET['page']) : ?><a href="<?php t(MAIN_FILE) ?>/admin/log?page=<?php t($i) ?>"><?php t($i) ?></a><?php else : ?><?php t($i) ?><?php endif ?></li>
                <?php endfor ?>
            </ul>
            <p><?php e($_view['log_pager']) ?></p>
        <?php endif ?>

<?php import('app/views/admin/footer.php') ?>
