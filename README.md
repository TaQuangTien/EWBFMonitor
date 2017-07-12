# EWBFMonitor
EWBF Miner Monitor

Requires:
- EWBF Version 0.3.4b
- JavaRE
- Web server can run PHP
- MySQL Server

How to use:
1. Create data table on MySQL Server using [Create_Data_Table.sql](Create_Data_Table.sql)
2. Copy [these PHP files](Webpage) to your Web server and fill your database information
3. In EWBF Miner: active http api to get statistics by add extras option to your Start.bat file: --api 
(eg: miner --server eu1-zcash.flypool.org --port 3333 --user t1QmwNgVc6VrhTuGgL9so7jeVz1BYcN3dPz.rig0 --pass x --api 0.0.0.0:8080)
4. Download current-build to your 24/7 running PC (eg: your mining rig) and fill your 
- EWBF Getstat URL (eg: http://0.0.0.0:8080/getstat)
- MySQL URL (this is where php files been) (eg: http://PUR_YOUR_DOMAIN_HERE/miningrig/)
5. Check Enable monitor to begin.

![Monitor App](https://raw.githubusercontent.com/TaQuangTien/EWBFMonitor/master/Screenshots/monitor.png)

![Database ](https://raw.githubusercontent.com/TaQuangTien/EWBFMonitor/master/Screenshots/mysqldata.png)

![Final result ](https://raw.githubusercontent.com/TaQuangTien/EWBFMonitor/master/Screenshots/result.png)
