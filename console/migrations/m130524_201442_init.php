<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function safeUp()
    {
        $this->createCountryTable();
        $this->createPersonTable();
        $this->createUserTable();
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-user-person_id', '{{%user}}');
        $this->dropIndex('idx-user-person_id', '{{%user}}');

        $this->dropForeignKey('fk-person-country_id', '{{%person}}');
        $this->dropIndex('idx-person-country_id', '{{%person}}');

        $this->dropTable('{{%user}}');
        $this->dropTable('{{%country}}');
        $this->dropTable('{{%person}}');
    }

    private function createCountryTable()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%country}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'code' => $this->string(2)->notNull(),
        ], $tableOptions);

        $this->db->createCommand("
            INSERT INTO `country` (`id`, `name`, `code`) VALUES
                (1, 'Андорра', 'AD'),
                (2, 'ОАЭ', 'AE'),
                (3, 'Афганистан', 'AF'),
                (4, 'Антигуа и Барбуда', 'AG'),
                (5, 'Ангилья', 'AI'),
                (6, 'Албания', 'AL'),
                (7, 'Армения', 'AM'),
                (8, 'Ангола', 'AO'),
                (9, 'Аргентина', 'AR'),
                (10, 'Американское Самоа', 'AS'),
                (11, 'Австрия', 'AT'),
                (12, 'Австралия', 'AU'),
                (13, 'Аруба', 'AW'),
                (14, 'Азербайджан', 'AZ'),
                (15, 'Босния и Герцеговина', 'BA'),
                (16, 'Барбадос', 'BB'),
                (17, 'Бангладеш', 'BD'),
                (18, 'Бельгия', 'BE'),
                (19, 'Буркина-Фасо', 'BF'),
                (20, 'Болгария', 'BG'),
                (21, 'Бахрейн', 'BH'),
                (22, 'Бурунди', 'BI'),
                (23, 'Бенин', 'BJ'),
                (24, 'Бермуды', 'BM'),
                (25, 'Бруней', 'BN'),
                (26, 'Боливия', 'BO'),
                (27, 'Бразилия', 'BR'),
                (28, 'Багамы', 'BS'),
                (29, 'Бутан', 'BT'),
                (30, 'Ботсвана', 'BW'),
                (31, 'Беларусь', 'BY'),
                (32, 'Белиз', 'BZ'),
                (33, 'Канада', 'CA'),
                (34, 'ЦАР', 'CF'),
                (35, 'Конго', 'CG'),
                (36, 'Швейцария', 'CH'),
                (37, 'Кот-д\'Ивуар', 'CI'),
                (38, 'острова Кука', 'CK'),
                (40, 'Чили', 'CL'),
                (41, 'Камерун', 'CM'),
                (42, 'Китай', 'CN'),
                (43, 'Колумбия', 'CO'),
                (44, 'Коста-Рика', 'CR'),
                (45, 'Куба', 'CU'),
                (46, 'Кабо-Верде', 'CV'),
                (47, 'Кипр', 'CY'),
                (48, 'Чехия', 'CZ'),
                (49, 'Германия', 'DE'),
                (50, 'Джибути', 'DJ'),
                (51, 'Дания', 'DK'),
                (52, 'Доминика', 'DM'),
                (53, 'Доминикана', 'DO'),
                (54, 'Алжир', 'DZ'),
                (55, 'Эквадор', 'EC'),
                (56, 'Эстония', 'EE'),
                (57, 'Египет', 'EG'),
                (58, 'Эритрея', 'ER'),
                (59, 'Испания', 'ES'),
                (60, 'Эфиопия', 'ET'),
                (61, 'Финляндия', 'FI'),
                (62, 'Фиджи', 'FJ'),
                (63, 'Фолклендские острова', 'FK'),
                (64, 'Микронезия', 'FM'),
                (65, 'Фарерские острова', 'FO'),
                (66, 'Франция', 'FR'),
                (67, 'Габон', 'GA'),
                (68, 'Великобритания', 'GB'),
                (69, 'Гренада', 'GD'),
                (70, 'Грузия', 'GE'),
                (71, 'Французская Гвиана', 'GF'),
                (72, 'Гана', 'GH'),
                (73, 'Гибралтар', 'GI'),
                (74, 'Гренландия', 'GL'),
                (75, 'Гамбия', 'GM'),
                (76, 'Гвинея', 'GN'),
                (77, 'Гваделупа', 'GP'),
                (78, 'Экваториальная Гвинея', 'GQ'),
                (79, 'Греция', 'GR'),
                (80, 'Гватемала', 'GT'),
                (81, 'Гуам', 'GU'),
                (82, 'Гвинея-Бисау', 'GW'),
                (83, 'Гайана', 'GY'),
                (84, 'Гонконг', 'HK'),
                (85, 'Гондурас', 'HN'),
                (86, 'Хорватия', 'HR'),
                (87, 'Гаити', 'HT'),
                (88, 'Венгрия', 'HU'),
                (89, 'Индонезия', 'ID'),
                (90, 'Ирландия', 'IE'),
                (91, 'Израиль', 'IL'),
                (93, 'Индия', 'IN'),
                (94, 'Ирак', 'IQ'),
                (95, 'Иран', 'IR'),
                (96, 'Исландия', 'IS'),
                (97, 'Италия', 'IT'),
                (98, 'Ямайка', 'JM'),
                (99, 'Иордания', 'JO'),
                (100, 'Япония', 'JP'),
                (101, 'Кения', 'KE'),
                (102, 'Киргизия', 'KG'),
                (103, 'Камбоджа', 'KH'),
                (104, 'Кирибати', 'KI'),
                (105, 'Коморы', 'KM'),
                (106, 'Сент-Киттс и Невис', 'KN'),
                (107, 'Южная Корея', 'KR'),
                (108, 'Кувейт', 'KW'),
                (109, 'Каймановы острова', 'KY'),
                (110, 'Казахстан', 'KZ'),
                (111, 'Лаос', 'LA'),
                (112, 'Ливан', 'LB'),
                (113, 'Сент-Люсия', 'LC'),
                (114, 'Лихтенштейн', 'LI'),
                (115, 'Шри-Ланка', 'LK'),
                (116, 'Либерия', 'LR'),
                (117, 'Лесото', 'LS'),
                (118, 'Литва', 'LT'),
                (119, 'Люксембург', 'LU'),
                (120, 'Латвия', 'LV'),
                (121, 'Ливия', 'LY'),
                (122, 'Марокко', 'MA'),
                (123, 'Монако', 'MC'),
                (124, 'Молдова', 'MD'),
                (125, 'Черногория', 'ME'),
                (126, 'Мадагаскар', 'MG'),
                (127, 'Маршалловы острова', 'MH'),
                (128, 'Македония', 'MK'),
                (129, 'Мали', 'ML'),
                (130, 'Мьянма', 'MM'),
                (131, 'Монголия', 'MN'),
                (132, 'Макао', 'MO'),
                (133, 'Северные Марианские острова', 'MP'),
                (134, 'Мартиника', 'MQ'),
                (135, 'Мавритания', 'MR'),
                (136, 'Монтсеррат', 'MS'),
                (137, 'Мальта', 'MT'),
                (138, 'Маврикий', 'MU'),
                (139, 'Мальдивы', 'MV'),
                (140, 'Малави', 'MW'),
                (141, 'Мексика', 'MX'),
                (142, 'Малайзия', 'MY'),
                (143, 'Мозамбик', 'MZ'),
                (144, 'Намибия', 'NA'),
                (145, 'Новая Каледония', 'NC'),
                (146, 'Нигер', 'NE'),
                (147, 'остров Норфолк', 'NF'),
                (148, 'Нигерия', 'NG'),
                (149, 'Никарагуа', 'NI'),
                (150, 'Нидерланды', 'NL'),
                (151, 'Норвегия', 'NO'),
                (152, 'Непал', 'NP'),
                (153, 'Ниуэ', 'NU'),
                (154, 'Новая Зеландия', 'NZ'),
                (155, 'Оман', 'OM'),
                (156, 'Панама', 'PA'),
                (157, 'Перу', 'PE'),
                (158, 'Французская Полинезия', 'PF'),
                (159, 'Папуа-Новая Гвинея', 'PG'),
                (160, 'Филиппины', 'PH'),
                (161, 'Пакистан', 'PK'),
                (162, 'Польша', 'PL'),
                (163, 'Сен-Пьер и Микелон', 'PM'),
                (164, 'Пуэрто-Рико', 'PR'),
                (165, 'Португалия', 'PT'),
                (166, 'Палау', 'PW'),
                (167, 'Парагвай', 'PY'),
                (168, 'Катар', 'QA'),
                (169, 'Реюньон', 'RE'),
                (170, 'Румыния', 'RO'),
                (171, 'Сербия', 'RS'),
                (172, 'Россия', 'RU'),
                (173, 'Руанда', 'RW'),
                (174, 'Саудовская Аравия', 'SA'),
                (175, 'Соломоновы острова', 'SB'),
                (176, 'Сейшелы', 'SC'),
                (177, 'Швеция', 'SE'),
                (178, 'Сингапур', 'SG'),
                (179, 'Словения', 'SI'),
                (180, 'Словакия', 'SK'),
                (181, 'Сьерра-Леоне', 'SL'),
                (182, 'Сан-Марино', 'SM'),
                (183, 'Сенегал', 'SN'),
                (184, 'Суринам', 'SR'),
                (185, 'Сан-Томе и Принсипи', 'ST'),
                (186, 'Сальвадор', 'SV'),
                (187, 'Свазиленд', 'SZ'),
                (188, 'Теркс и Кайкос', 'TC'),
                (189, 'Того', 'TG'),
                (190, 'Таиланд', 'TH'),
                (191, 'Таджикистан', 'TJ'),
                (192, 'Тунис', 'TN'),
                (193, 'Тонга', 'TO'),
                (194, 'Турция', 'TR'),
                (195, 'Тринидад и Тобаго', 'TT'),
                (196, 'Тувалу', 'TV'),
                (197, 'Тайвань', 'TW'),
                (198, 'Танзания', 'TZ'),
                (199, 'Украина', 'UA'),
                (200, 'Уганда', 'UG'),
                (201, 'США', 'US'),
                (202, 'Уругвай', 'UY'),
                (203, 'Узбекистан', 'UZ'),
                (204, 'Сент-Винсент и Гренадины', 'VC'),
                (205, 'Венесуэла', 'VE'),
                (206, 'Британские Виргинские острова', 'VG'),
                (207, 'Американские Виргинские острова', 'VI'),
                (208, 'Вьетнам', 'VN'),
                (209, 'Вануату', 'VU'),
                (210, 'Уоллис и Футуна', 'WF'),
                (211, 'Самоа', 'WS'),
                (212, 'Абхазия', 'XA'),
                (213, 'Крым', 'XC'),
                (214, 'Майотта', 'YT'),
                (215, 'ЮАР', 'ZA'),
                (216, 'Замбия', 'ZM'),
                (217, 'Зимбабве', 'ZW'),
                (218, 'Кокосовые острова', 'CC'),
                (219, 'Чад', 'TD')
        ")
        ->execute();
    }

    private function createUserTable()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'auth_key' => $this->string(32)->notNull(),
            'username' => $this->string()->null()->unique()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'verification_token' => $this->string()->defaultValue(null),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'person_id' => $this->integer()->notNull()->unique(),
        ], $tableOptions);

        $this->createIndex(
            'idx-user-person_id',
            '{{%user}}',
            'person_id'
        );

        $this->addForeignKey(
            'fk-user-person_id',
            '{{%user}}',
            'person_id',
            '{{%person}}',
            'id',
            'RESTRICT'
        );
    }

    private function createPersonTable()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%person}}', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer()->notNull(),
            'first_name' => $this->string(50)->notNull(),
            'last_name' => $this->string(50)->notNull(),
            'patronymic' => $this->string(50),
            'email' => $this->string()->null()->unique(),
            'phone' => $this->string(30)->notNull()->unique(),
            'photo' => $this->string(),
            'birth_date' => $this->date(),

            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex(
            'idx-person-country_id',
            '{{%person}}',
            'country_id'
        );

        $this->addForeignKey(
            'fk-person-country_id',
            '{{%person}}',
            'country_id',
            '{{%country}}',
            'id',
            'CASCADE'
        );
    }
}
