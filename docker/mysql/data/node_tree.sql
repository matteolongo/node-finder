CREATE TABLE `node-finder`.`node_tree` (
  `idNode` INT NOT NULL AUTO_INCREMENT,
  `level` INT NOT NULL,
  `iLeft` INT NOT NULL,
  `iRight` INT NOT NULL,
  PRIMARY KEY (`idNode`),
  UNIQUE INDEX `idNode_UNIQUE` (`idNode` ASC));

-- SEED DATA
INSERT INTO `node-finder`.`node_tree` (`idNode`, `level`, `iLeft`, `iRight`) VALUES ('1', '2', '2', '3');
INSERT INTO `node-finder`.`node_tree` (`idNode`, `level`, `iLeft`, `iRight`) VALUES ('2', '2', '4', '5');
INSERT INTO `node-finder`.`node_tree` (`idNode`, `level`, `iLeft`, `iRight`) VALUES ('3', '2', '6', '7');
INSERT INTO `node-finder`.`node_tree` (`idNode`, `level`, `iLeft`, `iRight`) VALUES ('4', '2', '8', '9');
INSERT INTO `node-finder`.`node_tree` (`idNode`, `level`, `iLeft`, `iRight`) VALUES ('5', '1', '1', '24');
INSERT INTO `node-finder`.`node_tree` (`idNode`, `level`, `iLeft`, `iRight`) VALUES ('6', '2', '10', '11');
INSERT INTO `node-finder`.`node_tree` (`idNode`, `level`, `iLeft`, `iRight`) VALUES ('7', '2', '12', '19');
INSERT INTO `node-finder`.`node_tree` (`idNode`, `level`, `iLeft`, `iRight`) VALUES ('8', '3', '15', '16');
INSERT INTO `node-finder`.`node_tree` (`idNode`, `level`, `iLeft`, `iRight`) VALUES ('9', '3', '17', '18');
INSERT INTO `node-finder`.`node_tree` (`idNode`, `level`, `iLeft`, `iRight`) VALUES ('10', '2', '20', '21');
INSERT INTO `node-finder`.`node_tree` (`idNode`, `level`, `iLeft`, `iRight`) VALUES ('11', '3', '13', '14');
INSERT INTO `node-finder`.`node_tree` (`idNode`, `level`, `iLeft`, `iRight`) VALUES ('12', '2', '22', '23');
