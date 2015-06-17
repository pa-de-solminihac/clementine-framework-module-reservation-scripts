<?php
$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `clementine_reservation_horaire` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `start_date` date DEFAULT NULL,
      `start_hour` time DEFAULT NULL COMMENT 'Un jour de la semaine',
      `end_hour` time DEFAULT NULL,
      `end_date` date DEFAULT NULL,
      `to_add` int(6) NOT NULL,
      `comment` varchar(255) NOT NULL,
      `maximum_number_place_by_reservation` int(11) NOT NULL,
      `maximum_number_place` int(11) NOT NULL,
      `time_creneaux` time NOT NULL,
      `option` int(11) NOT NULL DEFAULT '0',
      PRIMARY KEY(`id`)
    )
    ENGINE=InnoDB
    DEFAULT CHARACTER SET = UTF8;
SQL;

if (!$db->prepare($sql)->execute()) {
    $db->rollBack();
    return false;
}

$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `clementine_reservation_horaire_has_option` (
      `repeat_all` varchar(20) DEFAULT NULL,
      `month` varchar(256) DEFAULT NULL,
      `week` varchar(256) DEFAULT NULL,
      `till` date DEFAULT NULL,
      `id_horaire` int(11) NOT NULL,
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `repeat` int(11) DEFAULT NULL,
      PRIMARY KEY(`id`)
    )
    ENGINE=InnoDB
    DEFAULT CHARACTER SET = UTF8;
SQL;

if (!$db->prepare($sql)->execute()) {
    $db->rollBack();
    return false;
}

$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `clementine_reservation_option` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `libelle` varchar(45) DEFAULT NULL,
      PRIMARY KEY(`id`)
    )
    ENGINE=InnoDB
    DEFAULT CHARACTER SET = UTF8;
SQL;

if (!$db->prepare($sql)->execute()) {
    $db->rollBack();
    return false;
}

