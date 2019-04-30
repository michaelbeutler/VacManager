-- MySQL Workbench Forward Engineering
SET
    @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS,
    UNIQUE_CHECKS = 0;
SET
    @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS,
    FOREIGN_KEY_CHECKS = 0;
SET
    @OLD_SQL_MODE = @@SQL_MODE,
    SQL_MODE = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
    -- -----------------------------------------------------
    -- Schema vac_manager
    -- -----------------------------------------------------
DROP
    DATABASE IF EXISTS `vac_manager`;
    -- -----------------------------------------------------
    -- Schema vac_manager
    -- -----------------------------------------------------
CREATE DATABASE IF NOT EXISTS `vac_manager` DEFAULT CHARACTER SET utf8; USE
    `vac_manager`;
    -- -----------------------------------------------------
    -- Table `vac_manager`.`employer`
    -- -----------------------------------------------------
DROP TABLE IF EXISTS
    `vac_manager`.`employer`;
CREATE TABLE IF NOT EXISTS `vac_manager`.`employer`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(45) NOT NULL,
    `shortname` VARCHAR(45) NOT NULL,
    `create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_date` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(`id`)
) ENGINE = InnoDB;
-- -----------------------------------------------------
-- Table `vac_manager`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS
    `vac_manager`.`user`;
CREATE TABLE IF NOT EXISTS `vac_manager`.`user`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(91) NOT NULL,
    `email` VARCHAR(45) NOT NULL,
    `firstname` VARCHAR(45) NOT NULL,
    `lastname` VARCHAR(45) NOT NULL,
    `admin` TINYINT(1) NOT NULL DEFAULT 0,
    `is_banned` TINYINT(1) NOT NULL DEFAULT 0,
    `password` VARCHAR(128) NOT NULL,
    `salt` VARCHAR(128) NOT NULL,
    `create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_date` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `employer_id` INT NOT NULL,
    PRIMARY KEY(`id`),
    CONSTRAINT `fk_user_employer` FOREIGN KEY(`employer_id`) REFERENCES `vac_manager`.`employer`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB; CREATE INDEX `fk_user_employer1_idx` ON
    `vac_manager`.`user`(`employer_id` ASC);
    -- -----------------------------------------------------
    -- Table `vac_manager`.`vacation_type`
    -- -----------------------------------------------------
DROP TABLE IF EXISTS
    `vac_manager`.`vacation_type`;
CREATE TABLE IF NOT EXISTS `vac_manager`.`vacation_type`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(45) NOT NULL,
    `substract_vacation_days` TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY(`id`)
) ENGINE = InnoDB;
-- -----------------------------------------------------
-- Table `vac_manager`.`vacation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS
    `vac_manager`.`vacation`;
CREATE TABLE IF NOT EXISTS `vac_manager`.`vacation`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(45) NOT NULL,
    `description` VARCHAR(45) NULL,
    `start` DATETIME NOT NULL,
    `end` DATETIME NOT NULL,
    `days` FLOAT(3, 1) NOT NULL DEFAULT 0.0,
    `user_id` INT NOT NULL,
    `accepted` TINYINT NOT NULL DEFAULT 0,
    `user_id_accepted` INT NULL,
    `vacation_type_id` INT NOT NULL,
    `create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_date` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(`id`),
    CONSTRAINT `fk_vacation_user1` FOREIGN KEY(`user_id`) REFERENCES `vac_manager`.`user`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `fk_vacation_user2` FOREIGN KEY(`user_id_accepted`) REFERENCES `vac_manager`.`user`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `fk_vacation_vacation_type1` FOREIGN KEY(`vacation_type_id`) REFERENCES `vac_manager`.`vacation_type`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB; CREATE INDEX `fk_vacation_user1_idx` ON
    `vac_manager`.`vacation`(`user_id` ASC);
CREATE INDEX `fk_vacation_user2_idx` ON
    `vac_manager`.`vacation`(`user_id_accepted` ASC);
CREATE INDEX `fk_vacation_vacation_type1_idx` ON
    `vac_manager`.`vacation`(`vacation_type_id` ASC);
    -- -----------------------------------------------------
    -- Table `vac_manager`.`employer_privileges`
    -- -----------------------------------------------------
DROP TABLE IF EXISTS
    `vac_manager`.`employer_privileges`;
CREATE TABLE IF NOT EXISTS `vac_manager`.`employer_privileges`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `employer_id` INT NOT NULL,
    `can_accept` TINYINT NOT NULL DEFAULT 0,
    `can_rename` TINYINT NOT NULL DEFAULT 0,
    `can_priv` TINYINT NOT NULL DEFAULT 0,
    `create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_date` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(`id`),
    CONSTRAINT `fk_employer_privileges_user` FOREIGN KEY(`user_id`) REFERENCES `vac_manager`.`user`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `fk_employer_privileges_employer1` FOREIGN KEY(`employer_id`) REFERENCES `vac_manager`.`employer`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB; CREATE INDEX `fk_employer_privileges_user_idx` ON
    `vac_manager`.`employer_privileges`(`user_id` ASC);
CREATE INDEX `fk_employer_privileges_employer1_idx` ON
    `vac_manager`.`employer_privileges`(`employer_id` ASC);
    -- -----------------------------------------------------
    -- Table `vac_manager`.`contingent`
    -- -----------------------------------------------------
DROP TABLE IF EXISTS
    `vac_manager`.`contingent`;
CREATE TABLE IF NOT EXISTS `vac_manager`.`contingent`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `year` YEAR NOT NULL,
    `contingent` FLOAT(3, 1) NOT NULL,
    `user_id` INT NOT NULL,
    `create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_date` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(`id`),
    CONSTRAINT `fk_contingent_user1` FOREIGN KEY(`user_id`) REFERENCES `vac_manager`.`user`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB; CREATE INDEX `fk_contingent_user1_idx` ON
    `vac_manager`.`contingent`(`user_id` ASC);
SET
    SQL_MODE = @OLD_SQL_MODE;
SET
    FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS;
SET
    UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS;