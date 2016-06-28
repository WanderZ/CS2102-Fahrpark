SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `karfahrpark` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `karfahrpark` ;

-- -----------------------------------------------------
-- Table `karfahrpark`.`Users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `karfahrpark`.`Users` (
  `username` VARCHAR(255) NOT NULL ,
  `password` VARCHAR(255) NOT NULL ,
  `fullname` VARCHAR(255) NOT NULL ,
  `phone` VARCHAR(45) NOT NULL ,
  `isAdmin` TINYINT(1) NOT NULL DEFAULT 0 ,
  `lastLogin` TIMESTAMP NOT NULL ,
  `createdAt` TIMESTAMP NOT NULL DEFAULT NOW() ,
  PRIMARY KEY (`username`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `karfahrpark`.`Vehicles`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `karfahrpark`.`Vehicles` (
  `plateNo` VARCHAR(8) NOT NULL ,
  `numSeat` SMALLINT(2) NOT NULL ,
  `brand` VARCHAR(255) NOT NULL ,
  `model` VARCHAR(255) NOT NULL ,
  `color` VARCHAR(45) NOT NULL ,
  `driver` VARCHAR(255) NOT NULL ,
  `createdAt` TIMESTAMP NOT NULL DEFAULT NOW() ,
  PRIMARY KEY (`plateNo`) ,
  INDEX `fk_driver_idx` (`driver` ASC) ,
  CONSTRAINT `fk_driver`
    FOREIGN KEY (`driver` )
    REFERENCES `karfahrpark`.`Users` (`username` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `karfahrpark`.`Journeys`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `karfahrpark`.`Journeys` (
  `vehicle` VARCHAR(8) NOT NULL ,
  `createdAt` TIMESTAMP NOT NULL ,
  `startLat` DOUBLE NOT NULL ,
  `startLng` DOUBLE NOT NULL ,
  `endLat` DOUBLE NOT NULL ,
  `endLng` DOUBLE NOT NULL ,
  `start` VARCHAR(255) NOT NULL ,
  `end` VARCHAR(255) NOT NULL ,
  `cost` DECIMAL(10,2) NOT NULL ,
  `remarks` TEXT NOT NULL ,
  `departureDatetime` DATETIME NOT NULL ,
  `arrivalDatetime` DATETIME NOT NULL ,
  PRIMARY KEY (`vehicle`, `createdAt`) ,
  INDEX `fk_vehicle_idx` (`vehicle` ASC) ,
  CONSTRAINT `fk_vehicle`
    FOREIGN KEY (`vehicle` )
    REFERENCES `karfahrpark`.`Vehicles` (`plateNo` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_passenger`
    FOREIGN KEY ()
    REFERENCES `karfahrpark`.`Users` ()
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `karfahrpark`.`Bookings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `karfahrpark`.`Bookings` (
  `vehicle` VARCHAR(8) NOT NULL ,
  `journeyCreatedAt` TIMESTAMP NOT NULL ,
  `passenger` VARCHAR(255) NOT NULL ,
  `status` CHAR(9) NOT NULL ,
  `remarks` TEXT NOT NULL ,
  `createdAt` TIMESTAMP NOT NULL ,
  PRIMARY KEY (`vehicle`) ,
  INDEX `fk_journeys_idx` (`vehicle` ASC, `journeyCreatedAt` ASC) ,
  CONSTRAINT `fk_journeys`
    FOREIGN KEY (`vehicle` , `journeyCreatedAt` )
    REFERENCES `karfahrpark`.`Journeys` (`vehicle` , `createdAt` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `karfahrpark`.`Transactions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `karfahrpark`.`Transactions` (
  `invoiceNo` VARCHAR(255) NOT NULL ,
  `from` VARCHAR(255) NOT NULL ,
  `to` VARCHAR(255) NOT NULL ,
  `amount` DECIMAL(10,2) NOT NULL ,
  `type` CHAR(7) NOT NULL ,
  `createdAt` TIMESTAMP NOT NULL ,
  PRIMARY KEY (`invoiceNo`) ,
  INDEX `fk_from_idx` (`from` ASC) ,
  INDEX `fk_to_idx` (`to` ASC) ,
  CONSTRAINT `fk_from`
    FOREIGN KEY (`from` )
    REFERENCES `karfahrpark`.`Users` (`username` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_to`
    FOREIGN KEY (`to` )
    REFERENCES `karfahrpark`.`Users` (`username` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `karfahrpark` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
