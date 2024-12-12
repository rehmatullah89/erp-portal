@echo on
cd C:\Program Files\7-Zip
7z e C:\wamp\www\portal\files\mysql\dbPortal.zip -oC:\wamp\www\portal\files\mysql\
cd C:\MySQL Server 5.5\bin\
mysql --user=root --password=3tree  dbPortal < "C:\wamp\www\portal\files\mysql\dbPortal.sql"
del C:\wamp\www\portal\files\mysql\dbPortal.sql
del C:\wamp\www\portal\files\mysql\dbPortal.zip
timeout /T 60
@echo on