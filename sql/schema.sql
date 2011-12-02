DROP TABLE IF EXISTS `bw_story`;
DROP TABLE IF EXISTS `bw_contributor`;

CREATE TABLE bw_contributor (
	id int(11) unsigned NOT NULL auto_increment,
	name varchar(64) NOT NULL,
	email varchar(128) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE bw_story(
	id int(11) unsigned NOT NULL auto_increment,
	contributor int(11) unsigned NOT NULL,
	title varchar(255) NOT NULL DEFAULT 'untitled',
	story text NOT NULL,
	location varchar(255) NOT NULL DEFAULT '',
	season char(4) NOT NULL,
	PRIMARY KEY (id),
	CONSTRAINT FOREIGN KEY (contributor) REFERENCES bw_contributor (id)
);
