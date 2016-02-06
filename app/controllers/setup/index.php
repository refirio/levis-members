<?php

if (DATABASE_TYPE == 'pdo_mysql' || DATABASE_TYPE == 'mysql') {
    //MySQL用のテーブルを作成
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'users(
            id             INT UNSIGNED        NOT NULL AUTO_INCREMENT COMMENT \'代理キー\',
            created        DATETIME            NOT NULL                COMMENT \'作成日時\',
            modified       DATETIME            NOT NULL                COMMENT \'更新日時\',
            deleted        DATETIME                                    COMMENT \'削除日時\',
            username       VARCHAR(80)         NOT NULL UNIQUE         COMMENT \'ユーザ名\',
            password       VARCHAR(80)         NOT NULL                COMMENT \'パスワード\',
            password_salt  VARCHAR(80)         NOT NULL UNIQUE         COMMENT \'パスワードのソルト\',
            name           VARCHAR(255)        NOT NULL                COMMENT \'名前\',
            email          VARCHAR(255)        NOT NULL UNIQUE         COMMENT \'メールアドレス\',
            memo           TEXT                                        COMMENT \'メモ\',
            loggedin       DATETIME                                    COMMENT \'最終ログイン日時\',
            failed         INT UNSIGNED                                COMMENT \'ログイン失敗回数\',
            failed_last    DATETIME                                    COMMENT \'最終ログイン失敗日時\',
            token          VARCHAR(255)                                COMMENT \'パスワード再発行用のトークン\',
            token_code     VARCHAR(80)                                 COMMENT \'パスワード再発行用のコード\',
            token_expire   DATETIME                                    COMMENT \'パスワード再発行用のURL有効期限\',
            twostep        TINYINT(1) UNSIGNED NOT NULL                COMMENT \'二段階認証の利用\',
            twostep_email  VARCHAR(255)                                COMMENT \'二段階認証用のメールアドレス\',
            twostep_code   VARCHAR(80)                                 COMMENT \'二段階認証用のコード\',
            twostep_expire DATETIME                                    COMMENT \'二段階認証用のコード有効期限\',
            PRIMARY KEY(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT \'ユーザ\';
    ');
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'sessions(
            id       VARCHAR(255)        NOT NULL COMMENT \'セッションID\',
            created  DATETIME            NOT NULL COMMENT \'作成日時\',
            modified DATETIME            NOT NULL COMMENT \'更新日時\',
            user_id  INT UNSIGNED        NOT NULL COMMENT \'外部キー ユーザ\',
            agent    VARCHAR(255)        NOT NULL COMMENT \'ユーザエージェント\',
            keep     TINYINT(1) UNSIGNED NOT NULL COMMENT \'ログイン状態の保持\',
            twostep  TINYINT(1) UNSIGNED NOT NULL COMMENT \'二段階認証の利用\',
            expire   DATETIME            NOT NULL COMMENT \'セッションの有効期限\',
            PRIMARY KEY(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT \'セッション\';
    ');
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'classes(
            id       INT UNSIGNED        NOT NULL AUTO_INCREMENT COMMENT \'代理キー\',
            created  DATETIME            NOT NULL                COMMENT \'作成日時\',
            modified DATETIME            NOT NULL                COMMENT \'更新日時\',
            deleted  DATETIME                                    COMMENT \'削除日時\',
            code     VARCHAR(80)         NOT NULL UNIQUE         COMMENT \'コード\',
            name     VARCHAR(255)        NOT NULL                COMMENT \'名前\',
            memo     TEXT                                        COMMENT \'メモ\',
            image_01 VARCHAR(80)                                 COMMENT \'画像1\',
            image_02 VARCHAR(80)                                 COMMENT \'画像2\',
            document VARCHAR(80)                                 COMMENT \'資料\',
            sort     INT UNSIGNED        NOT NULL                COMMENT \'並び順\',
            PRIMARY KEY(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT \'教室\';
    ');
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'members(
            id        INT UNSIGNED        NOT NULL AUTO_INCREMENT COMMENT \'代理キー\',
            created   DATETIME            NOT NULL                COMMENT \'作成日時\',
            modified  DATETIME            NOT NULL                COMMENT \'更新日時\',
            deleted   DATETIME                                    COMMENT \'削除日時\',
            class_id  INT UNSIGNED        NOT NULL                COMMENT \'外部キー 教室\',
            name      VARCHAR(255)        NOT NULL                COMMENT \'名前\',
            name_kana VARCHAR(255)        NOT NULL                COMMENT \'名前のフリガナ\',
            grade     INT UNSIGNED        NOT NULL                COMMENT \'成績\',
            birthday  DATE                                        COMMENT \'生年月日\',
            email     VARCHAR(255)                                COMMENT \'メールアドレス\',
            tel       VARCHAR(255)                                COMMENT \'電話番号\',
            memo      TEXT                                        COMMENT \'メモ\',
            image_01  VARCHAR(80)                                 COMMENT \'画像1\',
            image_02  VARCHAR(80)                                 COMMENT \'画像2\',
            public    TINYINT(1) UNSIGNED NOT NULL                COMMENT \'公開\',
            PRIMARY KEY(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT \'名簿\';
    ');
} elseif (DATABASE_TYPE == 'pdo_pgsql' || DATABASE_TYPE == 'pgsql') {
    //PostgreSQL用のテーブルを作成
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'users(
            id             SERIAL        NOT NULL,
            created        TIMESTAMP     NOT NULL,
            modified       TIMESTAMP     NOT NULL,
            deleted        TIMESTAMP,
            username       VARCHAR(80)   NOT NULL UNIQUE,
            password       VARCHAR(80)   NOT NULL,
            password_salt  VARCHAR(80)   NOT NULL UNIQUE,
            session        VARCHAR(255)           UNIQUE,
            session_expire TIMESTAMP,
            name           VARCHAR(255)  NOT NULL,
            email          VARCHAR(255)  NOT NULL UNIQUE,
            memo           TEXT,
            loggedin       TIMESTAMP,
            failed         INTEGER,
            failed_last    TIMESTAMP,
            token          VARCHAR(255),
            token_code     VARCHAR(80),
            token_expire   TIMESTAMP,
            twostep        BOOLEAN       NOT NULL,
            twostep_email  VARCHAR(255),
            twostep_code   VARCHAR(80),
            twostep_expire TIMESTAMP,
            PRIMARY KEY(id)
        );
    ');
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'sessions(
            id       VARCHAR(255) NOT NULL,
            created  TIMESTAMP    NOT NULL,
            modified TIMESTAMP    NOT NULL,
            user_id  INT UNSIGNED NOT NULL,
            agent    VARCHAR(255) NOT NULL,
            keep     BOOLEAN      NOT NULL,
            twostep  BOOLEAN      NOT NULL,
            expire   TIMESTAMP    NOT NULL,
            PRIMARY KEY(id)
        );
    ');
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'classes(
            id       SERIAL       NOT NULL,
            created  TIMESTAMP    NOT NULL,
            modified TIMESTAMP    NOT NULL,
            deleted  TIMESTAMP,
            code     VARCHAR(80)  NOT NULL UNIQUE,
            name     VARCHAR(255) NOT NULL,
            memo     TEXT,
            image_01 VARCHAR(80),
            image_02 VARCHAR(80),
            document VARCHAR(80),
            sort     INTEGER      NOT NULL,
            PRIMARY KEY(id)
        );
    ');
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'members(
            id        SERIAL       NOT NULL,
            created   TIMESTAMP    NOT NULL,
            modified  TIMESTAMP    NOT NULL,
            deleted   TIMESTAMP,
            class_id  INTEGER      NOT NULL,
            name      VARCHAR(255) NOT NULL,
            name_kana VARCHAR(255) NOT NULL,
            grade     INTEGER      NOT NULL,
            birthday  DATE,
            email     VARCHAR(255),
            tel       VARCHAR(255),
            memo      TEXT,
            image_01  VARCHAR(80),
            image_02  VARCHAR(80),
            public    BOOLEAN      NOT NULL,
            PRIMARY KEY(id)
        );
    ');
} else {
    //SQLite用のテーブルを作成
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'users(
            id             INTEGER,
            created        DATETIME         NOT NULL,
            modified       DATETIME         NOT NULL,
            deleted        DATETIME,
            username       VARCHAR          NOT NULL UNIQUE,
            password       VARCHAR          NOT NULL,
            password_salt  VARCHAR          NOT NULL UNIQUE,
            session        VARCHAR                   UNIQUE,
            session_expire DATETIME,
            name           VARCHAR          NOT NULL,
            email          VARCHAR          NOT NULL UNIQUE,
            memo           TEXT,
            loggedin       DATETIME,
            failed         INTEGER UNSIGNED,
            failed_last    DATETIME,
            token          VARCHAR,
            token_code     VARCHAR,
            token_expire   DATETIME,
            twostep        INTEGER UNSIGNED NOT NULL,
            twostep_email  VARCHAR,
            twostep_code   VARCHAR,
            twostep_expire DATETIME,
            PRIMARY KEY(id)
        );
    ');
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'sessions(
            id       VARCHAR          NOT NULL,
            created  DATETIME         NOT NULL,
            modified DATETIME         NOT NULL,
            user_id  INTEGER UNSIGNED NOT NULL,
            agent    VARCHAR          NOT NULL,
            keep     INTEGER UNSIGNED NOT NULL,
            twostep  INTEGER UNSIGNED NOT NULL,
            expire   DATETIME         NOT NULL,
            PRIMARY KEY(id)
        );
    ');
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'classes(
            id       INTEGER,
            created  DATETIME         NOT NULL,
            modified DATETIME         NOT NULL,
            deleted  DATETIME,
            code     VARCHAR          NOT NULL UNIQUE,
            name     VARCHAR          NOT NULL,
            memo     TEXT,
            image_01 VARCHAR,
            image_02 VARCHAR,
            document VARCHAR,
            sort     INTEGER UNSIGNED NOT NULL,
            PRIMARY KEY(id)
        );
    ');
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'members(
            id        INTEGER,
            created   DATETIME         NOT NULL,
            modified  DATETIME         NOT NULL,
            deleted   DATETIME,
            class_id  VARCHAR          NOT NULL,
            name      VARCHAR          NOT NULL,
            name_kana VARCHAR          NOT NULL,
            grade     INTEGER UNSIGNED NOT NULL,
            birthday  DATE,
            email     VARCHAR,
            tel       VARCHAR,
            memo      TEXT,
            image_01  VARCHAR,
            image_02  VARCHAR,
            public    INTEGER UNSIGNED NOT NULL,
            PRIMARY KEY(id)
        );
    ');
}

ok();
