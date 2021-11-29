update wc_t_po set nofshipallow=2 where poid='300025425PI1';

insert into wc_t_shipment_ETA (pono, lcNo, shipNo, shipmode, scheduleETA, insertby, inserton, insertfrom, status)
SELECT pono, lcNo, 2, shipmode, curdate(), insertby, now(), insertfrom, 0 FROM wc_t_shipment_ETA where pono='300025425PI1';

insert into wc_t_action_log (`RefID`, `PO`, `ActionID`, `Status`, `Msg`, `UserMsg`, `XRefID`, `PI`, `shipNo`, `TargetForm`, `ActionBy`, `ActionByRole`, `ActionOn`, `BaseActionOn`, `ActionFrom`, `SLADate`)
select `RefID`, `PO`, `ActionID`, 0, 'Shipment #2 schedule accepted by Buyer against PO# 300025425PI1', `UserMsg`, `XRefID`, `PI`, 2, `TargetForm`, `ActionBy`, `ActionByRole`, `ActionOn`, `BaseActionOn`, `ActionFrom`, `SLADate` 
from wc_t_action_log where ActionID=58 and PO='300025425PI1';