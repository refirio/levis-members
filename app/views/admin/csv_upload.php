<?php import('app/views/admin/header.php') ?>

        <h3><?php h($view['title']) ?></h3>

        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/csv_download">CSVダウンロード</a>から入手した形式のファイルを、アップロードして一括登録します。</li>
            <li>画像ファイルは <code><?php h($GLOBALS['config']['file_targets']['member']) ?></code> 内にIDごとのディレクトリを作成し、その中に別途アップロードします。</li>
        </ul>
        <?php if (isset($_GET['ok'])) : ?>
        <ul class="ok">
            <?php if ($_GET['ok'] === 'post') : ?>
            <li>データを登録しました。</li>
            <?php endif ?>
        </ul>
        <?php elseif (isset($view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/admin/csv_upload" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>アップロードフォーム</legend>
                <input type="hidden" name="token" value="<?php t($view['token']) ?>" class="token" />
                <dl>
                    <dt>対象</dt>
                        <dd><input type="file" name="file" size="30" /></dd>
                    <dt>操作内容</dt>
                        <dd>
                            <ul>
                                <li><label><input type="radio" name="operation" value="insert" checked="checked" /> 登録のみ</label></li>
                                <li><label><input type="radio" name="operation" value="update" /> 更新のみ</label></li>
                                <li><label><input type="radio" name="operation" value="replace" /> 入れ替え</label></li>
                            </ul>
                        </dd>
                </dl>
                <p><input type="submit" value="アップロードする" /></p>
            </fieldset>
        </form>

<?php import('app/views/admin/footer.php') ?>
