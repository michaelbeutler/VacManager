SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `tbl_class` (
  `id` int(7) NOT NULL,
  `class_name` varchar(45) COLLATE utf8_german2_ci NOT NULL,
  `class_longname` varchar(45) COLLATE utf8_german2_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

CREATE TABLE `tbl_contingent` (
  `id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `basis` float(10,1) NOT NULL DEFAULT '25.0',
  `tbl_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

CREATE TABLE `tbl_employer` (
  `id` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_german2_ci NOT NULL,
  `shortname` varchar(15) COLLATE utf8_german2_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `takeover` tinyint(4) NOT NULL DEFAULT '0',
  `per_year` decimal(10,0) NOT NULL DEFAULT '25'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(25) COLLATE utf8_german2_ci NOT NULL,
  `lastname` varchar(25) COLLATE utf8_german2_ci NOT NULL,
  `birthdate` date DEFAULT NULL,
  `username` varchar(51) COLLATE utf8_german2_ci NOT NULL,
  `password` varchar(1000) COLLATE utf8_german2_ci NOT NULL,
  `salt` varchar(128) COLLATE utf8_german2_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `tbl_class_id` int(7) NOT NULL,
  `tbl_employer_id` int(11) NOT NULL,
  `start_work` date NOT NULL,
  `end_work` date NOT NULL,
  `ban` tinyint(4) NOT NULL DEFAULT '0',
  `loadClassEvents` tinyint(1) NOT NULL DEFAULT '0'
  `admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

CREATE TABLE `tbl_vacation` (
  `id` int(11) NOT NULL,
  `start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `num` decimal(10,1) NOT NULL DEFAULT '0.0',
  `description` varchar(100) COLLATE utf8_german2_ci DEFAULT NULL,
  `other_text` varchar(45) COLLATE utf8_german2_ci DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `canceled` tinyint(4) NOT NULL DEFAULT '0',
  `tbl_user_id` int(11) NOT NULL,
  `tbl_vacation_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_german2_ci;

CREATE TABLE `tbl_vacation_type` (
  `id` int(11) NOT NULL,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_german2_ci NOT NULL,
  `substract_vacation_days` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE `tbl_class`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tbl_contingent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbl_contingent_tbl_user1_idx` (`tbl_user_id`);

ALTER TABLE `tbl_employer`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbl_user_tbl_class1_idx` (`tbl_class_id`),
  ADD KEY `fk_tbl_user_tbl_employer1_idx` (`tbl_employer_id`);

ALTER TABLE `tbl_vacation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbl_vacation_tbl_user1_idx` (`tbl_user_id`),
  ADD KEY `fk_tbl_vacation_tbl_vacation_type1_idx` (`tbl_vacation_type_id`);

ALTER TABLE `tbl_vacation_type`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `tbl_contingent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `tbl_employer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
ALTER TABLE `tbl_vacation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
ALTER TABLE `tbl_vacation_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `tbl_contingent`
  ADD CONSTRAINT `fk_tbl_contingent_tbl_user1` FOREIGN KEY (`tbl_user_id`) REFERENCES `tbl_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `tbl_user`
  ADD CONSTRAINT `fk_tbl_user_tbl_class1` FOREIGN KEY (`tbl_class_id`) REFERENCES `tbl_class` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_user_tbl_employer1` FOREIGN KEY (`tbl_employer_id`) REFERENCES `tbl_employer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `tbl_vacation`
  ADD CONSTRAINT `fk_tbl_vacation_tbl_user1` FOREIGN KEY (`tbl_user_id`) REFERENCES `tbl_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_vacation_tbl_vacation_type1` FOREIGN KEY (`tbl_vacation_type_id`) REFERENCES `tbl_vacation_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
