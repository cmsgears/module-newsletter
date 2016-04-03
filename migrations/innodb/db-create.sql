/* ============================= CMSGears Newsletter ======================================== */

--
-- Table structure for table `cmg_newsletter`
--

DROP TABLE IF EXISTS `cmg_newsletter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_newsletter` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `templateId` bigint(20) DEFAULT NULL,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `lastSentAt` datetime DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_newsletter_1` (`templateId`),
  KEY `fk_newsletter_2` (`createdBy`),
  KEY `fk_newsletter_3` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_newsletter_member`
--

DROP TABLE IF EXISTS `cmg_newsletter_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_newsletter_member` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_newsletter_list`
--

DROP TABLE IF EXISTS `cmg_newsletter_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_newsletter_list` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `newsletterId` bigint(20) NOT NULL,
  `memberId` bigint(20) NOT NULL,
  `active` tinyint DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_newsletter_list_1` (`newsletterId`),
  KEY `fk_newsletter_list_2` (`memberId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET FOREIGN_KEY_CHECKS=0;

--
-- Constraints for table `cmg_newsletter`
--

ALTER TABLE `cmg_newsletter`
	ADD CONSTRAINT `fk_newsletter_1` FOREIGN KEY (`templateId`) REFERENCES `cmg_core_template` (`id`),
  	ADD CONSTRAINT `fk_newsletter_2` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
  	ADD CONSTRAINT `fk_newsletter_3` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_newsletter_list`
--

ALTER TABLE `cmg_newsletter_list`
  	ADD CONSTRAINT `fk_newsletter_list_1` FOREIGN KEY (`newsletterId`) REFERENCES `cmg_newsletter` (`id`),
  	ADD CONSTRAINT `fk_newsletter_list_2` FOREIGN KEY (`memberId`) REFERENCES `cmg_core_user` (`id`);

SET FOREIGN_KEY_CHECKS=1;