<?php

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
      INDEX `fk_Reservation_user1_idx` (`user_id`),
      CONSTRAINT `fk_Reservation_User1`
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
    CREATE TABLE IF NOT EXISTS `clementine_reservation_option_has_privilege` (
      `option_id` int(11) NOT NULL AUTO_INCREMENT,
      `clementine_users_privileges_id` int(10) unsigned NOT NULL,
      PRIMARY KEY (`option_id`,`clementine_users_privileges_id`),
      INDEX `fk_Option2` (`clementine_users_privileges_id`) ,
      CONSTRAINT `fk_Option1`
        FOREIGN KEY (`option_id`)
        REFERENCES `clementine_reservation_option` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
      CONSTRAINT `fk_Option2`
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
      INDEX `fk_Ressource_clementine_users1` (`client_id`),
      CONSTRAINT `fk_Ressource_clementine_users1`
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
      INDEX `fk_Ressource_has_Horaire_horaire1_idx` (`horaire_id`),
      INDEX `fk_Ressource_has_Horaire_ressource1_idx` (`ressource_id`),
      CONSTRAINT `fk_Ressource_has_Horaire_Horaire1`
        FOREIGN KEY (`horaire_id`)
        REFERENCES `clementine_reservation_horaire` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
      CONSTRAINT `fk_Ressource_has_Horaire_Ressource1`
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
      `primary` tinyint(1) DEFAULT NULL
      PRIMARY KEY (`reservation_id`,`ressource_id`),
      INDEX `fk_RessourceReserve_Ressource1` (`ressource_id`),
      CONSTRAINT `fk_RessourceReserve_Reservation1`
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
    CREATE TABLE IF NOT EXISTS `clementine_reservation_users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(45) DEFAULT NULL,
      `firstname` varchar(45) DEFAULT NULL,
      `mail` varchar(45) DEFAULT NULL,
      `clementine_users_id` int(10) unsigned DEFAULT NULL,
      PRIMARY KEY (`id`),
      INDEX `fk_User_UserConnecte1` (`clementine_users_id`)
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
      INDEX `fk_Option_has_User_User1_idx` (`user_id`),
      INDEX `fk_Option_has_User_Option1_idx` (`option_id`),
      CONSTRAINT `fk_Option_has_User_Option1`
        FOREIGN KEY (`option_id`)
        REFERENCES `clementine_reservation_option` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
      CONSTRAINT `fk_Option_has_User_User1`
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

return true;
