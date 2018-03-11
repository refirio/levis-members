<?php import('app/views/header.php') ?>

        <h2><?php h($_view['title']) ?></h2>

        <?php if (isset($_view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($_view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/user/twostep" method="post" class="register validate">
            <fieldset>
                <legend>設定フォーム</legend>
                <input type="hidden" name="_token" value="<?php t($_view['token']) ?>" class="token">
                <dl>
                    <dt>2段階認証</dt>
                        <dd>
                            <select name="twostep">
                                <?php foreach ($GLOBALS['config']['options']['user']['twosteps'] as $key => $value) : ?>
                                <option value="<?php t($key) ?>"<?php $key == $_view['user']['twostep'] ? e(' selected="selected"') : '' ?>><?php t($value) ?></option>
                                <?php endforeach ?>
                            </select>
                        </dd>
                    <dt>メールアドレス</dt>
                        <dd>
                            <div id="validate_twostep_email">
                                <input type="text" name="twostep_email[account]" size="10" value="<?php t($_view['user']['twostep_email']['account']) ?>">
                                @
                                <select name="twostep_email[domain]">
                                    <option value=""></option>
                                    <?php foreach ($GLOBALS['config']['carriers'] as $carrier) : ?>
                                    <option value="<?php t($carrier) ?>"<?php $carrier === $_view['user']['twostep_email']['domain'] ? e(' selected="selected"') : '' ?>><?php t($carrier) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </dd>
                </dl>
                <p><input type="submit" value="設定する"></p>
            </fieldset>
        </form>

<?php import('app/views/footer.php') ?>
