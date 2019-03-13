-- create table
CREATE TABLE `ost_article_families_keys` (
  `id` int(11) NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ost_article_families_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_E98F30CE4E645A7E` (`key`);

ALTER TABLE `ost_article_families_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
