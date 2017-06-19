# turf
Turf map for GTA:San Andreas

This is a turf map made for Project San Andreas multiplayer game server. See the SQL tables listed below.

### SQL structure
```sql
CREATE TABLE IF NOT EXISTS `gangs` (
  `id` int(11) NOT NULL COMMENT 'A gang id-je, ez a kulcs',
  `name` varchar(256) NOT NULL COMMENT 'A gang neve',
  `thread` varchar(512) NOT NULL COMMENT 'A témájukhoz való link',
  `fillcolor` varchar(8) NOT NULL DEFAULT 'FFFFFF' COMMENT 'A bandához tartozó területeket ilyenre kell színezni. Formátum: RRGGBB'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `points` (
  `id` int(11) NOT NULL COMMENT 'A turf point id-je, ez a kulcs',
  `turfid` int(11) NOT NULL COMMENT 'A turf id-je, amihez az adott pont tartozik',
  `x` int(11) NOT NULL COMMENT 'X koordináta',
  `y` int(11) NOT NULL COMMENT 'Y koordináta'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `turfs` (
  `id` int(11) NOT NULL COMMENT 'A turf ID-je',
  `gangid` int(11) NOT NULL COMMENT 'A turföt birtokló gang ID-je'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

```
