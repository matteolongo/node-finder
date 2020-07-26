CREATE TABLE `node-finder`.`node_tree_names` (
  `idNode` INT NOT NULL,
  `language` ENUM('english', 'italian') NOT NULL,
  `nodeName` VARCHAR(255) NOT NULL);

ALTER TABLE `node-finder`.`node_tree_names`
ADD INDEX `idNode_idx` (`idNode` ASC);
ALTER TABLE `node-finder`.`node_tree_names`
ADD CONSTRAINT `idNode`
  FOREIGN KEY (`idNode`)
  REFERENCES `node-finder`.`node_tree` (`idNode`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `node-finder`.`node_tree_names`
ADD UNIQUE INDEX `idNode_language_idx` (`idNode` ASC, `language` ASC);

-- seed data
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('1', 'english', 'Marketing');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('1', 'italian', 'Marketing');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('2', 'english', 'Helpdesk');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('2', 'italian', 'Supporto Tecnico');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('3', 'english', 'managers');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('3', 'italian', 'Managers');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('4', 'english', 'Customer Account');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('4', 'italian', 'Assistenza Cliente');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('5', 'english', 'Docebo');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('5', 'italian', 'Docebo');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('6', 'english', 'Accounting');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('6', 'italian', 'Amministrazione');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('7', 'english', 'Sales');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('7', 'italian', 'Vendite');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('8', 'english', 'Italy');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('8', 'italian', 'Italia');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('9', 'english', 'Europe');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('9', 'italian', 'Europa');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('10', 'english', 'Developers');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('10', 'italian', 'Sviluppatori');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('11', 'english', 'North America');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('11', 'italian', 'Nord America');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('12', 'english', 'Quality Assurance');
INSERT INTO `node-finder`.`node_tree_names` (`idNode`, `language`, `nodeName`) VALUES ('12', 'italian', 'Controllo Qualità');


