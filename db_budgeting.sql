/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 100130
Source Host           : localhost:3306
Source Database       : db_budgeting

Target Server Type    : MYSQL
Target Server Version : 100130
File Encoding         : 65001

Date: 2018-09-26 16:56:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cfg_codeprefix
-- ----------------------------
DROP TABLE IF EXISTS `cfg_codeprefix`;
CREATE TABLE `cfg_codeprefix` (
  `CodePost` varchar(5) DEFAULT NULL,
  `LengthCodePost` tinyint(2) DEFAULT NULL,
  `CodePostRealisasi` varchar(5) DEFAULT NULL,
  `LengthCodePostRealisasi` tinyint(2) DEFAULT NULL,
  `CodePostBudget` varchar(5) DEFAULT NULL,
  `YearCodePostBudget` tinyint(1) DEFAULT '0' COMMENT '0 : Tidak pake Code Tahun, 1: Pake Code Tahun',
  `LengthCodePostBudget` tinyint(2) DEFAULT NULL,
  `CodeCatalog` varchar(5) DEFAULT NULL,
  `LengthCodeCatalog` tinyint(2) DEFAULT NULL,
  `CodeSupplier` varchar(5) DEFAULT NULL,
  `LengthCodeSupplier` tinyint(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 MAX_ROWS=1;

-- ----------------------------
-- Records of cfg_codeprefix
-- ----------------------------
INSERT INTO `cfg_codeprefix` VALUES ('PS', '7', 'PSR', '7', 'PSB', '1', '10', 'CT', '6', 'CS', '6');

-- ----------------------------
-- Table structure for cfg_dateperiod
-- ----------------------------
DROP TABLE IF EXISTS `cfg_dateperiod`;
CREATE TABLE `cfg_dateperiod` (
  `Year` year(4) NOT NULL,
  `StartPeriod` date DEFAULT NULL,
  `EndPeriod` date DEFAULT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 : Aktif, 0 : Tidak aktif / delete',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 : Action Not Delete and Update, 1 : Can Be delete and Update',
  PRIMARY KEY (`Year`,`Active`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cfg_dateperiod
-- ----------------------------
INSERT INTO `cfg_dateperiod` VALUES ('2018', '2018-09-30', '2019-08-31', '1', '1');
INSERT INTO `cfg_dateperiod` VALUES ('2019', '2019-09-30', '2020-08-31', '1', '1');
INSERT INTO `cfg_dateperiod` VALUES ('2020', '2020-09-30', '2021-08-31', '1', '1');

-- ----------------------------
-- Table structure for cfg_group_user
-- ----------------------------
DROP TABLE IF EXISTS `cfg_group_user`;
CREATE TABLE `cfg_group_user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `GroupAuth` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cfg_group_user
-- ----------------------------
INSERT INTO `cfg_group_user` VALUES ('1', 'Super Admin');
INSERT INTO `cfg_group_user` VALUES ('2', 'Administrator');
INSERT INTO `cfg_group_user` VALUES ('3', 'Kabag');
INSERT INTO `cfg_group_user` VALUES ('4', 'Admin');
INSERT INTO `cfg_group_user` VALUES ('5', 'Viewer');

-- ----------------------------
-- Table structure for cfg_menu
-- ----------------------------
DROP TABLE IF EXISTS `cfg_menu`;
CREATE TABLE `cfg_menu` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Menu` varchar(50) NOT NULL,
  `Icon` varchar(255) NOT NULL,
  `IDDepartement` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`,`Menu`),
  UNIQUE KEY `unique_nm` (`Menu`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cfg_menu
-- ----------------------------
INSERT INTO `cfg_menu` VALUES ('1', 'Dashboard', 'icon-dashboard', 'NA.9');
INSERT INTO `cfg_menu` VALUES ('2', 'Configuration', 'fa fa-wrench', 'NA.9');
INSERT INTO `cfg_menu` VALUES ('3', 'Master', 'fa fa-globe', 'NA.9');

-- ----------------------------
-- Table structure for cfg_m_userrole
-- ----------------------------
DROP TABLE IF EXISTS `cfg_m_userrole`;
CREATE TABLE `cfg_m_userrole` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NameUserRole` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cfg_m_userrole
-- ----------------------------
INSERT INTO `cfg_m_userrole` VALUES ('1', 'Admin');
INSERT INTO `cfg_m_userrole` VALUES ('2', 'Approver 1');
INSERT INTO `cfg_m_userrole` VALUES ('3', 'Approver 2');
INSERT INTO `cfg_m_userrole` VALUES ('4', 'Approver 3');
INSERT INTO `cfg_m_userrole` VALUES ('5', 'Approver 4');

-- ----------------------------
-- Table structure for cfg_post
-- ----------------------------
DROP TABLE IF EXISTS `cfg_post`;
CREATE TABLE `cfg_post` (
  `CodePost` varchar(255) NOT NULL,
  `PostName` varchar(255) NOT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 : Aktif, 0 : Tidak aktif / delete',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 : Action Not Delete, 1 : Can Be delete',
  `CreatedBy` varchar(255) DEFAULT NULL,
  `CreatedAt` date DEFAULT NULL,
  PRIMARY KEY (`CodePost`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cfg_post
-- ----------------------------
INSERT INTO `cfg_post` VALUES ('PS-0001', 'Operasional', '1', '0', '2018018', '2018-09-21');
INSERT INTO `cfg_post` VALUES ('PS-0002', 'Asset', '1', '0', '2018018', '2018-09-21');
INSERT INTO `cfg_post` VALUES ('PS-0003', 'Renovation', '1', '0', '2018018', '2018-09-21');
INSERT INTO `cfg_post` VALUES ('PS-0004', 'Consumable', '1', '0', '2018018', '2018-09-21');
INSERT INTO `cfg_post` VALUES ('PS-0005', 'Training', '1', '0', '2018018', '2018-09-21');

-- ----------------------------
-- Table structure for cfg_postrealisasi
-- ----------------------------
DROP TABLE IF EXISTS `cfg_postrealisasi`;
CREATE TABLE `cfg_postrealisasi` (
  `CodePostRealisasi` varchar(255) NOT NULL,
  `CodePost` varchar(11) DEFAULT NULL,
  `RealisasiPostName` varchar(255) DEFAULT NULL,
  `Departement` varchar(20) NOT NULL DEFAULT '0' COMMENT '0 : All Departement',
  `Active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 : Aktif, 0 : Tidak aktif / delete',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 : Action Not Delete, 1 : Can Be delete',
  `CreatedBy` varchar(255) DEFAULT NULL,
  `CreatedAt` date DEFAULT NULL,
  PRIMARY KEY (`CodePostRealisasi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cfg_postrealisasi
-- ----------------------------
INSERT INTO `cfg_postrealisasi` VALUES ('PSR-001', 'PS-0001', 'Operasional', 'NA.12', '1', '0', '2018018', '2018-09-21');
INSERT INTO `cfg_postrealisasi` VALUES ('PSR-002', 'PS-0002', 'Comp', 'NA.12', '1', '0', '2018018', '2018-09-21');
INSERT INTO `cfg_postrealisasi` VALUES ('PSR-003', 'PS-0002', 'Non Comp', 'NA.12', '1', '0', '2018018', '2018-09-21');
INSERT INTO `cfg_postrealisasi` VALUES ('PSR-004', 'PS-0003', 'Renovation', 'NA.12', '1', '1', '2018018', '2018-09-21');
INSERT INTO `cfg_postrealisasi` VALUES ('PSR-005', 'PS-0004', 'Stock', 'NA.12', '1', '1', '2018018', '2018-09-21');
INSERT INTO `cfg_postrealisasi` VALUES ('PSR-006', 'PS-0004', 'Non Stock', 'NA.12', '1', '1', '2018018', '2018-09-21');
INSERT INTO `cfg_postrealisasi` VALUES ('PSR-007', 'PS-0005', 'Training', 'NA.12', '1', '1', '2018018', '2018-09-21');

-- ----------------------------
-- Table structure for cfg_rule_g_user
-- ----------------------------
DROP TABLE IF EXISTS `cfg_rule_g_user`;
CREATE TABLE `cfg_rule_g_user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `cfg_group_user` varchar(20) NOT NULL,
  `ID_cfg_sub_menu` int(11) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `write` tinyint(1) NOT NULL DEFAULT '0',
  `update` tinyint(1) NOT NULL DEFAULT '0',
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  `Active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `unique` (`cfg_group_user`,`ID_cfg_sub_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cfg_rule_g_user
-- ----------------------------
INSERT INTO `cfg_rule_g_user` VALUES ('1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `cfg_rule_g_user` VALUES ('2', '1', '2', '1', '1', '1', '1', '1');
INSERT INTO `cfg_rule_g_user` VALUES ('3', '1', '3', '1', '1', '1', '1', '1');
INSERT INTO `cfg_rule_g_user` VALUES ('4', '1', '4', '1', '1', '1', '1', '1');

-- ----------------------------
-- Table structure for cfg_set_post
-- ----------------------------
DROP TABLE IF EXISTS `cfg_set_post`;
CREATE TABLE `cfg_set_post` (
  `CodePostBudget` varchar(255) NOT NULL,
  `CodeSubPost` varchar(255) DEFAULT NULL,
  `Year` year(4) DEFAULT NULL,
  `Budget` decimal(15,2) DEFAULT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 : Aktif, 0 : Tidak aktif / delete',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 : Action Not Delete, 1 : Can Be delete',
  `CreatedBy` varchar(255) DEFAULT NULL,
  `CreatedAt` date DEFAULT NULL,
  `LastUpdateBy` varchar(255) DEFAULT NULL,
  `LastUpdateAt` datetime DEFAULT NULL,
  PRIMARY KEY (`CodePostBudget`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cfg_set_post
-- ----------------------------
INSERT INTO `cfg_set_post` VALUES ('PSB18-0001', 'PSR-001', '2018', '20000000.00', '1', '1', '2018018', '2018-09-21', '2018018', '2018-09-24 09:39:04');
INSERT INTO `cfg_set_post` VALUES ('PSB18-0002', 'PSR-002', '2018', '100000000.00', '1', '1', '2018018', '2018-09-24', '2018018', '2018-09-24 14:41:51');
INSERT INTO `cfg_set_post` VALUES ('PSB18-0003', 'PSR-003', '2018', '10000000.00', '1', '1', '2018018', '2018-09-24', null, null);

-- ----------------------------
-- Table structure for cfg_set_roleuser
-- ----------------------------
DROP TABLE IF EXISTS `cfg_set_roleuser`;
CREATE TABLE `cfg_set_roleuser` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_m_userrole` int(11) NOT NULL,
  `NIP` varchar(255) NOT NULL,
  `Departement` varchar(20) NOT NULL DEFAULT '0' COMMENT '0 : All Departement',
  `Active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 : Aktif, 0 : Tidak aktif / delete',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 : Action Not Edit, 1 : Can Be Edit',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cfg_set_roleuser
-- ----------------------------
INSERT INTO `cfg_set_roleuser` VALUES ('1', '1', '2018018', 'NA.12', '1', '1');
INSERT INTO `cfg_set_roleuser` VALUES ('2', '2', '2018034', 'NA.12', '1', '1');
INSERT INTO `cfg_set_roleuser` VALUES ('3', '3', '2014078', 'NA.12', '1', '1');
INSERT INTO `cfg_set_roleuser` VALUES ('5', '4', '9907003', 'NA.12', '1', '1');

-- ----------------------------
-- Table structure for cfg_set_userrole
-- ----------------------------
DROP TABLE IF EXISTS `cfg_set_userrole`;
CREATE TABLE `cfg_set_userrole` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CodePostRealisasi` varchar(255) NOT NULL,
  `Entry` tinyint(1) NOT NULL DEFAULT '0',
  `Approved` tinyint(1) NOT NULL DEFAULT '0',
  `Cancel` tinyint(1) NOT NULL DEFAULT '0',
  `ID_m_userrole` int(11) NOT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 : Aktif, 0 : Tidak aktif / delete',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 : Action Not Delete, 1 : Can Be delete',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cfg_set_userrole
-- ----------------------------
INSERT INTO `cfg_set_userrole` VALUES ('1', 'PSR-004', '1', '0', '0', '1', '1', '1');
INSERT INTO `cfg_set_userrole` VALUES ('2', 'PSR-004', '1', '1', '1', '2', '1', '1');
INSERT INTO `cfg_set_userrole` VALUES ('3', 'PSR-004', '1', '1', '1', '3', '1', '1');
INSERT INTO `cfg_set_userrole` VALUES ('4', 'PSR-004', '1', '1', '1', '4', '1', '1');
INSERT INTO `cfg_set_userrole` VALUES ('5', 'PSR-001', '1', '0', '1', '1', '1', '1');
INSERT INTO `cfg_set_userrole` VALUES ('6', 'PSR-001', '1', '1', '1', '2', '1', '1');
INSERT INTO `cfg_set_userrole` VALUES ('7', 'PSR-001', '1', '1', '1', '3', '1', '1');
INSERT INTO `cfg_set_userrole` VALUES ('8', 'PSR-003', '1', '0', '0', '1', '1', '1');
INSERT INTO `cfg_set_userrole` VALUES ('9', 'PSR-002', '1', '1', '1', '1', '1', '1');
INSERT INTO `cfg_set_userrole` VALUES ('10', 'PSR-002', '1', '1', '1', '2', '1', '1');
INSERT INTO `cfg_set_userrole` VALUES ('11', 'PSR-003', '1', '0', '0', '2', '1', '1');
INSERT INTO `cfg_set_userrole` VALUES ('12', 'PSR-002', '1', '1', '1', '3', '1', '1');

-- ----------------------------
-- Table structure for cfg_sub_menu
-- ----------------------------
DROP TABLE IF EXISTS `cfg_sub_menu`;
CREATE TABLE `cfg_sub_menu` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_Menu` int(11) NOT NULL,
  `SubMenu1` varchar(50) NOT NULL DEFAULT 'Empty',
  `SubMenu2` varchar(50) NOT NULL DEFAULT 'Empty',
  `Slug` varchar(255) NOT NULL,
  `Controller` varchar(255) NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `write` tinyint(1) NOT NULL DEFAULT '0',
  `update` tinyint(1) NOT NULL DEFAULT '0',
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `unique_cfg_submenu` (`ID_Menu`,`SubMenu1`,`SubMenu2`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of cfg_sub_menu
-- ----------------------------
INSERT INTO `cfg_sub_menu` VALUES ('1', '1', 'Empty', 'Empty', 'budgeting', 'page/budgeting/c_budgeting', '1', '1', '1', '1');
INSERT INTO `cfg_sub_menu` VALUES ('2', '2', 'Empty', 'Empty', 'budgeting_configfinance', 'page/budgeting/c_budgeting/configfinance', '1', '1', '1', '1');
INSERT INTO `cfg_sub_menu` VALUES ('3', '3', 'Catalog', 'Empty', 'budgeting/master/catalog', 'page/budgeting/finance/c_master/catalog', '1', '1', '1', '1');
INSERT INTO `cfg_sub_menu` VALUES ('4', '3', 'Supplier', 'Empty', 'budgeting/master/supplier', 'page/budgeting/finance/c_master/supplier', '1', '1', '1', '1');

-- ----------------------------
-- Table structure for log_cfg_set_post
-- ----------------------------
DROP TABLE IF EXISTS `log_cfg_set_post`;
CREATE TABLE `log_cfg_set_post` (
  `CodePostBudget` varchar(255) NOT NULL,
  `Time` datetime DEFAULT NULL,
  `ActionBy` varchar(255) DEFAULT NULL,
  `Detail` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of log_cfg_set_post
-- ----------------------------
INSERT INTO `log_cfg_set_post` VALUES ('PSB18-0001', '2018-09-21 16:52:04', '2018018', '{\"action\":\"Created\"}');
INSERT INTO `log_cfg_set_post` VALUES ('PSB18-0001', '2018-09-24 09:39:04', '2018018', '{\"action\":\"Edited\",\"Detail\":{\"Before\":{\"Budget\":\"25000000.00\"},\"After\":{\"Budget\":\"20000000\"}}}');
INSERT INTO `log_cfg_set_post` VALUES ('PSB18-0002', '2018-09-24 14:37:30', '2018018', '{\"action\":\"Created\"}');
INSERT INTO `log_cfg_set_post` VALUES ('PSB18-0002', '2018-09-24 14:37:40', '2018018', '{\"action\":\"Edited\",\"Detail\":{\"Before\":{\"Budget\":\"35000000.00\"},\"After\":{\"Budget\":\"100000000\"}}}');
INSERT INTO `log_cfg_set_post` VALUES ('PSB18-0003', '2018-09-24 15:36:14', '2018018', '{\"action\":\"Created\"}');

-- ----------------------------
-- Table structure for m_catalog
-- ----------------------------
DROP TABLE IF EXISTS `m_catalog`;
CREATE TABLE `m_catalog` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Item` varchar(255) NOT NULL,
  `Desc` varchar(255) NOT NULL,
  `EstimaValue` decimal(15,2) NOT NULL,
  `Photo` varchar(255) DEFAULT NULL,
  `Departement` varchar(255) NOT NULL,
  `Approval` tinyint(1) NOT NULL DEFAULT '0',
  `Active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 : Aktif, 0 : Tidak aktif / delete',
  `Status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 : Action Not Delete, 1 : Can Be delete',
  `ApprovalBy` varchar(255) DEFAULT NULL,
  `ApprovalAt` datetime DEFAULT NULL,
  `CreatedBy` varchar(255) NOT NULL,
  `CreatedAt` date NOT NULL,
  `LastUpdateBy` varchar(255) DEFAULT NULL,
  `LastUpdateAt` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of m_catalog
-- ----------------------------

-- ----------------------------
-- Table structure for m_supplier
-- ----------------------------
DROP TABLE IF EXISTS `m_supplier`;
CREATE TABLE `m_supplier` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CodeSupplier` varchar(255) DEFAULT NULL,
  `NamaSupplier` varchar(255) DEFAULT NULL,
  `Alamat` varchar(255) DEFAULT NULL,
  `Website` varchar(255) DEFAULT NULL,
  `NoTelp` varchar(255) DEFAULT NULL,
  `NoHp` varchar(255) DEFAULT NULL,
  `DetailInfo` longtext,
  `ItemSupplier` varchar(255) DEFAULT NULL,
  `DetailItem` longtext,
  `Active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 : Aktif, 0 : Tidak aktif / delete',
  `ApprovalBy` varchar(255) DEFAULT NULL,
  `ApprovalAt` datetime DEFAULT NULL,
  `CreatedBy` varchar(255) NOT NULL,
  `CreatedAt` date NOT NULL,
  `LastUpdateBy` varchar(255) DEFAULT NULL,
  `LastUpdateAt` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `CodeSupplier` (`CodeSupplier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of m_supplier
-- ----------------------------

-- ----------------------------
-- Table structure for previleges_guser
-- ----------------------------
DROP TABLE IF EXISTS `previleges_guser`;
CREATE TABLE `previleges_guser` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NIP` varchar(255) DEFAULT NULL,
  `G_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `NIP` (`NIP`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of previleges_guser
-- ----------------------------
INSERT INTO `previleges_guser` VALUES ('1', '2018018', '1');