$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `clementine_reservation_users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(45) DEFAULT NULL,
      `firstname` varchar(45) DEFAULT NULL,
      `mail` varchar(45) DEFAULT NULL,
      `clementine_users_id` int(10) unsigned DEFAULT NULL,
      PRIMARY KEY (`id`),
      INDEX `fk_clementine_reservation_user_clementine_reservation_users1` (`clementine_users_id`)
    )
    ENGINE=InnoDB
    DEFAULT CHARACTER SET = UTF8;
SQL;

if (!$db->prepare($sql)->execute()) {
    $db->rollBack();
    return false;
}

$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `clementine_reservation` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `number_people` int(11) DEFAULT NULL,
      `cancel` tinyint(1) DEFAULT NULL,
      `comment` longtext,
      `information_id` int(11) DEFAULT NULL,
      `start_date` datetime DEFAULT NULL,
      `end_date` datetime DEFAULT NULL,
      `user_id` int(11) NOT NULL,
      PRIMARY KEY(`id`),
      INDEX `fk_clementine_reservation_clementine_reservation_user1` (`user_id`),
      CONSTRAINT `fk_clementine_reservation_clementine_reservation_user1`
        FOREIGN KEY (`user_id`)
        REFERENCES `clementine_reservation_users` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
    )
    ENGINE=InnoDB
    DEFAULT CHARACTER SET = UTF8;
SQL;

if (!$db->prepare($sql)->execute()) {
    $db->rollBack();
    return false;
}

$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `clementine_reservation_option_has_privilege` (
      `option_id` int(11) NOT NULL AUTO_INCREMENT,
      `clementine_users_privileges_id` int(10) unsigned NOT NULL,
      PRIMARY KEY (`option_id`,`clementine_users_privileges_id`),
      INDEX `fk_clementine_reservation_option_has_privilege_clementine_rese1` (`option_id`),
      INDEX `fk_clementine_reservation_option_has_privilege_clementine_user2` (`clementine_users_privileges_id`),
      CONSTRAINT `fk_clementine_reservation_option_has_privilege_clementine_rese1`
        FOREIGN KEY (`option_id`)
        REFERENCES `clementine_reservation_option` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
      CONSTRAINT `fk_clementine_reservation_option_has_privilege_clementine_user2`
        FOREIGN KEY (`clementine_users_privileges_id`)
        REFERENCES `clementine_users_privileges` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
    )
    ENGINE=InnoDB
    DEFAULT CHARACTER SET = UTF8;
SQL;

if (!$db->prepare($sql)->execute()) {
    $db->rollBack();
    return false;
}

$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `clementine_reservation_ressource` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `client_id` int(10) unsigned NOT NULL,
      `maximum_number_place` int(11) DEFAULT NULL,
      `time_creneaux` time DEFAULT NULL COMMENT 'unité en minute',
      `libelle` varchar(255) NOT NULL,
      `maximum_number_place_by_reservation` int(11) NOT NULL,
      PRIMARY KEY (`id`),
      INDEX `fk_clementine_reservation_ressource_clementine_users1` (`client_id`),
      CONSTRAINT `fk_clementine_reservation_ressource_clementine_users1`
        FOREIGN KEY (`client_id`)
        REFERENCES `clementine_users` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
    )
    ENGINE=InnoDB
    DEFAULT CHARACTER SET = UTF8;
SQL;
if (!$db->prepare($sql)->execute()) {
    $db->rollBack();
    return false;
}

$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `clementine_reservation_ressource_has_horaire` (
      `ressource_id` int(11) NOT NULL,
      `horaire_id` int(11) NOT NULL,
      PRIMARY KEY (`ressource_id`,`horaire_id`),
      INDEX `fk_clementine_reservation_ressource_has_horaire_clementine_res1` (`horaire_id`),
      INDEX `fk_clementine_reservation_ressource_has_Horaire_clementine_res2` (`ressource_id`),
      CONSTRAINT `fk_clementine_reservation_ressource_has_horaire_clementine_res1`
        FOREIGN KEY (`horaire_id`)
        REFERENCES `clementine_reservation_horaire` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
      CONSTRAINT `fk_clementine_reservation_ressource_has_Horaire_clementine_res2`
        FOREIGN KEY (`ressource_id`)
        REFERENCES `clementine_reservation_ressource` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
    )
    ENGINE=InnoDB
    DEFAULT CHARACTER SET = UTF8;
SQL;

if (!$db->prepare($sql)->execute()) {
    $db->rollBack();
    return false;
}

$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `clementine_reservation_ressource_has_reservation` (
      `reservation_id` int(11) NOT NULL,
      `ressource_id` int(11) NOT NULL,
      `primary` tinyint(1) DEFAULT NULL,
      PRIMARY KEY (`reservation_id`,`ressource_id`),
      INDEX `fk_clementine_reservation_ressource_has_reservation_clementine1` (`ressource_id`),
      CONSTRAINT `fk_clementine_reservation_ressource_has_reservation_clementine1`
        FOREIGN KEY (`reservation_id`)
        REFERENCES `clementine_reservation` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
    )
    ENGINE=InnoDB
    DEFAULT CHARACTER SET = UTF8;
SQL;

if (!$db->prepare($sql)->execute()) {
    $db->rollBack();
    return false;
}

$sql = <<<SQL
    CREATE TABLE IF NOT EXISTS `clementine_reservation_users_has_option` (
      `user_id` int(11) NOT NULL,
      `option_id` int(11) NOT NULL,
      PRIMARY KEY (`user_id`,`option_id`),
      INDEX `fk_clementine_reservation_users_has_option_clementine_reservat1` (`user_id`),
      INDEX `fk_clementine_reservation_option_has_user_clementine_reservat2` (`option_id`),
      CONSTRAINT `fk_clementine_reservation_option_has_user_clementine_reservat2`
        FOREIGN KEY (`option_id`)
        REFERENCES `clementine_reservation_option` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
      CONSTRAINT `fk_clementine_reservation_users_has_option_clementine_reservat1`
        FOREIGN KEY (`user_id`)
        REFERENCES `clementine_reservation_users` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
    )
    ENGINE=InnoDB
    DEFAULT CHARACTER SET = UTF8;
SQL;

if (!$db->prepare($sql)->execute()) {
    $db->rollBack();
    return false;
}

$sql = <<<SQL
    INSERT INTO clementine_users_privileges(id, privilege) VALUES (DEFAULT, 'clementine_reservation_gerer_reservation'), (DEFAULT,'clementine_reservation_creer_exception'),(DEFAULT, 'clementine_reservation_annuler_reservation_cl'), (DEFAULT, 'clementine_reservation_annuler_reservation_ut'), (DEFAULT, 'clementine_reservation_visualiser_stats'), (DEFAULT, 'clementine_reservation_list_reservation'), (DEFAULT, 'clementine_reservation_regarder_reservation');
SQL;

if (!$db->prepare($sql)->execute()) {
    $db->rollBack();
    return false;
}
$sql = <<<SQL
    SELECT * FROM `clementine_users_groups` WHERE `group`='administrateurs' OR `group`='clients' 
    ORDER BY id ASC
SQL;
echo "lel";
$sth = $db->prepare($sql);
$sth->execute();
$id_group = array();
$res = $sth->fetchAll();
var_dump($res);
foreach ($res as $key => $value) {
    if ($value['group'] == "administrateurs") {
        $id_group[0] = $value['id'];
    } else {
        $id_group[1] = $value['id'];
    }
}
echo "kek";

$sql = <<<SQL
    SELECT id, privilege
    FROM clementine_users_privileges
    WHERE `privilege` = 'clementine_reservation_gerer_reservation' OR `privilege` = 'clementine_reservation_creer_exception' OR `privilege` = 'clementine_reservation_annuler_reservation_cl' OR `privilege` = 'clementine_reservation_annuler_reservation_ut' OR  `privilege` = 'clementine_reservation_visualiser_stats' OR `privilege` = 'clementine_reservation_list_reservation' OR `privilege` = 'clementine_reservation_regarder_reservation'
SQL;

$sth = $db->prepare($sql);
$sth->execute();
$res1 = $sth->fetchAll();
$sql = "INSERT INTO clementine_users_groups_has_privileges(group_id, privilege_id) VALUES";
$i = 0;
foreach($res1 as $key => $value) {
    if ($i > 0) {
        $sql .= ", ";
    }  
    if ($value['privilege'] == "clementine_reservation_regarder_reservation") {
        $sql .= ' (' . $id_group[1] . ', ' . $value['id'] . ')';
    } else {
        $sql .= ' (' . $id_group[0] . ', ' .  $value['id'] . ')';
    }
    ++$i;
}    
echo $sql;
if (!$db->prepare($sql)->execute()) {
    $db->rollBack();
    return false;
}

return true;
